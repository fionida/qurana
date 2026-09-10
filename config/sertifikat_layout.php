<?php

/**
 * Posisi overlay teks dinamis (mm) — A4 landscape (297 × 210 mm).
 * Acuan: template QFI halaman depan (PNG/JPG). Sesuaikan setelah uji cetak.
 */
return [
    /** Cetak sertifikat: fpdi (template PDF/gambar + mm) atau dompdf (HTML). */
    'engine' => env('SERTIFIKAT_PDF_ENGINE', 'fpdi'),

    'page' => [
        'orientation' => 'landscape',
        'width_mm' => 297,
        'height_mm' => 210,
    ],

    'halaman_1' => [
        'nomor_sertifikat' => ['top' => 132, 'left' => 0, 'width' => 297, 'size' => 11, 'align' => 'center'],
        'nama_lengkap' => ['top' => 72, 'left' => 102, 'width' => 180, 'size' => 11],
        'ttl' => ['top' => 81, 'left' => 102, 'width' => 180, 'size' => 11],
        'niq' => ['top' => 90, 'left' => 102, 'width' => 180, 'size' => 11],
        'alamat' => ['top' => 99, 'left' => 102, 'width' => 88, 'size' => 10],
        'lembaga' => ['top' => 99, 'left' => 200, 'width' => 85, 'size' => 10],
        'tanggal_sertifikasi' => ['top' => 118, 'left' => 33, 'width' => 231, 'size' => 11, 'align' => 'center'],
        'tanggal_terbit' => ['top' => 150, 'left' => 206, 'width' => 78, 'size' => 10, 'align' => 'center'],
    ],

    'contoh_template_halaman_1' => 'sertifikat-template/template-halaman-1-contoh.png',
    'contoh_template_halaman_2' => 'sertifikat-template/template-halaman-2-contoh.png',

    /** Halaman belakang: tabel nilai komponen tes (mm). */
    'halaman_2' => [
        'komponen' => ['left' => 42, 'width' => 155],
        'nilai' => ['left' => 218, 'width' => 35],
        'row_start_top' => 48.5,
        'row_height' => 7.35,
        'total_row_top' => 107.5,
        'total_komponen_label' => 'Nilai akhir',
        'font_size' => 10.5,
    ],
];
