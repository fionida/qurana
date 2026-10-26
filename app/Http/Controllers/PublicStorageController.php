<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PublicStorageController extends Controller
{
    /**
     * Serve files from public storage roots when Apache cannot find the file
     * (e.g. legacy files still under storage/app/public, or missing symlink).
     */
    public function __invoke(string $path): BinaryFileResponse
    {
        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');

        if ($path === '' || str_contains($path, '..')) {
            abort(404);
        }

        $bases = array_values(array_filter([
            realpath(public_path('storage')),
            realpath(storage_path('app/public')),
        ]));

        foreach ($bases as $base) {
            $candidate = $base.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $path);
            $full = realpath($candidate);

            if ($full !== false && str_starts_with($full, $base) && is_file($full)) {
                return response()->file($full, [
                    'Cache-Control' => 'public, max-age=604800, immutable',
                ]);
            }
        }

        abort(404);
    }
}
