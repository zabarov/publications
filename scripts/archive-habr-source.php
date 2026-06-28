<?php

declare(strict_types=1);

if ($argc < 2) {
    fwrite(STDERR, "Usage: php scripts/archive-habr-source.php <habr-article-url-or-id> [...]\n");
    exit(1);
}

$root = dirname(__DIR__);
$archiveRoot = $root . '/source/articles/habr';

function fetchUrl(string $url): string
{
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'header' => implode("\r\n", [
                'User-Agent: Mozilla/5.0 publication-archive/1.0',
                'Accept: text/html,application/xhtml+xml',
            ]),
            'timeout' => 30,
        ],
    ]);

    $html = @file_get_contents($url, false, $context);
    if ($html === false || trim($html) === '') {
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => 'Mozilla/5.0 publication-archive/1.0',
            CURLOPT_HTTPHEADER => [
                'Accept: text/html,application/xhtml+xml',
            ],
        ]);
        $html = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($html === false || trim((string) $html) === '' || $status >= 400) {
            throw new RuntimeException("Cannot fetch Habr page: {$url}" . ($error !== '' ? " ({$error})" : ''));
        }
    }

    return $html;
}

function articleIdFromInput(string $input): string
{
    if (preg_match('/^\d+$/', $input)) {
        return $input;
    }

    if (preg_match('~/articles/(\d+)/?~', $input, $matches)) {
        return $matches[1];
    }

    throw new InvalidArgumentException("Cannot detect Habr article id from: {$input}");
}

function habrUrl(string $id): string
{
    return "https://habr.com/ru/articles/{$id}/";
}

function extractPiniaState(string $html): array
{
    if (!preg_match('/window\.__PINIA_STATE__=(\{.*?\});\s*\(function\(\)/s', $html, $matches)
        && !preg_match('/window\.__PINIA_STATE__=(\{.*?\})<\/script>/s', $html, $matches)
    ) {
        throw new RuntimeException('Cannot find Habr __PINIA_STATE__ payload.');
    }

    $state = json_decode($matches[1], true);
    if (!is_array($state)) {
        throw new RuntimeException('Cannot decode Habr __PINIA_STATE__ payload.');
    }

    return $state;
}

function extractJsonLd(string $html): array
{
    if (!preg_match_all('/<script type="application\/ld\+json"[^>]*>(.*?)<\/script>/s', $html, $matches)) {
        return [];
    }

    foreach ($matches[1] as $payload) {
        $json = json_decode(html_entity_decode($payload, ENT_QUOTES | ENT_HTML5, 'UTF-8'), true);
        if (is_array($json) && ($json['@type'] ?? '') === 'Article') {
            return $json;
        }
    }

    return [];
}

