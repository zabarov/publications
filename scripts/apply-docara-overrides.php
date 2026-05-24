<?php

$root = dirname(__DIR__);
$indexPath = $root . '/source/index.blade.md';

if (!is_file($indexPath)) {
    fwrite(STDERR, "Missing source/index.blade.md\n");
    exit(1);
}

$content = file_get_contents($indexPath);
$content = preg_replace('/^title:\s*.*$/m', 'title: Home', $content, 1);
$content = preg_replace('/^description:\s*.*$/m', 'description: Redirecting to the English publication site.', $content, 1);

file_put_contents($indexPath, $content);
echo "Applied Docara publication overrides.\n";
