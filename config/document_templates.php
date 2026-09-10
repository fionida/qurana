<?php

return [
    'storage_directory' => 'document-templates',

    'slots' => [
        'sertifikat_depan' => [
            'label' => 'Sertifikat — halaman depan',
            'description' => 'Master desain depan sertifikat. PNG/PDF dipakai FPDI saat di-assign ke gelombang; DOC/DOCX untuk edit di Word.',
            'accept' => '.png,.jpg,.jpeg,.pdf,.doc,.docx',
            'mimes' => 'jpeg,jpg,png,pdf,doc,docx',
            'max_kb' => 15360,
        ],
        'kwitansi' => [
            'label' => 'Kwitansi pembayaran',
            'description' => 'PNG/JPG = latar cetak kwitansi (teks dinamis ditimpa). Word = master untuk diedit manual.',
            'accept' => '.png,.jpg,.jpeg,.doc,.docx',
            'mimes' => 'jpeg,jpg,png,doc,docx',
            'max_kb' => 10240,
        ],
        'kartu_ujian' => [
            'label' => 'Kartu ujian / kartu peserta',
            'description' => 'PNG/JPG = latar kartu peserta. Word = master untuk diedit manual.',
            'accept' => '.png,.jpg,.jpeg,.doc,.docx',
            'mimes' => 'jpeg,jpg,png,doc,docx',
            'max_kb' => 10240,
        ],
    ],
];
