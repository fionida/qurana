<?php

namespace App\Support;

use App\Models\Santri;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class SantriExcelExport
{
    /**
     * @param  iterable<int, Santri>  $santris
     */
    public static function download(iterable $santris): StreamedResponse
    {
        $filename = 'peserta-'.now()->format('Ymd-His').'.xlsx';

        return response()->streamDownload(function () use ($santris) {
            $spreadsheet = new Spreadsheet;
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Peserta');

            $headers = [
                'No. Daftar',
                'Nama Lengkap',
                'Tempat Lahir',
                'Tanggal Lahir',
                'Jenis Kelamin',
                'Program',
                'Gelombang',
                'Asal Lembaga',
                'Alamat',
                'Desa/Kelurahan',
                'Kecamatan',
                'Kota/Kabupaten',
                'Provinsi',
                'No. WhatsApp',
                'Email',
                'Metode Pembayaran',
                'Status Pembayaran',
                'Status Pendaftar',
                'Nilai Akhir',
                'N.I.Q',
                'Nomor Sertifikat',
                'Jumlah Bayar (Rp)',
                'Voucher',
                'Tgl Verifikasi',
                'Tgl Daftar',
            ];

            foreach ($headers as $col => $header) {
                $sheet->setCellValue([$col + 1, 1], $header);
            }

            $row = 2;
            foreach ($santris as $santri) {
                $values = self::rowValues($santri);
                foreach ($values as $col => $value) {
                    $sheet->setCellValue([$col + 1, $row], $value);
                }
                $row++;
            }

            $sheet->getStyle('A1:Y1')->getFont()->setBold(true);
            foreach (range('A', 'Y') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            (new Xlsx($spreadsheet))->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * @return array<int, string|int|float|null>
     */
    private static function rowValues(Santri $santri): array
    {
        return [
            $santri->nomor_pendaftaran,
            $santri->nama_lengkap,
            $santri->tempat_lahir,
            $santri->tanggal_lahir?->format('Y-m-d') ?? '',
            $santri->jenis_kelamin_label,
            $santri->gelombang?->program?->nama ?? '',
            $santri->gelombang?->nama ?? '',
            $santri->lembaga,
            $santri->alamat,
            $santri->desa,
            $santri->kecamatan,
            $santri->kota_kab,
            $santri->provinsi,
            $santri->no_wa,
            $santri->email,
            $santri->metode_pembayaran_label,
            $santri->status_pembayaran_label,
            $santri->statusPendaftarLabel(),
            $santri->nilai_akhir ?? '',
            $santri->niq ?? '',
            $santri->nomor_sertifikat ?? '',
            $santri->isLunas() ? $santri->jumlahBayarEfektif() : '',
            $santri->voucher?->kode ?? '',
            $santri->verified_at?->format('Y-m-d H:i') ?? '',
            $santri->created_at?->format('Y-m-d H:i') ?? '',
        ];
    }
}
