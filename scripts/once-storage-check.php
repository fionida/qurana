<?php

declare(strict_types=1);

$expected = '___TOKEN___';
$given = (string) ($_GET['t'] ?? '');
if (! hash_equals($expected, $given)) {
    http_response_code(404);
    echo 'Not found';
    exit;
}

header('Content-Type: text/plain; charset=utf-8');

$root = dirname(__DIR__);
$public = $root.'/storage/app/public';
echo 'storage_app_public='.(is_dir($public) ? 'yes' : 'no')."\n";
echo 'public_storage='.(file_exists($root.'/public/storage') ? 'yes' : 'no')."\n";
echo 'public_storage_link='.(is_link($root.'/public/storage') ? 'yes' : 'no')."\n";

$pas = $public.'/pas-foto';
echo 'pas_foto_dir='.(is_dir($pas) ? 'yes' : 'no')."\n";

$sample = null;
if (is_dir($pas)) {
    $files = array_values(array_filter(scandir($pas) ?: [], fn ($f) => $f !== '.' && $f !== '..'));
    echo 'pas_foto_count='.count($files)."\n";
    $sample = $files[0] ?? null;
    if ($sample) {
        echo 'sample='.$sample."\n";
        echo 'sample_bytes='.filesize($pas.'/'.$sample)."\n";
        echo 'sample_url=/storage/pas-foto/'.$sample."\n";
    }
}

echo 'DONE_OK'."\n";
@unlink(__FILE__);
