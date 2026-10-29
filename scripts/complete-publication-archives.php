<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

function convert(string $input, string $from, string $to): string
{
    $process = proc_open(['pandoc', '--from=' . $from, '--to=' . $to, '--wrap=none'], [['pipe', 'r'], ['pipe', 'w'], ['pipe', 'w']], $pipes);
    fwrite($pipes[0], $input);
    fclose($pipes[0]);
    $output = stream_get_contents($pipes[1]);
    $error = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    if (proc_close($process) !== 0) {
        throw new RuntimeException('Pandoc failed: ' . $error);
    }
    return $output;
}

function document(string $html): DOMDocument
{
    $dom = new DOMDocument();
    @$dom->loadHTML('<?xml encoding="UTF-8"><html><body>' . $html . '</body></html>', LIBXML_NONET);
    return $dom;
}

function saveImage(string $root, string $id, string $url, array $existing): array
{
    $host = parse_url($url, PHP_URL_HOST);
    if (!in_array($host, ['habrastorage.org', 'miro.medium.com', 'hackernoon.imgix.net', 'media2.dev.to'], true)) {
        return ['url' => $url, 'status' => 'unsupported_image_host'];
    }
    foreach ($existing as $asset) {
        $path = $asset['path'] ?? '';
        if (($asset['url'] ?? '') === $url && ($asset['status'] ?? '') === 'saved'
            && str_starts_with($path, 'assets/publications/') && is_file("{$root}/{$path}")
            && hash_file('sha256', "{$root}/{$path}") === $asset['sha256']) {
            return $asset;
        }
    }
    $curl = curl_init($url);
    curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 25, CURLOPT_CONNECTTIMEOUT => 8, CURLOPT_MAXFILESIZE => 15000000]);
    $bytes = curl_exec($curl);
    $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    curl_close($curl);
    $image = is_string($bytes) ? @getimagesizefromstring($bytes) : false;
    if ($status !== 200 || !$image) {
        return ['url' => $url, 'status' => 'download_failed', 'http_status' => $status];
    }
    $hash = hash('sha256', $bytes);
    $path = "assets/publications/{$id}/" . substr($hash, 0, 16) . '.' . image_type_to_extension($image[2], false);
    if (!is_dir(dirname("{$root}/{$path}"))) {
        mkdir(dirname("{$root}/{$path}"), 0755, true);
    }
    file_put_contents("{$root}/{$path}", $bytes);
    return ['url' => $url, 'status' => 'saved', 'path' => $path, 'sha256' => $hash, 'width' => $image[0], 'height' => $image[1], 'mime' => $image['mime']];
}

