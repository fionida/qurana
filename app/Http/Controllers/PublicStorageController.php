<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicStorageController extends Controller
{
    /**
     * Serve files from storage/app/public when public/storage symlink is missing
     * (common on shared hosting where storage:link cannot run).
     */
    public function __invoke(string $path): BinaryFileResponse
    {
        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');

        if ($path === '' || str_contains($path, '..')) {
            abort(404);
        }

        $base = realpath(storage_path('app/public'));
        if ($base === false) {
            abort(404);
        }

        $candidate = $base.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $path);
        $full = realpath($candidate);

        if ($full === false || ! str_starts_with($full, $base) || ! is_file($full)) {
            abort(404);
        }

        return response()->file($full, [
            'Cache-Control' => 'public, max-age=604800, immutable',
        ]);
    }
}
