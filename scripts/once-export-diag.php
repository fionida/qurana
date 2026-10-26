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

echo 'PHP='.PHP_VERSION."\n";
echo 'zip_ext='.(extension_loaded('zip') ? 'yes' : 'no')."\n";
echo 'xml_ext='.(extension_loaded('xml') ? 'yes' : 'no')."\n";
echo 'mbstring='.(extension_loaded('mbstring') ? 'yes' : 'no')."\n";
echo 'gd='.(extension_loaded('gd') ? 'yes' : 'no')."\n";
echo 'ZipArchive='.(class_exists('ZipArchive') ? 'yes' : 'no')."\n";
echo 'vendor_autoload='.(is_file($root.'/vendor/autoload.php') ? 'yes' : 'no')."\n";
echo 'phpspreadsheet_dir='.(is_dir($root.'/vendor/phpoffice/phpspreadsheet') ? 'yes' : 'no')."\n";

if (is_file($root.'/vendor/autoload.php')) {
    require $root.'/vendor/autoload.php';
    echo 'Spreadsheet_class='.(class_exists(\PhpOffice\PhpSpreadsheet\Spreadsheet::class) ? 'yes' : 'no')."\n";
    echo 'Xlsx_class='.(class_exists(\PhpOffice\PhpSpreadsheet\Writer\Xlsx::class) ? 'yes' : 'no')."\n";

    try {
        $app = require $root.'/bootstrap/app.php';
        $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet;
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'ok');
        $tmp = tempnam(sys_get_temp_dir(), 'xlsx');
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save($tmp);
        echo 'xlsx_write='.(is_file($tmp) && filesize($tmp) > 0 ? 'yes' : 'no')."\n";
        @unlink($tmp);
        echo 'DIAG_OK'."\n";
    } catch (Throwable $e) {
        echo 'ERROR='.$e->getMessage()."\n";
        echo $e->getFile().':'.$e->getLine()."\n";
    }
} else {
    echo 'ERROR=vendor missing'."\n";
}

@unlink(__FILE__);
