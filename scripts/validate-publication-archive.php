<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$root = dirname(__DIR__);
$records = Yaml::parseFile($root . '/data/publications.yml')['publications'];
$venues = array_column(Yaml::parseFile($root . '/data/publication-venues.yml')['venues'], 'id');
$ids = [];
$mirrors = 0;
$externalOnly = 0;
$recovered = 0;
$errors = [];

foreach ($records as $record) {
    $id = $record['id'];
    if (isset($ids[$id])) {
        $errors[] = "Duplicate publication id: {$id}";
    }
    $ids[$id] = true;
    if (($record['status'] ?? '') !== 'published') {
        continue;
    }
    if (!in_array($record['venue'], $venues, true)) {
        $errors[] = "Unknown venue for {$id}";
    }
    $policy = $record['public_text_policy'] ?? '';
    if (!in_array($policy, ['full_mirror', 'recovered_snapshot'], true)) {
        $externalOnly++;
        continue;
    }
    $policy === 'full_mirror' ? $mirrors++ : $recovered++;
    $requiredPaths = $policy === 'full_mirror' ? ['archive_path', 'cover_path'] : ['archive_path'];
    foreach ($requiredPaths as $field) {
        $relative = $record[$field] ?? '';
        $resolved = $relative === '' ? false : realpath($root . '/' . $relative);
        if (!$resolved || !str_starts_with($resolved, $root . '/') || !is_file($resolved) || filesize($resolved) === 0) {
            $errors[] = "Missing or invalid {$field} for {$id}";
        }
    }
    if (empty($record['canonical_url']) || empty($record[$policy === 'full_mirror' ? 'verified_at' : 'preserved_at'])) {
        $errors[] = "Missing provenance for {$id}";
    }
    $archive = $record['archive_path'] ?? '';
    if ($archive !== '' && is_file($root . '/' . $archive)) {
        $text = file_get_contents($root . '/' . $archive);
        if (!str_contains($text, '# ') || mb_strlen($text) < 500) {
            $errors[] = "Article body appears empty or incomplete for {$id}";
        }
        if ($policy === 'recovered_snapshot') {
            $manifestPath = dirname($root . '/' . $archive) . '/preservation.json';
            $manifest = is_file($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : null;
            if (!$manifest || ($manifest['publication_id'] ?? '') !== $id || ($manifest['text_sha256'] ?? '') !== hash('sha256', $text)) {
                $errors[] = "Missing or mismatched preservation manifest for {$id}";
            }
            foreach ($manifest['assets'] ?? [] as $asset) {
                if (($asset['status'] ?? '') !== 'saved') {
                    continue;
                }
                $path = realpath($root . '/' . $asset['path']);
                if (!$path || !str_starts_with($path, $root . '/assets/publications/') || hash_file('sha256', $path) !== $asset['sha256']) {
                    $errors[] = "Missing or changed preserved image for {$id}";
                }
            }
        }
    }
}

foreach ($errors as $error) {
    fwrite(STDERR, $error . PHP_EOL);
}
echo "Full public archives: {$mirrors}; recovered text snapshots: {$recovered}; published records without public text: {$externalOnly}.\n";
if ($externalOnly > 0) {
    echo "External-only records remain a preservation backlog; this check does not claim they are backed up.\n";
}
exit($errors === [] ? 0 : 1);
