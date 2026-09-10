<?php

namespace App\Support;

final class SertifikatPageLayout
{
    /**
     * @return array{width_mm: float|int, height_mm: float|int, orientation: string, orient_css: string}
     */
    public static function fromConfig(): array
    {
        $page = config('sertifikat_layout.page');
        $orientation = $page['orientation'] ?? 'landscape';

        return [
            'width_mm' => $page['width_mm'],
            'height_mm' => $page['height_mm'],
            'orientation' => $orientation,
            'orient_css' => $orientation === 'landscape' ? 'landscape' : 'portrait',
        ];
    }
}
