<?php

namespace App\Support;

use App\Models\Gelombang;

class CertificateTemplatePreview
{
    /** Maks. ukuran berkas untuk inline base64 di halaman admin (5 MB). */
    private const INLINE_MAX_BYTES = 5_242_880;

    /**
     * Sumber gambar untuk editor layout: data URI jika memungkinkan, else URL path relatif.
     */
    public static function srcForLayoutEditor(Gelombang $gelombang): ?string
    {
        $path = $gelombang->templateHalaman1Path();

        if (! $path) {
            return null;
        }

        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf') {
            return null;
        }

        $size = filesize($path);

        if ($size !== false && $size <= self::INLINE_MAX_BYTES) {
            $dataUri = self::dataUriFromPath($path);

            if ($dataUri !== null) {
                return $dataUri;
            }
        }

        return route('admin.gelombangs.template-depan', $gelombang, absolute: false);
    }

    /**
     * URL path relatif ke public storage (tanpa APP_URL), jika berkas ada.
     */
    public static function publicRelativeUrl(?string $storageRelative): ?string
    {
        if (! $storageRelative) {
            return null;
        }

        $absolute = self::resolveAbsolutePath($storageRelative);

        return $absolute ? '/storage/'.ltrim(str_replace('\\', '/', $storageRelative), '/') : null;
    }

    public static function resolveAbsolutePath(?string $storageRelative): ?string
    {
        if (! $storageRelative) {
            return null;
        }

        $relative = str_replace('\\', '/', $storageRelative);

        foreach ([
            storage_path('app/public/'.$relative),
            public_path('storage/'.$relative),
        ] as $absolute) {
            if (is_file($absolute)) {
                return $absolute;
            }
        }

        return null;
    }

    public static function dataUriFromPath(string $absolutePath): ?string
    {
        if (! is_readable($absolutePath)) {
            return null;
        }

        $ext = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
        $mime = match ($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            default => mime_content_type($absolutePath) ?: null,
        };

        if (! $mime || ! str_starts_with($mime, 'image/')) {
            return null;
        }

        $contents = file_get_contents($absolutePath);

        if ($contents === false) {
            return null;
        }

        return 'data:'.$mime.';base64,'.base64_encode($contents);
    }
}
