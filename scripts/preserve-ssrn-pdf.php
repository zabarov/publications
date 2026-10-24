<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

if ($argc !== 3 || !preg_match('/^\d+$/', $argv[1])) {
    fwrite(STDERR, "Usage: php scripts/preserve-ssrn-pdf.php <abstract-id> <downloaded-pdf>\n");
    exit(1);
}
$root = dirname(__DIR__);
$id = $argv[1];
$input = realpath($argv[2]);
if (!$input || file_get_contents($input, false, null, 0, 5) !== '%PDF-') {
    throw new RuntimeException('Input must be an existing downloaded PDF.');
}
$records = array_column(Yaml::parseFile("{$root}/data/publications.yml")['publications'], null, 'id');
$record = $records["ssrn-{$id}"] ?? null;
if (!$record || ($record['status'] ?? '') !== 'published') {
    throw new RuntimeException('A published SSRN registry record is required.');
}
$capturePath = "{$root}/source/articles/ssrn/{$id}/page.json";
$capture = json_decode(file_get_contents($capturePath), true, 512, JSON_THROW_ON_ERROR);
if ($capture['url'] !== $record['public_url'] || trim($capture['title']) !== $record['title']) {
    throw new RuntimeException('Captured SSRN page does not match the registry.');
}
$text = [];
$code = 0;
exec('pdftotext -layout ' . escapeshellarg($input) . ' -', $text, $code);
if ($code !== 0) {
    throw new RuntimeException('PDF text extraction failed.');
}
$plain = implode("\n", $text);
$normalize = fn (string $value): string => mb_strtolower(preg_replace('/\s+/u', '', $value));
if (!str_contains($normalize($plain), $normalize($record['title'])) || !str_contains($plain, 'Rim Zabarov')) {
    throw new RuntimeException('PDF title or author does not match the SSRN record.');
}
$pages = explode("\f", $plain);
while ($pages !== [] && trim(end($pages)) === '') {
    array_pop($pages);
}
if (count($pages) !== (int) $record['page_count'] || mb_strlen($plain) < 3000) {
    throw new RuntimeException('PDF page count or extracted body failed validation.');
}
$package = "publications/articles/ssrn/{$id}";
$asset = "assets/publications/ssrn-{$id}/paper.pdf";
foreach (["{$root}/{$package}", dirname("{$root}/{$asset}")] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}
if (is_file("{$root}/{$asset}") && hash_file('sha256', "{$root}/{$asset}") !== hash_file('sha256', $input)) {
    throw new RuntimeException('Refusing to replace an existing different PDF; preserve a version first.');
}
copy($input, "{$root}/{$asset}");
$markdown = "# {$record['title']}\n\n[Read the published PDF](../../../../{$asset}) | [SSRN record]({$record['public_url']})\n\n";
$markdown .= "Text extracted from the downloaded published PDF. Page boundaries and preformatted layout are retained to protect tables, formulas and line breaks. The PDF is authoritative for layout and typography.\n\n";
foreach ($pages as $index => $page) {
    $page = preg_replace('/[ \t]+$/m', '', $page);
    $fence = '```';
    while (str_contains($page, $fence)) {
        $fence .= '`';
    }
    $markdown .= '## Page ' . ($index + 1) . "\n\n{$fence}text\n" . trim($page, "\r\n") . "\n{$fence}\n\n";
}
$archivePath = "{$root}/{$package}/article.en.md";
if (is_file($archivePath) && file_get_contents($archivePath) !== $markdown) {
    throw new RuntimeException('Refusing to replace existing different Markdown.');
}
file_put_contents($archivePath, $markdown);
$manifest = [
    'publication_id' => "ssrn-{$id}",
    'canonical_url' => $record['public_url'],
    'source_kind' => 'published_ssrn_pdf_browser_download',
    'source_captured_at' => $capture['captured_at'],
    'source_url' => "https://papers.ssrn.com/sol3/Delivery.cfm/{$id}.pdf?abstractid={$id}&mirid=1",
    'page_count' => count($pages),
    'pdf_sha256' => hash_file('sha256', $input),
    'text_sha256' => hash('sha256', $markdown),
    'extraction' => 'Poppler pdftotext -layout; page boundaries preserved; trailing whitespace normalized.',
    'assets' => [['status' => 'saved', 'path' => $asset, 'sha256' => hash_file('sha256', $input)]],
];
file_put_contents("{$root}/{$package}/preservation.json", json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
$readme = "# {$record['title']}\n\nAuthor: Rim Zabarov. Language: English. Posted on SSRN: {$record['published_at']}.\n\n[Read the published PDF](../../../../{$asset}) | [Read extracted text](article.en.md) | [Original SSRN record]({$record['public_url']})\n\n";
$readme .= "This {$record['page_count']}-page preprint was downloaded directly from the public SSRN page on {$record['verified_at']} and preserved unchanged. Its text was also extracted into Markdown, retaining page boundaries and table layout.\n\nThis is a preprint, not a peer-reviewed journal publication. Scientific limitations and declarations remain in the archived paper. Copyright and reuse terms remain with the author and any separately attributed material. Archiving does not grant a new reuse license.\n\n[Preservation details](preservation.json)\n";
file_put_contents("{$root}/{$package}/README.md", $readme);
echo "Preserved SSRN {$id}: " . count($pages) . " pages, original PDF and extracted Markdown.\n";