function cleanText(string $text): string
{
    return trim(preg_replace('/\s+/u', ' ', html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8')));
}

function cleanHtmlTitle(string $html): string
{
    return cleanText(strip_tags($html));
}

function inlineMarkdown(DOMNode $node): string
{
    if ($node instanceof DOMText) {
        return $node->wholeText;
    }

    if (!$node instanceof DOMElement) {
        $text = '';
        foreach ($node->childNodes as $child) {
            $text .= inlineMarkdown($child);
        }
        return $text;
    }

    $children = '';
    foreach ($node->childNodes as $child) {
        $children .= inlineMarkdown($child);
    }

    $children = cleanText($children);
    $tag = strtolower($node->tagName);

    return match ($tag) {
        'strong', 'b' => $children === '' ? '' : "**{$children}**",
        'em', 'i' => $children === '' ? '' : "*{$children}*",
        'code' => $children === '' ? '' : '`' . str_replace('`', '\\`', $children) . '`',
        'a' => $children === '' ? '' : '[' . $children . '](' . $node->getAttribute('href') . ')',
        'br' => "\n",
        default => $children,
    };
}

function blockMarkdown(DOMNode $node, int $listLevel = 0): string
{
    if ($node instanceof DOMText) {
        return trim($node->wholeText);
    }

    if (!$node instanceof DOMElement) {
        return blockMarkdownChildren($node, $listLevel);
    }

    $tag = strtolower($node->tagName);

    if ($tag === 'p') {
        $text = inlineMarkdown($node);
        return $text === '' ? '' : $text . "\n\n";
    }

    if (preg_match('/^h([1-6])$/', $tag, $matches)) {
        $level = max(2, (int) $matches[1]);
        $text = inlineMarkdown($node);
        return $text === '' ? '' : str_repeat('#', $level) . ' ' . $text . "\n\n";
    }

    if ($tag === 'pre') {
        $code = rtrim(html_entity_decode($node->textContent, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        return $code === '' ? '' : "```\n{$code}\n```\n\n";
    }

    if (in_array($tag, ['ul', 'ol'], true)) {
        $markdown = '';
        $index = 1;
        foreach ($node->childNodes as $child) {
            if (!$child instanceof DOMElement || strtolower($child->tagName) !== 'li') {
                continue;
            }
            $prefix = $tag === 'ol' ? "{$index}. " : '- ';
            $item = trim(blockMarkdown($child, $listLevel + 1));
            $item = preg_replace('/\n{2,}/', "\n", $item);
            $markdown .= str_repeat('  ', $listLevel) . $prefix . str_replace("\n", "\n" . str_repeat('  ', $listLevel + 1), $item) . "\n";
            $index++;
        }
        return $markdown . "\n";
    }

    if ($tag === 'li') {
        $parts = [];
        foreach ($node->childNodes as $child) {
            $part = trim(blockMarkdown($child, $listLevel));
            if ($part !== '') {
                $parts[] = $part;
            }
        }
        return trim(implode("\n", $parts));
    }

    if ($tag === 'blockquote') {
        $text = trim(blockMarkdownChildren($node, $listLevel));
        return $text === '' ? '' : preg_replace('/^/m', '> ', $text) . "\n\n";
    }

    if ($tag === 'img') {
        $src = $node->getAttribute('src');
        $alt = $node->getAttribute('alt');
        return $src === '' ? '' : "![{$alt}]({$src})\n\n";
    }

    return blockMarkdownChildren($node, $listLevel);
}

function blockMarkdownChildren(DOMNode $node, int $listLevel = 0): string
{
    $markdown = '';
    foreach ($node->childNodes as $child) {
        $markdown .= blockMarkdown($child, $listLevel);
    }
    return $markdown;
}

function htmlToMarkdown(string $html): string
{
    $document = new DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $document->loadHTML('<?xml encoding="UTF-8"><body>' . $html . '</body>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    $body = $document->getElementsByTagName('body')->item(0);
    if (!$body) {
        return trim(strip_tags($html)) . "\n";
    }

    $markdown = blockMarkdownChildren($body);
    $markdown = preg_replace("/\n{3,}/", "\n\n", $markdown);

    return trim($markdown) . "\n";
}

function yamlString(string $value): string
{
    return '"' . str_replace('"', '\"', $value) . '"';
}

function writeArticle(string $archiveRoot, string $id, array $article, array $jsonLd, string $rawHtml): void
{
    $dir = "{$archiveRoot}/{$id}";
    if (!is_dir($dir) && !mkdir($dir, 0775, true) && !is_dir($dir)) {
        throw new RuntimeException("Cannot create archive directory: {$dir}");
    }

    $title = cleanHtmlTitle($article['titleHtml'] ?? ($jsonLd['headline'] ?? "Habr article {$id}"));
    $publishedAt = $article['timePublished'] ?? ($jsonLd['datePublished'] ?? '');
    $canonicalUrl = habrUrl($id);
    $bodyHtml = $article['textHtml'] ?? '';
    if ($bodyHtml === '') {
        throw new RuntimeException("Article {$id} does not contain textHtml.");
    }

    $hubs = array_map(static fn (array $hub): string => cleanHtmlTitle($hub['titleHtml'] ?? $hub['title'] ?? ''), $article['hubs'] ?? []);
    $tags = array_map(static fn (array $tag): string => cleanHtmlTitle($tag['titleHtml'] ?? $tag['title'] ?? ''), $article['tags'] ?? []);

    $markdown = "---\n";
    $markdown .= "source: \"habr\"\n";
    $markdown .= "habr_article_id: " . yamlString($id) . "\n";
    $markdown .= "canonical_url: " . yamlString($canonicalUrl) . "\n";
    $markdown .= "published_at: " . yamlString($publishedAt) . "\n";
    $markdown .= "title: " . yamlString($title) . "\n";
    $markdown .= "archive_policy: \"ignored-local-source\"\n";
    $markdown .= "public_text_policy: \"canonical_only_until_explicit_approval\"\n";
    if (array_filter($hubs) !== []) {
        $markdown .= "hubs:\n";
        foreach (array_filter($hubs) as $hub) {
            $markdown .= "  - " . yamlString($hub) . "\n";
        }
    }
    if (array_filter($tags) !== []) {
        $markdown .= "tags:\n";
        foreach (array_filter($tags) as $tag) {
            $markdown .= "  - " . yamlString($tag) . "\n";
        }
    }
    $markdown .= "---\n\n";
    $markdown .= "# {$title}\n\n";
    $markdown .= "> Local ignored recovery copy. The canonical publication URL is {$canonicalUrl}.\n\n";
    $markdown .= htmlToMarkdown($bodyHtml);

    file_put_contents("{$dir}/README.md", $markdown);
    file_put_contents("{$dir}/body.html", $bodyHtml . "\n");
    file_put_contents("{$dir}/source.html", $rawHtml);

    echo "Archived Habr article {$id}: {$dir}/README.md\n";
}

foreach (array_slice($argv, 1) as $input) {
    $id = articleIdFromInput($input);
    $html = fetchUrl(habrUrl($id));
    $state = extractPiniaState($html);
    $jsonLd = extractJsonLd($html);
    $article = $state['articlesList']['articlesList'][$id] ?? null;

    if (!is_array($article)) {
        throw new RuntimeException("Cannot find article {$id} in Habr state.");
    }

    writeArticle($archiveRoot, $id, $article, $jsonLd, $html);
}
