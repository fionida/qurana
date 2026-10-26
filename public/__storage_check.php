<?php

declare(strict_types=1);

// Temporary diagnostic — remove after use.
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

try {
    require $root.'/vendor/autoload.php';
    $app = require $root.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    echo 'db_santris='.Illuminate\Support\Facades\DB::table('santris')->count()."\n";
    echo 'db_with_pas_foto='.Illuminate\Support\Facades\DB::table('santris')->whereNotNull('pas_foto')->where('pas_foto', '!=', '')->count()."\n";

    $paths = Illuminate\Support\Facades\DB::table('santris')
        ->whereNotNull('pas_foto')
        ->where('pas_foto', '!=', '')
        ->orderByDesc('id')
        ->limit(5)
        ->pluck('pas_foto');

    foreach ($paths as $i => $p) {
        $rel = ltrim(str_replace('\\', '/', (string) $p), '/');
        $abs = $public.'/'.$rel;
        echo 'db_path_'.$i.'='.$rel."\n";
        echo 'db_file_'.$i.'='.(is_file($abs) ? 'yes' : 'MISSING')."\n";
        if (is_file($abs)) {
            echo 'sample_url=/storage/'.$rel."\n";
        }
    }

    echo 'filesystem_disk='.config('filesystems.default')."\n";
} catch (Throwable $e) {
    echo 'DB_ERROR='.$e->getMessage()."\n";
}

echo 'DONE_OK'."\n";
