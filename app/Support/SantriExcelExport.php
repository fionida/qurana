<?php

namespace App\Support;

use App\Models\Santri;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class SantriExcelExport
{
    /**
     * Stream CSV (UTF-8 BOM) that opens cleanly in Excel.
     *
     * @param  iterable<int, Santri>  $santris
     */
    public static function download(iterable $santris): StreamedResponse
    {
        $filename = 'peserta-'.now()->format('Ymd-His').'.csv';

        $headers = [
            'No',
            'No. Daftar',
            'Nama Lengkap',
            'L/P',
            'Tempat Lahir',
            'Tanggal Lahir',
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

        return response()->streamDownload(function () use ($santris, $headers) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, $headers);

            $no = 1;
            foreach ($santris as $santri) {
                fputcsv($out, self::rowValues($santri, $no));
                $no++;
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * @return array<int, string|int|float|null>
     */
    private static function rowValues(Santri $santri, int $no): array
    {
        return [
            $no,
            $santri->nomor_pendaftaran,
            $santri->nama_lengkap,
            $santri->jenis_kelamin ?: '',
            $santri->tempat_lahir,
            $santri->tanggal_lahir?->format('d/m/Y') ?? '',
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
            $santri->isLunas() ? number_format($santri->jumlahBayarEfektif(), 0, ',', '.') : '',
            $santri->voucher?->kode ?? '',
            $santri->verified_at?->format('d/m/Y H:i') ?? '',
            $santri->created_at?->format('d/m/Y H:i') ?? '',
        ];
    }
}
