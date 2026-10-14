<?php

declare(strict_types=1);

$expected = '___TOKEN___';
$given = (string) ($_GET['t'] ?? '');
if (!hash_equals($expected, $given)) {
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

function run_shell(string $cmd): int
{
    out('> '.$cmd);
    $output = [];
    $code = 1;
    if (function_exists('exec')) {
        exec($cmd.' 2>&1', $output, $code);
        foreach ($output as $line) {
            out($line);
        }
    } else {
        out('exec() disabled; skip shell command');
    }

    return (int) $code;
}

try {
    out('ROOT='.$root);
    out('PHP='.PHP_VERSION);

    if (! is_dir($root.'/vendor')) {
        out('vendor missing; trying composer install...');
    }

    $composerDone = false;
    foreach ([
        'composer',
        '/opt/cpanel/composer/bin/composer',
        'php '.$root.'/composer.phar',
    ] as $bin) {
        if ($bin === 'php '.$root.'/composer.phar' && ! is_file($root.'/composer.phar')) {
            out('Downloading composer.phar...');
            $installer = @file_get_contents('https://getcomposer.org/installer');
            if ($installer === false) {
                out('Failed to download composer installer');
                continue;
            }
            file_put_contents($root.'/composer-setup.php', $installer);
            run_shell('php composer-setup.php --install-dir='.escapeshellarg($root).' --filename=composer.phar');
            @unlink($root.'/composer-setup.php');
        }
        $code = run_shell($bin.' install --no-dev --optimize-autoloader --no-interaction');
        if ($code === 0) {
            $composerDone = true;
            break;
        }
    }
    out($composerDone ? 'composer: OK' : 'composer: skipped/failed (continuing if vendor exists)');

    if (! is_file($root.'/vendor/autoload.php')) {
        throw new RuntimeException('vendor/autoload.php missing');
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