$root = dirname(__DIR__);
$apply = in_array('--apply', $argv, true);
$reports = [];
$registryText = file_get_contents("{$root}/data/publications.yml");
$records = Yaml::parse($registryText)['publications'];
foreach ($records as $record) {
    if (($record['public_text_policy'] ?? '') !== 'recovered_snapshot') {
        continue;
    }
    $id = $record['id'];
    $dir = "{$root}/source/completeness/{$id}";
    $item = json_decode(file_get_contents("{$dir}/inventory.json"), true, 512, JSON_THROW_ON_ERROR);
    if (is_file("{$dir}/browser.json")) {
        $item = json_decode(file_get_contents("{$dir}/browser.json"), true, 512, JSON_THROW_ON_ERROR);
        file_put_contents("{$dir}/body.html", $item['html']);
    }
    if ($item['status'] !== 'captured') {
        $reports[] = $item;
        continue;
    }
    $dom = document(file_get_contents("{$dir}/body.html"));
    $xpath = new DOMXPath($dom);
    // Habr appends code-explanation controls; Medium figures contain UI controls.
    foreach (iterator_to_array($xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " code-explainer ")] | //script | //style | //button | //figcaption/button')) as $node) {
        $node->parentNode->removeChild($node);
    }
    foreach (iterator_to_array($xpath->query('//figure')) as $figure) {
        $image = $figure->getElementsByTagName('img')->item(0);
        if (!$image) {
            continue;
        }
        $replacement = $dom->createElement('div');
        $replacement->appendChild($image->cloneNode(true));
        foreach ($figure->getElementsByTagName('figcaption') as $caption) {
            $p = $dom->createElement('p');
            $p->appendChild($dom->createTextNode($caption->textContent));
            $replacement->appendChild($p);
        }
        $figure->parentNode->replaceChild($replacement, $figure);
    }
    foreach ($xpath->query('//*') as $node) {
        foreach (iterator_to_array($node->attributes) as $attribute) {
            if (!in_array($attribute->name, ['href', 'src', 'alt', 'colspan', 'rowspan'], true)) {
                $node->removeAttribute($attribute->name);
            }
        }
    }
    $html = '';
    foreach ($dom->getElementsByTagName('body')->item(0)->childNodes as $child) {
        $html .= $dom->saveHTML($child);
    }
    $markdown = convert($html, 'html-native_divs-native_spans', 'gfm');
    $title = $record['original_title'] ?? $record['title'];
    if (!str_starts_with($markdown, '# ')) {
        $markdown = "# {$title}\n\n" . $markdown;
    }
    file_put_contents("{$dir}/converted.md", $markdown);
    $old = file_get_contents("{$root}/{$record['archive_path']}");
    $originalArchive = $old;
    $stripHeadingImages = function (string $s): string {
        $s = preg_replace('/\A# [^\r\n]*\R/', '', $s, 1);
        return preg_replace('/!\[[^\]]*\]\([^\r\n)]*\)/', '', $s);
    };
    if ($id === 'medium-9b909c8e1241') {
        // The old archive appends platform tags; those are metadata, not prose.
        $old = preg_replace('/\R## Topics\R.*\z/s', '', $old);
    }
    $oldPlain = convert($stripHeadingImages($old), 'gfm', 'plain');
    $newPlain = convert($stripHeadingImages($markdown), 'gfm', 'plain');
    $normalize = fn (string $s): string => preg_replace('/[^\p{L}\p{N}]+/u', '', mb_strtolower($s));
    $oldNormal = $normalize($oldPlain);
    $newNormal = $normalize($newPlain);
    similar_text($oldNormal, $newNormal, $similarity);
    $item['comparison'] = $oldNormal === $newNormal ? 'same_text_ignoring_formatting' : 'text_difference_review_required';
    $item['similarity_percent'] = round($similarity, 2);
    $item['old_text_characters'] = mb_strlen($oldNormal);
    $item['live_text_characters'] = mb_strlen($newNormal);
    file_put_contents("{$dir}/old.txt", $oldPlain);
    file_put_contents("{$dir}/live.txt", $newPlain);
    if ($apply) {
        if ($item['attachments'] !== [] || $item['embeds'] !== []) {
            throw new RuntimeException("Attachments or embedded media require explicit recovery review: {$id}");
        }
        if ($item['comparison'] !== 'same_text_ignoring_formatting') {
            throw new RuntimeException("Prose change requires review: {$id}");
        }
        $package = dirname("{$root}/{$record['archive_path']}");
        $manifest = json_decode(file_get_contents("{$package}/preservation.json"), true, 512, JSON_THROW_ON_ERROR);
        if ($manifest['text_sha256'] !== hash('sha256', $originalArchive)) {
            throw new RuntimeException("Existing archive integrity mismatch: {$id}");
        }
        $assets = $manifest['assets'];
        $requiredImages = $item['images'];
        if (!empty($item['cover_url'])) {
            $requiredImages[] = ['url' => $item['cover_url'], 'alt' => 'Publication cover'];
        }
        $stored = [];
        foreach ($requiredImages as $image) {
            $url = $image['url'];
            if (isset($stored[$url])) {
                continue;
            }
            $asset = saveImage($root, $id, $url, $assets);
            $asset['alt'] = $image['alt'];
            $stored[$url] = $asset;
            if ($asset['status'] === 'saved') {
                $assets = array_values(array_filter($assets, fn ($oldAsset) => ($oldAsset['url'] ?? '') !== $url));
                $assets[] = $asset;
            }
        }
        $failed = array_filter($stored, fn ($asset) => $asset['status'] !== 'saved');
        $coverUrl = $item['cover_url'] ?? ($item['images'][0]['url'] ?? null);
        $cover = $coverUrl ? ($stored[$coverUrl]['path'] ?? null) : null;
        if ($id === 'medium-9b909c8e1241' && !str_contains($originalArchive, '![') && $cover) {
            $originalArchive = preg_replace('/\A(# [^\r\n]+\R)/', '$1' . "\n![Original photo and artistic interpretation](../../../../{$cover})\n", $originalArchive, 1);
            file_put_contents("{$root}/{$record['archive_path']}", $originalArchive);
        }
        $resourceRows = [];
        $externalLinks = [];
        $linksMissingFromOldBody = [];
        foreach ($xpath->query('//a[@href]') as $link) {
            $url = $link->getAttribute('href');
            if (!preg_match('~^https?://~', $url) || isset($externalLinks[$url])) {
                continue;
            }
            $label = trim(preg_replace('/\s+/u', ' ', $link->textContent));
            $label = str_replace(['[', ']', '|'], '', $label);
            $externalLinks[$url] = $label ?: $url;
            if (!str_contains(html_entity_decode($originalArchive), html_entity_decode($url))) {
                $linksMissingFromOldBody[] = $url;
            }
            $resourceRows[] = '- [' . ($label ?: $url) . '](' . $url . ')';
        }
        $excerpt = null;
        if (is_file("{$dir}/excerpt.html")) {
            $excerpt = "# Publication Preview\n\nOriginal platform preview, stored separately from the article body.\n\n" . convert(file_get_contents("{$dir}/excerpt.html"), 'html-native_divs-native_spans', 'gfm');
            $excerpt = preg_replace('/[ \t]+$/m', '', $excerpt);
            file_put_contents("{$package}/preview.md", rtrim($excerpt) . "\n");
        }
        $state = $failed === [] ? 'text_and_media_checked' : 'text_checked_media_gaps';
        if (($item['current_visibility'] ?? '') === 'draft_visible_to_author') {
            $state = 'historical_text_media_checked_author_view';
        }
        $completeness = [
            'publication_id' => $id,
            'checked_at' => $item['checked_at'],
            'source_url' => $record['public_url'],
            'source_kind' => $item['source_kind'] ?? 'public_article_body_and_platform_preview',
            'current_visibility' => $item['current_visibility'] ?? $item['visibility'] ?? 'public_page',
            'text_comparison' => 'Author body matches after ignoring whitespace, Markdown presentation and letter case; platform tags excluded from body comparison.',
            'compared_text_sha256' => hash('sha256', $newNormal),
            'state' => $state,
            'body_image_count' => count($item['images']),
            'platform_cover_count' => !empty($item['cover_url']) ? 1 : 0,
            'media' => array_values($stored),
            'direct_attachments' => $item['attachments'],
            'embedded_media' => $item['embeds'],
            'external_links' => $externalLinks,
            'links_not_literal_in_previous_markdown' => $linksMissingFromOldBody,
            'boundary' => 'Body media and platform cover/preview inspected. Linked services, videos and source-code repositories are references, not mirrored third-party sites or repository snapshots. No direct downloadable attachment was found by extension-based link inventory; extensionless download endpoints require separate review.',
        ];
        file_put_contents("{$package}/completeness.json", json_encode($completeness, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
        $manifest['assets'] = $assets;
        $manifest['text_sha256'] = hash('sha256', $originalArchive);
        $manifest['completeness'] = "Text and media checked on " . substr($item['checked_at'], 0, 10) . '. See completeness.json for the source, scope and external-reference boundary.';
        $manifest['completeness_path'] = 'completeness.json';
        if ($excerpt !== null) {
            $manifest['preview_sha256'] = hash_file('sha256', "{$package}/preview.md");
        }
        file_put_contents("{$package}/preservation.json", json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
        $visibilityNote = ($item['current_visibility'] ?? '') === 'draft_visible_to_author'
            ? "\nThe original Habr page currently shows a draft to the signed-in author; anonymous access returned HTTP 403. This archive preserves the previously published text. No unpublished replacement text was added.\n" : '';
        $mediaMarkdown = $cover ? "\n![Publication cover](../../../../{$cover})\n" : '';
        $previewLink = $excerpt !== null ? "\n[Platform preview](preview.md)\n" : '';
        file_put_contents("{$package}/README.md", "# {$record['title']}\n\n[Original publication]({$record['public_url']}) | [Read archived text](" . basename($record['archive_path']) . ")\n\nAuthor: Rim Zabarov. Original language: {$record['original_language']}.\n\nChecked against the source on " . substr($item['checked_at'], 0, 10) . ". The author body matches the saved edition after presentation normalization. Body images and the separately stored platform cover were inventoried; available files are local. Earlier editions remain in Git history.\n" . $mediaMarkdown . $previewLink . $visibilityNote . "\n## Linked Resources\n\n" . ($resourceRows ? implode("\n", $resourceRows) : 'No external body hyperlinks observed.') . "\n\nLinked services, videos and code repositories remain external references. No direct file attachment was found in this article's extension-based link inventory; this is not an archive of the linked websites.\n\nSource and completeness data: [preservation](preservation.json), [completeness](completeness.json). Original media rights and attribution remain unchanged.\n");
        $updates = ['preservation_status' => $state, 'completeness_checked_at' => substr($item['checked_at'], 0, 10)];
        if ($cover) {
            $updates['cover_path'] = $cover;
        }
        if (($item['current_visibility'] ?? '') === 'draft_visible_to_author') {
            $updates['live_verification_status'] = 'draft_visible_to_author_public_access_403';
        }
        foreach ($updates as $field => $value) {
            $registryText = preg_replace_callback('/(^  - id: "' . preg_quote($id, '/') . '"\R)(.*?)(?=^  - id:|\z)/ms', function ($match) use ($field, $value) {
                $line = '    ' . $field . ': ' . json_encode($value, JSON_UNESCAPED_SLASHES) . "\n";
                $body = preg_match('/^    ' . $field . ':/m', $match[2])
                    ? preg_replace('/^    ' . $field . ':.*\R/m', $line, $match[2]) : $line . $match[2];
                return $match[1] . $body;
            }, $registryText);
        }
        $item['preservation_status'] = $state;
        $item['stored_media_count'] = count($stored) - count($failed);
    }
    $reports[] = $item;
    echo "{$id}: {$item['comparison']}; {$item['similarity_percent']}%; {$item['old_text_characters']} -> {$item['live_text_characters']}\n";
}
file_put_contents("{$root}/source/completeness/comparisons.json", json_encode($reports, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
if ($apply) {
    file_put_contents("{$root}/data/publications.yml", $registryText);
    $rows = [];
    foreach ($reports as $report) {
        $record = array_values(array_filter($records, fn ($r) => $r['id'] === $report['id']))[0];
        $link = '../' . substr(dirname($record['archive_path']), strlen('publications/')) . '/README.md';
        $result = match ($report['preservation_status'] ?? '') {
            'text_and_media_checked' => 'Text and images checked',
            'historical_text_media_checked_author_view' => 'Checked against author view; original now a draft',
            'text_checked_media_gaps' => 'Text checked; image recovery gaps remain',
            default => 'Source unavailable',
        };
        $rows[] = "| [{$record['title']}]({$link}) | {$result} | " . count($report['images'] ?? []) . ' | ' . ($report['stored_media_count'] ?? 0) . ' |';
    }
    file_put_contents("{$root}/publications/articles/completeness.md", "# Historical Archive Completeness\n\nChecked October 29, 2026. Scope: the 16 previously recovered snapshots. Article text was compared after normalizing presentation; covers, body images and platform previews were checked separately. Per-article completeness files record exact source URLs and local media hashes.\n\n| Article | Result | Body images | Stored body/cover references |\n| --- | --- | --- | --- |\n" . implode("\n", $rows) . "\n\nThe Habr portrait article is currently a draft visible to its author; its earlier published text remains archived. Other public captures were available without article editing.\n\nNo direct file attachments or embedded media elements were observed in the extension-based/HTML-element inventory. One article links to an external VK video, and several link to code repositories or online tools. Those services and repositories are listed on the article pages; their contents are not mirrored. Extensionless download endpoints and whole-site crawling are outside this check. Platform controls, avatars, comments and advertisements are excluded.\n\nEarlier source captures and private workflow data remain ignored. Public text/media are in Git; a verified push is needed for a remote copy.\n");
}
