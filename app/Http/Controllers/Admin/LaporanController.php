<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use App\Models\PembayaranRiwayat;
use App\Models\Santri;
use App\Support\SantriReportScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.laporan.index', SantriReportScope::filterOptions($request));
    }

    public function exportPendaftar(Request $request): StreamedResponse
    {
        $query = Santri::query()->with(['gelombang.program'])->orderBy('nomor_pendaftaran');
        SantriReportScope::apply($query, $request);

        return $this->csvResponse('pendaftar-'.now()->format('Ymd-His').'.csv', [
            'No. Daftar', 'Nama', 'Program', 'Gelombang', 'Status Pendaftar', 'Pembayaran', 'Nilai Akhir', 'Asal lembaga', 'No. WA',
        ], $query->cursor(), function (Santri $s) {
            return [
                $s->nomor_pendaftaran,
                $s->nama_lengkap,
                $s->gelombang?->program?->nama ?? '',
                $s->gelombang?->nama ?? '',
                $s->statusPendaftarLabel(),
                $s->status_pembayaran_label,
                $s->nilai_akhir ?? '',
                $s->lembaga,
                $s->no_wa ?? '',
            ];
        });
    }

    public function exportPembayaran(Request $request): StreamedResponse
    {
        $query = PembayaranRiwayat::query()
            ->with(['santri.gelombang.program', 'voucher', 'verifier'])
            ->orderByDesc('verified_at');

        SantriReportScope::applyToPembayaranRiwayat($query, $request);

        return $this->csvResponse('pembayaran-'.now()->format('Ymd-His').'.csv', [
            'Tanggal', 'No. Daftar', 'Nama', 'Program', 'Gelombang', 'Nominal', 'Diskon', 'Jumlah Bayar', 'Metode', 'Voucher', 'Verifikator',
        ], $query->cursor(), function (PembayaranRiwayat $r) {
            return [
                $r->verified_at->format('Y-m-d H:i'),
                $r->santri->nomor_pendaftaran,
                $r->santri->nama_lengkap,
                $r->santri->gelombang?->program?->nama ?? '',
                $r->santri->gelombang?->nama ?? '',
                $r->nominal_dasar,
                $r->diskon,
                $r->jumlah_bayar,
                $r->metode,
                $r->voucher?->kode ?? '',
                $r->verifier?->name ?? '',
            ];
        });
    }

    public function exportRekonsiliasi(Request $request): StreamedResponse
    {
        $query = Santri::query()
            ->with(['gelombang.program', 'voucher'])
            ->where('status_pembayaran', 'lunas')
            ->orderBy('verified_at');

        SantriReportScope::apply($query, $request);

        return $this->csvResponse('rekonsiliasi-'.now()->format('Ymd-His').'.csv', [
            'No. Daftar', 'Nama', 'Program', 'Gelombang', 'Metode', 'Nominal Dasar', 'Diskon', 'Jumlah Bayar', 'Tgl Verifikasi', 'Voucher',
        ], $query->cursor(), function (Santri $s) {
            $dasar = $s->nominalPendaftaranDasar();
            $bayar = $s->jumlahBayarEfektif();

            return [
                $s->nomor_pendaftaran,
                $s->nama_lengkap,
                $s->gelombang?->program?->nama ?? '',
                $s->gelombang?->nama ?? '',
                $s->metode_pembayaran,
                $dasar,
                max(0, $dasar - $bayar),
                $bayar,
                $s->verified_at?->format('Y-m-d H:i') ?? '',
                $s->voucher?->kode ?? '',
            ];
        });
    }

    public function exportNilaiTes(Gelombang $gelombang): StreamedResponse
    {
        $gelombang->load('program');
        $gelombang->ensureKomponenTesSeeded();
        $komponen = $gelombang->komponenTes;

        $headers = ['No. Daftar', 'Nama', 'Program', 'Nilai Akhir', 'Status'];
        foreach ($komponen as $k) {
            $headers[] = $k->nama_komponen;
        }

        $santris = Santri::query()
            ->with('nilaiTes')
            ->where('gelombang_id', $gelombang->id)
            ->orderByDesc('nilai_akhir')
            ->get();

        return Response::streamDownload(function () use ($santris, $komponen, $headers, $gelombang) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, $headers);

            foreach ($santris as $santri) {
                $map = $santri->nilaiTes->keyBy('gelombang_komponen_tes_id');
                $row = [
                    $santri->nomor_pendaftaran,
                    $santri->nama_lengkap,
                    $gelombang->program?->nama ?? '',
                    $santri->nilai_akhir ?? '',
                    $santri->statusPendaftarLabel(),
                ];
                foreach ($komponen as $k) {
                    $row[] = $map->get($k->id)?->nilai ?? '';
                }
                fputcsv($out, $row);
            }
            fclose($out);
        }, 'nilai-tes-'.$gelombang->id.'-'.now()->format('Ymd').'.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * @param  iterable<int, mixed>  $rows
     * @param  callable(mixed): array<int, string|int|float|null>  $mapRow
     */
    private function csvResponse(string $filename, array $header, iterable $rows, callable $mapRow): StreamedResponse
    {
        return Response::streamDownload(function () use ($header, $rows, $mapRow) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, $header);
            foreach ($rows as $row) {
                fputcsv($out, $mapRow($row));
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
