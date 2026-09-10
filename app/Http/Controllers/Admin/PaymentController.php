<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranRiwayat;
use App\Models\Santri;
use App\Support\DocumentTemplate;
use App\Support\SantriReportScope;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Santri::query()->with('gelombang.program')->latest();

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        } else {
            $query->where('status_pembayaran', 'pending');
        }

        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        SantriReportScope::apply($query, $request);

        $verifySantri = null;
        if ($request->filled('verify')) {
            $verifySantri = Santri::with('verifier')->find($request->verify);
        }

        return view('admin.payments.index', [
            'santris' => $query->paginate(15)->withQueryString(),
            'verifyPayload' => $verifySantri ? $this->modalPayload($verifySantri) : null,
            ...SantriReportScope::filterOptions($request),
        ]);
    }

    public function show(Santri $santri): RedirectResponse
    {
        return redirect()->route('admin.payments.index', ['verify' => $santri->id]);
    }

    public function verify(Santri $santri): RedirectResponse
    {
        if ($santri->isLunas()) {
            return back()->with('error', 'Pembayaran sudah diverifikasi.');
        }

        $dasar = $santri->nominalPendaftaranDasar();
        $jumlahBayar = $santri->jumlahBayarEfektif();
        $diskon = max(0, $dasar - $jumlahBayar);

        $santri->load('gelombang.program');
        $program = $santri->gelombang?->program;
        $statusPendaftar = 'lunas';

        if ($program && ! $program->butuh_seleksi_tes && ! $program->butuh_kelulusan) {
            $statusPendaftar = 'lulus';
        }

        $santri->update([
            'status_pembayaran' => 'lunas',
            'status_pendaftar' => $statusPendaftar,
            'jumlah_bayar' => $jumlahBayar,
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        if ($santri->voucher_id) {
            $santri->voucher?->increment('terpakai');
        }

        PembayaranRiwayat::create([
            'santri_id' => $santri->id,
            'nominal_dasar' => $dasar,
            'diskon' => $diskon,
            'jumlah_bayar' => $jumlahBayar,
            'metode' => $santri->metode_pembayaran,
            'voucher_id' => $santri->voucher_id,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.payments.index', array_filter([
                'verify' => $santri->id,
                'program' => request('program'),
                'gelombang' => request('gelombang'),
                'status_pembayaran' => request('status_pembayaran'),
                'metode_pembayaran' => request('metode_pembayaran'),
            ], fn ($v) => $v !== null && $v !== ''))
            ->with('success', 'Pembayaran berhasil diverifikasi sebagai lunas.');
    }

    public function kwitansi(Santri $santri): Response
    {
        if (! $santri->isLunas()) {
            abort(403, 'Kwitansi hanya tersedia untuk santri yang sudah lunas.');
        }

        $pdf = Pdf::loadView('admin.kwitansi', [
            'santri' => $santri,
            'biaya' => $santri->jumlahBayarEfektif(),
            'backgroundPath' => DocumentTemplate::isPrintableBackground('kwitansi')
                ? DocumentTemplate::absolutePath('kwitansi')
                : null,
        ])->setPaper('a5', 'portrait');

        return $pdf->stream("kwitansi-{$santri->nomor_pendaftaran}.pdf");
    }

    public static function modalPayload(Santri $santri): array
    {
        return [
            'nama_lengkap' => $santri->nama_lengkap,
            'nomor_pendaftaran' => $santri->nomor_pendaftaran,
            'lembaga' => $santri->lembaga,
            'metode_pembayaran' => $santri->metode_pembayaran,
            'metode_pembayaran_label' => $santri->metode_pembayaran_label,
            'status_pembayaran_label' => $santri->status_pembayaran_label,
            'is_lunas' => $santri->isLunas(),
            'biaya_formatted' => 'Rp '.number_format($santri->jumlahBayarEfektif(), 0, ',', '.'),
            'foto_url' => asset('storage/'.$santri->pas_foto),
            'bukti_url' => $santri->bukti_transfer ? asset('storage/'.$santri->bukti_transfer) : null,
            'verify_url' => route('admin.payments.verify', $santri),
            'kwitansi_url' => route('admin.payments.kwitansi', $santri),
            'sertifikat_url' => route('admin.certificates.print', $santri),
            'verified_info' => $santri->verified_at
                ? 'Pembayaran diverifikasi pada '.$santri->verified_at->format('d/m/Y H:i')
                : '',
        ];
    }
}
