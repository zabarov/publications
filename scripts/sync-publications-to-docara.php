<?php

$root = dirname(__DIR__);
$publicationsRoot = $root . '/publications';
$docaraRoot = $root . '/source/docs/en';
$docaraPublicationsRoot = $docaraRoot . '/publications';

if (!is_dir($publicationsRoot)) {
    fwrite(STDERR, "Missing publications directory\n");
    exit(1);
}

function ensureDir(string $path): void
{
    if (!is_dir($path) && !mkdir($path, 0775, true) && !is_dir($path)) {
        throw new RuntimeException("Cannot create directory: {$path}");
    }
}

function removeDir(string $path): void
{
    if (!is_dir($path)) {
        return;
    }

    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($items as $item) {
        $item->isDir() ? rmdir($item->getPathname()) : unlink($item->getPathname());
    }

    rmdir($path);
}

function titleFromMarkdown(string $markdown, string $fallback): string
{
    if (preg_match('/^#\s+(.+)$/m', $markdown, $matches)) {
        return trim($matches[1]);
    }

    return $fallback;
}

function humanTitleFromPath(string $path): string
{
    $base = basename($path);
    $title = str_replace(['-', '_'], ' ', $base);
    return mb_convert_case($title, MB_CASE_TITLE, 'UTF-8');
}

function wrapDocaraPage(string $markdown, string $title): string
{
    if (str_starts_with(ltrim($markdown), '---')) {
        return $markdown;
    }

    $description = preg_replace('/\s+/', ' ', trim(strip_tags($title)));
    $yamlTitle = json_encode($title, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $yamlDescription = json_encode($description, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    return "---\n"
        . "extends: _core._layouts.documentation\n"
        . "section: content\n"
        . "title: " . $yamlTitle . "\n"
        . "description: " . $yamlDescription . "\n"
        . "---\n\n"
        . $markdown;
}

function writeSettings(string $dir, string $title): void
{
    $php = "<?php\n\nreturn [\n"
        . "    'title' => " . var_export($title, true) . ",\n"
        . "    'showInMenu' => true,\n"
        . "];\n";

    file_put_contents($dir . '/.settings.php', $php);
}

removeDir($docaraRoot);
ensureDir($docaraRoot);

file_put_contents($docaraRoot . '/index.md', "---\n"
    . "extends: _core._layouts.documentation\n"
    . "section: content\n"
    . "title: Rim Zabarov Publications\n"
    . "description: Publications, working papers, essays, research outputs and data/code packages by Rim Zabarov.\n"
    . "---\n\n"
    . "# Rim Zabarov Publications\n\n"
    . "This site collects public publications, working papers, essays, research outputs and release packages by Rim Zabarov.\n\n"
    . "## Sections\n\n"
    . "- [Publications](publications/)\n");

writeSettings($docaraRoot, 'Rim Zabarov Publications');

removeDir($docaraPublicationsRoot);

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($publicationsRoot, FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'md') {
        continue;
    }

    $sourcePath = $file->getPathname();
    $relativeDir = trim(substr(dirname($sourcePath), strlen($publicationsRoot)), DIRECTORY_SEPARATOR);
    $targetDir = $relativeDir === ''
        ? $docaraPublicationsRoot
        : $docaraPublicationsRoot . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $relativeDir);

    ensureDir($targetDir);

    $markdown = file_get_contents($sourcePath);
    $markdown = preg_replace('~\]\((?:\.\./)+assets/publications/~', '](/assets/publications/', $markdown);
    $markdown = preg_replace('~\]\(([^)\s]*)README\.md\)~', ']($1)', $markdown);
    $markdown = preg_replace('~\]\((?!https?://)([^)\s]+)\.md\)~', ']($1/)', $markdown);
    $manifestUrl = 'https://github.com/zabarov/publications/blob/main/publications/'
        . ($relativeDir === '' ? '' : str_replace(DIRECTORY_SEPARATOR, '/', $relativeDir) . '/')
        . 'preservation.json';
    $markdown = str_replace('](preservation.json)', '](' . $manifestUrl . ')', $markdown);
    $isIndex = $file->getFilename() === 'README.md';
    if (!$isIndex) {
        // Docara gives non-index Markdown its own directory URL.
        $markdown = preg_replace('~\]\((?![a-z][a-z0-9+.-]*:|/|\#)([^)\s]+)\)~i', '](../$1)', $markdown);
    }
    $fallbackTitle = $relativeDir === '' ? 'Publications' : humanTitleFromPath($relativeDir);
    $title = titleFromMarkdown($markdown, $fallbackTitle);

    $targetName = $isIndex ? 'index.md' : $file->getFilename();
    file_put_contents($targetDir . '/' . $targetName, wrapDocaraPage($markdown, $title));
    if ($isIndex) {
        writeSettings($targetDir, $title);
    }
}

$assetRoot = $root . '/assets/publications';
if (is_dir($assetRoot)) {
    $assets = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($assetRoot, FilesystemIterator::SKIP_DOTS));
    foreach ($assets as $asset) {
        if (!$asset->isFile() || !in_array(strtolower($asset->getExtension()), ['png', 'jpg', 'jpeg', 'webp', 'gif', 'svg'], true)) {
            continue;
        }
        $relative = substr($asset->getPathname(), strlen($assetRoot) + 1);
        $target = $root . '/source/assets/publications/' . $relative;
        ensureDir(dirname($target));
        if (!copy($asset->getPathname(), $target)) {
            throw new RuntimeException("Cannot copy publication asset: {$relative}");
        }
    }
}

echo "Synced publications to Docara source.\n";
