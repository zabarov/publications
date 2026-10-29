<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$root = dirname(__DIR__);
$registryPath = $root . '/data/publications.yml';
$registry = Yaml::parseFile($registryPath);
$originalRecords = array_column($registry['publications'], null, 'id');
$today = gmdate('Y-m-d');
$rows = [];
$only = null;
foreach ($argv as $argument) {
    if (str_starts_with($argument, '--only=')) {
        $only = substr($argument, 7);
    }
}

foreach ($registry['publications'] as &$record) {
    if (($record['status'] ?? '') !== 'published') {
        continue;
    }
    $id = $record['id'];
    $venue = $record['venue'];
    $shortId = substr($id, strlen($venue) + 1);
    $source = "{$root}/source/articles/{$venue}/{$shortId}/README.md";
    $refresh = in_array('--refresh-generated', $argv, true)
        && ($record['public_text_policy'] ?? '') === 'recovered_snapshot'
        && ($only === null || $id === $only);
    if ((!isset($record['archive_path']) || $refresh) && is_file($source)) {
        if ($refresh) {
            $manifestPath = dirname("{$root}/{$record['archive_path']}") . '/preservation.json';
            $previous = json_decode(file_get_contents($manifestPath), true, 512, JSON_THROW_ON_ERROR);
            if (hash_file('sha256', "{$root}/{$record['archive_path']}") !== $previous['text_sha256']) {
                throw new RuntimeException("Refusing to overwrite edited archive: {$id}");
            }
        }
        $raw = file_get_contents($source);
        if (!preg_match('/\A---\R(.*?)\R---\R(.*)\z/s', $raw, $parts)) {
            throw new RuntimeException("Missing source metadata: {$id}");
        }
        $meta = Yaml::parse($parts[1]);
        // Remove only the local-storage notice, keeping the author's prose intact.
        $body = preg_replace('/(^# [^\r\n]+\R\s*\R)> Local (?:Markdown copy|ignored recovery copy)[^\r\n]*\R(?:>[^\r\n]*\R)*/', '$1', ltrim($parts[2]), 1);
        $body = trim($body) . "\n";
        $fence = null;
        $lines = explode("\n", $body);
        foreach ($lines as &$line) {
            if (preg_match('/^\s*(`{3,}|~{3,})/', $line, $fenced)) {
                $marker = $fenced[1][0];
                $fence = $fence === $marker ? null : ($fence ?? $marker);
            }
            $line = preg_replace_callback('/[ \t]+$/', fn ($match) => $fence === null && strlen($match[0]) >= 2 ? '\\' : '', $line);
        }
        unset($line);
        $body = implode("\n", $lines);
        if (mb_strlen($body) < 500 || !str_starts_with($body, '# ')) {
            throw new RuntimeException("Unusable article body: {$id}");
        }
        $lang = $record['original_language'] === 'Russian' ? 'ru' : 'en';
        $package = "publications/articles/{$venue}/{$shortId}";
        $dir = "{$root}/{$package}";
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $assets = [];
        $body = preg_replace_callback('/!\[([^\]]*)\]\((https:\/\/[^\s)]+)\)/', function ($match) use ($root, $id, &$assets) {
            $url = $match[2];
            $host = parse_url($url, PHP_URL_HOST);
            if (!in_array($host, ['habrastorage.org', 'miro.medium.com', 'hackernoon.imgix.net'], true)) {
                $assets[] = ['url' => $url, 'status' => 'remote_unreviewed'];
                return $match[0];
            }
            $curl = curl_init($url);
            curl_setopt_array($curl, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20, CURLOPT_CONNECTTIMEOUT => 8]);
            $bytes = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
            curl_close($curl);
            $info = is_string($bytes) ? @getimagesizefromstring($bytes) : false;
            if ($status !== 200 || !$info) {
                $assets[] = ['url' => $url, 'status' => 'download_failed', 'http_status' => $status];
                return $match[0];
            }
            $ext = image_type_to_extension($info[2], false);
            $path = "assets/publications/{$id}/" . substr(hash('sha256', $bytes), 0, 16) . ".{$ext}";
            if (!is_dir(dirname("{$root}/{$path}"))) {
                mkdir(dirname("{$root}/{$path}"), 0755, true);
            }
            file_put_contents("{$root}/{$path}", $bytes);
            $assets[] = ['url' => $url, 'status' => 'saved', 'path' => $path, 'sha256' => hash('sha256', $bytes)];
            return "![{$match[1]}](../../../../{$path})";
        }, $body);
        $archive = "{$package}/article.{$lang}.md";
        file_put_contents("{$root}/{$archive}", $body);
        $provenance = [
            'publication_id' => $id,
            'canonical_url' => $record['canonical_url'] ?? $record['public_url'],
            'preserved_at' => $today,
            'source_kind' => $meta['source_kind'] ?? 'previous_local_published_copy',
            'source_captured_at' => $meta['generated_at'] ?? null,
            'source_sha256' => hash('sha256', $raw),
            'text_sha256' => hash('sha256', $body),
            'format_normalization' => 'Trailing whitespace removed; Markdown hard breaks represented with backslashes.',
            'completeness' => 'Recovered text; original-page and media completeness require review.',
            'assets' => $assets,
        ];
        file_put_contents("{$dir}/preservation.json", json_encode($provenance, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
        $record['public_text_policy'] = 'recovered_snapshot';
        $record['archive_path'] = $archive;
        $record['preserved_at'] = $today;
        $record['preservation_status'] = 'text_recovered_completeness_unverified';
        $record['canonical_url'] ??= $record['public_url'];
        $title = $record['title'];
        $url = $record['public_url'];
        file_put_contents("{$dir}/README.md", "# {$title}\n\n[Original publication]({$url}) | [Read the archived text](article.{$lang}.md)\n\nLanguage: {$record['original_language']}. Author: Rim Zabarov.\n\nRecovered published-text snapshot, preserved {$today}. The prose is retained in its original language. This is a recovery copy; correspondence with the latest online revision and original media completeness remain unverified.\n\nAvailable referenced images are stored locally. Failed or unavailable assets are recorded in [preservation details](preservation.json). Media attribution and rights remain with their original owners; no separate reuse license is granted.\n");
        echo "Recovered {$id}; " . count($assets) . " image references inspected.\n";
    }
    if (isset($record['archive_path'])) {
        $link = '../' . substr($record['archive_path'], strlen('publications/'));
        $state = match ($record['preservation_status'] ?? 'published_text_and_cover_archived') {
            'text_and_media_checked' => 'Text and images checked against source',
            'historical_text_media_checked_author_view' => 'Historical text and images checked; original now an author-visible draft',
            'published_pdf_and_extracted_text_archived' => 'Published PDF and extracted text preserved',
            'published_text_and_cover_archived' => 'Published text and cover preserved',
            default => 'Recovered text; completeness review pending',
        };
        $rows[] = "| [{$record['title']}]({$link}) | {$venue} | {$state} |";
    } else {
        $record['preservation_status'] = 'published_text_missing';
        $rows[] = "| [{$record['title']}]({$record['public_url']}) | {$venue} | Text missing; recovery required |";
    }
}
unset($record);
$registryText = file_get_contents($registryPath);
foreach ($registry['publications'] as $record) {
    $before = $originalRecords[$record['id']];
    foreach (['public_text_policy', 'archive_path', 'preserved_at', 'preservation_status', 'canonical_url'] as $field) {
        if (!isset($record[$field]) || ($before[$field] ?? null) === $record[$field]) {
            continue;
        }
        $idPattern = preg_quote($record['id'], '/');
        $registryText = preg_replace_callback('/(^  - id: "' . $idPattern . '"\R)(.*?)(?=^  - id:|\z)/ms', function ($match) use ($field, $record) {
            $line = '    ' . $field . ': ' . json_encode($record[$field], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n";
            $body = $match[2];
            if (preg_match('/^    ' . $field . ':.*$/m', $body)) {
                $body = preg_replace('/^    ' . $field . ':.*\R/m', $line, $body);
            } else {
                $body = $line . $body;
            }
            return $match[1] . $body;
        }, $registryText);
    }
}
file_put_contents($registryPath, $registryText);
file_put_contents("{$root}/publications/articles/preservation.md", "# Preservation Inventory\n\nUpdated {$today}. Every published registry record is listed. A recovered snapshot preserves the available text; its label does not certify correspondence with the latest publication or a complete set of images. Original publication links are available on each article page.\n\n| Publication | Venue | Preservation state |\n| --- | --- | --- |\n" . implode("\n", $rows) . "\n\nRaw captures and private drafts remain ignored and require a separate private backup. A local commit alone does not provide a remote backup.\n");
foreach (['habr' => 'Habr', 'medium' => 'Medium', 'dev-to' => 'DEV Community', 'hackernoon' => 'HackerNoon', 'ssrn' => 'SSRN'] as $venue => $label) {
    $table = [];
    foreach ($registry['publications'] as $record) {
        if (($record['status'] ?? '') !== 'published' || $record['venue'] !== $venue) {
            continue;
        }
        $link = isset($record['archive_path']) ? substr($record['archive_path'], strlen("publications/articles/{$venue}/")) : $record['public_url'];
        if ($venue === 'ssrn' && isset($record['archive_path'])) {
            $link = dirname($link) . '/README.md';
        }
        $state = isset($record['archive_path']) ? 'Text preserved; see article page for completeness' : 'Text recovery pending';
        $date = isset($record['published_at']) ? substr($record['published_at'], 0, 10) : 'Date unverified';
        $table[] = "| {$date} | [{$record['title']}]({$link}) | {$state} |";
    }
    $dir = "{$root}/publications/articles/{$venue}";
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $heading = $venue === 'ssrn' ? 'SSRN Preprints' : "{$label} Articles";
    $note = $venue === 'ssrn' ? "\nPreprints are not peer-reviewed journal publications. Each paper page links to the unchanged published PDF, extracted Markdown and original SSRN record.\n" : '';
    file_put_contents("{$dir}/README.md", "# {$heading}\n\nPublications by Rim Zabarov. English titles are metadata; archived article text retains its original language. Original links and recovery details appear on each article page.\n\n| Published | Article | Archive |\n| --- | --- | --- |\n" . implode("\n", $table) . "\n" . $note . "\n[Preservation inventory](../preservation.md)\n");
}
