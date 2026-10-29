<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$root = dirname(__DIR__);
$results = [];
foreach (Yaml::parseFile("{$root}/data/publications.yml")['publications'] as $record) {
    if (($record['public_text_policy'] ?? '') !== 'recovered_snapshot') {
        continue;
    }
    $id = $record['id'];
    $dir = "{$root}/source/completeness/{$id}";
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $url = $record['public_url'];
    if ($record['venue'] === 'dev-to') {
        $url = str_replace('https://dev.to/', 'https://dev.to/api/articles/', $url);
    }
    $curl = curl_init($url);
    curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_TIMEOUT => 20, CURLOPT_CONNECTTIMEOUT => 8, CURLOPT_USERAGENT => 'Mozilla/5.0 publication-archive/1.0']);
    $raw = curl_exec($curl);
    $http = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
    $error = curl_error($curl);
    curl_close($curl);
    $item = ['id' => $id, 'url' => $record['public_url'], 'checked_at' => gmdate('c'), 'http_status' => $http];
    try {
        if (!is_string($raw) || $http !== 200) {
            throw new RuntimeException("Fetch failed {$http}: {$error}");
        }
        file_put_contents("{$dir}/raw", $raw);
        $body = null;
        if ($record['venue'] === 'habr') {
            if (!preg_match('/window\.__PINIA_STATE__=(\{.*?\});\s*\(function\(\)/s', $raw, $match)
                && !preg_match('/window\.__PINIA_STATE__=(\{.*?\})<\/script>/s', $raw, $match)) {
                throw new RuntimeException('Article state missing');
            }
            $state = json_decode($match[1], true, 512, JSON_THROW_ON_ERROR);
            $article = $state['articlesList']['articlesList'][substr($id, 5)] ?? null;
            if (!$article || empty($article['textHtml'])) {
                throw new RuntimeException('Article body missing');
            }
            if (($article['author']['alias'] ?? '') !== 'zabarov') {
                throw new RuntimeException('Wrong Habr author');
            }
            $body = $article['textHtml'];
            $item['title'] = html_entity_decode(strip_tags($article['titleHtml']));
            $item['cover_url'] = $article['leadData']['imageUrl'] ?? null;
            $item['visibility'] = $article['status'] ?? 'unknown';
            if (!empty($article['leadData']['textHtml'])) {
                file_put_contents("{$dir}/excerpt.html", $article['leadData']['textHtml']);
            }
        } elseif ($record['venue'] === 'dev-to') {
            $article = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
            if (($article['user']['username'] ?? '') !== 'zabarov') {
                throw new RuntimeException('Wrong DEV author');
            }
            $body = $article['body_html'];
            $item['title'] = $article['title'];
            $item['cover_url'] = $article['cover_image'] ?? null;
        } elseif ($record['venue'] === 'hackernoon') {
            $dom = new DOMDocument();
            @$dom->loadHTML($raw, LIBXML_NONET);
            $node = (new DOMXPath($dom))->query('//script[@id="__NEXT_DATA__"]')->item(0);
            if (!$node) {
                throw new RuntimeException('HackerNoon article state missing');
            }
            $article = json_decode($node->textContent, true, 512, JSON_THROW_ON_ERROR)['props']['pageProps']['data'];
            if (($article['profile']['handle'] ?? '') !== 'zabarov') {
                throw new RuntimeException('Wrong HackerNoon author');
            }
            $body = $article['parsed'];
            $item['title'] = $article['title'];
            $item['cover_url'] = $article['mainImage'] ?? null;
        }
        if ($body === null) {
            throw new RuntimeException('Browser capture required for this venue');
        }
        file_put_contents("{$dir}/body.html", $body);
        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8"><html><body>' . $body . '</body></html>', LIBXML_NONET);
        $xpath = new DOMXPath($dom);
        $item['images'] = [];
        foreach ($xpath->query('//img') as $image) {
            $item['images'][] = ['url' => $image->getAttribute('src'), 'alt' => $image->getAttribute('alt')];
        }
        $item['embeds'] = [];
        foreach ($xpath->query('//iframe|//video|//audio|//source|//object|//embed') as $embed) {
            $item['embeds'][] = ['tag' => $embed->tagName, 'url' => $embed->getAttribute('src') ?: $embed->getAttribute('data')];
        }
        $item['attachments'] = [];
        foreach ($xpath->query('//a[@href]') as $link) {
            $href = $link->getAttribute('href');
            if (preg_match('/\.(pdf|zip|tar|gz|docx?|xlsx?|csv|json|txt|mp[34]|wav)(?:[?#]|$)/i', $href)) {
                $item['attachments'][] = ['url' => $href, 'label' => $link->textContent];
            }
        }
        $item['body_sha256'] = hash('sha256', $body);
        $item['status'] = 'captured';
    } catch (Throwable $e) {
        $item['status'] = 'unavailable';
        $item['reason'] = $e->getMessage();
    }
    $results[] = $item;
    file_put_contents("{$dir}/inventory.json", json_encode($item, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
    echo "{$id}: {$item['status']}; images=" . count($item['images'] ?? []) . '; attachments=' . count($item['attachments'] ?? []) . '; embeds=' . count($item['embeds'] ?? []) . "\n";
}
file_put_contents("{$root}/source/completeness/inventory.json", json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n");
