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
    if (($record['public_text_policy'] ?? '') !== 'full_mirror') {
        $externalOnly++;
        continue;
    }
    $mirrors++;
    foreach (['archive_path', 'cover_path'] as $field) {
        $relative = $record[$field] ?? '';
        $resolved = $relative === '' ? false : realpath($root . '/' . $relative);
        if (!$resolved || !str_starts_with($resolved, $root . '/') || !is_file($resolved) || filesize($resolved) === 0) {
            $errors[] = "Missing or invalid {$field} for {$id}";
        }
    }
    if (empty($record['canonical_url']) || empty($record['verified_at'])) {
        $errors[] = "Missing provenance for {$id}";
    }
    $archive = $record['archive_path'] ?? '';
    if ($archive !== '' && is_file($root . '/' . $archive)) {
        $text = file_get_contents($root . '/' . $archive);
        if (!str_contains($text, '# ') || mb_strlen($text) < 500) {
            $errors[] = "Article body appears empty or incomplete for {$id}";
        }
    }
}

foreach ($errors as $error) {
    fwrite(STDERR, $error . PHP_EOL);
}
echo "Full public archives: {$mirrors}; published records without a declared public full-text archive: {$externalOnly}.\n";
if ($externalOnly > 0) {
    echo "External-only records remain a preservation backlog; this check does not claim they are backed up.\n";
}
exit($errors === [] ? 0 : 1);
