<?php

namespace App\Support;

class SertifikatLayout
{
    /**
     * @return array<string, array<string, float|int|string>>
     */
    public static function defaultHalaman1(): array
    {
        $ph = (float) config('sertifikat_layout.page.height_mm', 210);
        $pw = (float) config('sertifikat_layout.page.width_mm', 297);

        $fromConfig = config('sertifikat_layout.halaman_1', []);
        $out = [];

        foreach ($fromConfig as $key => $box) {
            $out[$key] = self::mmBoxToPercent($box, $pw, $ph);
        }

        return $out;
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultHalaman2(): array
    {
        $pw = 297.0;
        $ph = 210.0;
        $h2 = config('sertifikat_layout.halaman_2', []);

        $komponenLeft = (float) ($h2['komponen']['left'] ?? $h2['durasi']['left'] ?? 42);
        $komponenWidth = (float) ($h2['komponen']['width'] ?? $h2['durasi']['width'] ?? 155);
        $nilaiLeft = (float) ($h2['nilai']['left'] ?? $h2['jpl']['left'] ?? 218);
        $nilaiWidth = (float) ($h2['nilai']['width'] ?? $h2['jpl']['width'] ?? 35);

        return [
            'komponen_left_pct' => round($komponenLeft / $pw * 100, 2),
            'komponen_width_pct' => round($komponenWidth / $pw * 100, 2),
            'nilai_left_pct' => round($nilaiLeft / $pw * 100, 2),
            'nilai_width_pct' => round($nilaiWidth / $pw * 100, 2),
            'row_start_top_pct' => round(((float) ($h2['row_start_top'] ?? 48.5)) / $ph * 100, 2),
            'row_height_pct' => round(((float) ($h2['row_height'] ?? 7.35)) / $ph * 100, 2),
            'total_row_top_pct' => round(((float) ($h2['total_row_top'] ?? 107.5)) / $ph * 100, 2),
            'font_size' => $h2['font_size'] ?? 10.5,
            'total_komponen_label' => $h2['total_komponen_label'] ?? 'Nilai akhir',
        ];
    }

    /**
     * @param  array<string, mixed>  $stored
     * @return array<string, mixed>
     */
    public static function normalizeHalaman2(array $stored): array
    {
        $defaults = self::defaultHalaman2();
        $merged = array_merge($defaults, $stored);

        if (isset($stored['durasi_left_pct']) && ! isset($stored['komponen_left_pct'])) {
            $merged['komponen_left_pct'] = $stored['durasi_left_pct'];
        }
        if (isset($stored['durasi_width_pct']) && ! isset($stored['komponen_width_pct'])) {
            $merged['komponen_width_pct'] = $stored['durasi_width_pct'];
        }
        if (isset($stored['jpl_left_pct']) && ! isset($stored['nilai_left_pct'])) {
            $merged['nilai_left_pct'] = $stored['jpl_left_pct'];
        }
        if (isset($stored['jpl_width_pct']) && ! isset($stored['nilai_width_pct'])) {
            $merged['nilai_width_pct'] = $stored['jpl_width_pct'];
        }

        return $merged;
    }

    /**
     * @param  array<string, mixed>  $box
     * @return array<string, mixed>
     */
    public static function mmBoxToPercent(array $box, float $pageWidthMm, float $pageHeightMm): array
    {
        return [
            'top_pct' => round(((float) $box['top']) / $pageHeightMm * 100, 2),
            'left_pct' => round(((float) $box['left']) / $pageWidthMm * 100, 2),
            'width_pct' => round(((float) $box['width']) / $pageWidthMm * 100, 2),
            'size' => $box['size'] ?? 11,
            'align' => $box['align'] ?? 'left',
        ];
    }

    /**
     * @param  array<string, mixed>  $box
     * @return array{top: float, left: float, width: float, size: float|int, align: string}
     */
    public static function percentBoxToMm(array $box, float $pageWidthMm, float $pageHeightMm): array
    {
        return [
            'top' => round(((float) ($box['top_pct'] ?? 0)) / 100 * $pageHeightMm, 2),
            'left' => round(((float) ($box['left_pct'] ?? 0)) / 100 * $pageWidthMm, 2),
            'width' => round(((float) ($box['width_pct'] ?? 50)) / 100 * $pageWidthMm, 2),
            'size' => $box['size'] ?? 11,
            'align' => $box['align'] ?? 'left',
        ];
    }

    /**
     * @param  array<string, mixed>  $box
     */
    public static function boxStyle(array $box): string
    {
        $size = ($box['size'] ?? 11).'pt';

        return sprintf(
            'top:%s%%;left:%s%%;width:%s%%;font-size:%s;',
            $box['top_pct'] ?? 0,
            $box['left_pct'] ?? 0,
            $box['width_pct'] ?? 50,
            $size,
        );
    }

    public static function boxClasses(array $box, string $base = 'field'): string
    {
        $classes = [$base];
        if (($box['align'] ?? 'left') === 'center') {
            $classes[] = 'field-center';
        }

        return implode(' ', $classes);
    }
}
