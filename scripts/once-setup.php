<?php

declare(strict_types=1);

$expected = '___TOKEN___';
$given = (string) ($_GET['t'] ?? '');
if (! hash_equals($expected, $given)) {
    http_response_code(404);
    echo 'Not found';
    exit;
}

set_time_limit(0);
ignore_user_abort(true);
header('Content-Type: text/plain; charset=utf-8');

$root = dirname(__DIR__);
chdir($root);

function out(string $s): void
{
    echo $s."\n";
    if (function_exists('ob_flush')) {
        @ob_flush();
    }
    if (function_exists('flush')) {
        @flush();
    }
}

try {
    out('ROOT='.$root);
    out('PHP='.PHP_VERSION);

    // Shared hosting often disables exec()/escapeshellarg(); run Artisan in-process.
    out('composer: skipped (no shell); using existing vendor if present');

    if (! is_file($root.'/vendor/autoload.php')) {
        throw new RuntimeException('vendor/autoload.php missing — upload vendor or enable shell for composer');
    }

    require $root.'/vendor/autoload.php';
    /** @var \Illuminate\Foundation\Application $app */
    $app = require $root.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    foreach ([
        ['migrate', ['--force' => true]],
        ['config:clear', []],
        ['route:clear', []],
        ['view:clear', []],
        ['cache:clear', []],
    ] as [$cmd, $opts]) {
        out('artisan '.$cmd);
        $code = $kernel->call($cmd, $opts);
        out(trim((string) $kernel->output()));
        out('exit='.$code);
        if ($cmd === 'migrate' && $code !== 0) {
            throw new RuntimeException('migrate failed with exit '.$code);
        }
    }

    out('DONE_OK');
} catch (Throwable $e) {
    http_response_code(500);
    out('ERROR: '.$e->getMessage());
    out($e->getFile().':'.$e->getLine());
} finally {
    @unlink(__FILE__);
}
