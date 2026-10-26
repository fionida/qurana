<?php

declare(strict_types=1);

$expected = 'qurana-storage-check-2026';
$given = (string) ($_GET['t'] ?? '');
if (! hash_equals($expected, $given)) {
    http_response_code(404);
    echo 'Not found';
    exit;
}

header('Content-Type: text/plain; charset=utf-8');

$root = dirname(__DIR__);
chdir($root);

$candidates = [
    'storage/app/public',
    'storage/app/private',
    'storage/app',
    'public/storage',
    'storage/app/public/pas-foto',
    'storage/app/pas-foto',
    'storage/app/private/pas-foto',
];

foreach ($candidates as $rel) {
    $abs = $root.'/'.$rel;
    echo $rel.'='.(is_dir($abs) ? 'dir' : (is_file($abs) ? 'file' : 'no'));
    if (is_dir($abs)) {
        $files = array_values(array_filter(scandir($abs) ?: [], fn ($f) => $f !== '.' && $f !== '..'));
        echo ' count='.count($files);
        if ($files !== []) {
            echo ' sample='.$files[0];
        }
    }
    echo "\n";
}

// Recursive find any jpg/png under storage/app (max depth)
$found = [];
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root.'/storage/app', FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::SELF_FIRST
);
foreach ($iterator as $file) {
    if (! $file->isFile()) {
        continue;
    }
    $ext = strtolower($file->getExtension());
    if (! in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
        continue;
    }
    $found[] = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
    if (count($found) >= 10) {
        break;
    }
}
echo 'found_images='.count($found)."\n";
foreach ($found as $i => $p) {
    echo 'img_'.$i.'='.$p."\n";
}

echo 'writable_public='.(is_writable($root.'/storage/app/public') ? 'yes' : 'no')."\n";

echo 'DONE_OK'."\n";
