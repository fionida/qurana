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
chdir($root);

$public = $root.'/storage/app/public';
echo 'storage_app_public='.(is_dir($public) ? 'yes' : 'no')."\n";
echo 'public_storage='.(file_exists($root.'/public/storage') ? 'yes' : 'no')."\n";
echo 'public_storage_link='.(is_link($root.'/public/storage') ? 'yes' : 'no')."\n";

if (is_dir($public)) {
    $entries = array_values(array_filter(scandir($public) ?: [], fn ($f) => $f !== '.' && $f !== '..'));
    echo 'public_entries='.implode(',', $entries)."\n";
}

$pas = $public.'/pas-foto';
echo 'pas_foto_dir='.(is_dir($pas) ? 'yes' : 'no')."\n";

$sample = null;
if (is_dir($pas)) {
    $files = array_values(array_filter(scandir($pas) ?: [], fn ($f) => $f !== '.' && $f !== '..'));
    echo 'pas_foto_count='.count($files)."\n";
    $sample = $files[0] ?? null;
    if ($sample) {
        echo 'sample='.$sample."\n";
        echo 'sample_url=/storage/pas-foto/'.$sample."\n";
    }
}

try {
    require $root.'/vendor/autoload.php';
    $app = require $root.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    $total = Illuminate\Support\Facades\DB::table('santris')->count();
    $withFoto = Illuminate\Support\Facades\DB::table('santris')
        ->whereNotNull('pas_foto')
        ->where('pas_foto', '!=', '')
        ->count();
    echo "db_santris={$total}\n";
    echo "db_with_pas_foto={$withFoto}\n";

    $paths = Illuminate\Support\Facades\DB::table('santris')
        ->whereNotNull('pas_foto')
        ->where('pas_foto', '!=', '')
        ->orderByDesc('id')
        ->limit(5)
        ->pluck('pas_foto');

    foreach ($paths as $i => $p) {
        $abs = $public.'/'.ltrim(str_replace('\\', '/', (string) $p), '/');
        echo 'db_path_'.$i.'='.$p."\n";
        echo 'db_file_'.$i.'='.(is_file($abs) ? 'yes' : 'no')."\n";
        if ($sample === null && is_file($abs)) {
            $sample = (string) $p;
            echo 'sample_url=/storage/'.$sample."\n";
        }
    }
} catch (Throwable $e) {
    echo 'DB_ERROR='.$e->getMessage()."\n";
}

echo 'DONE_OK'."\n";
@unlink(__FILE__);
