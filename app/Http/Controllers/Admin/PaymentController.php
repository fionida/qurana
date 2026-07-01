<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Santri::query()->latest();

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        } else {
            $query->where('status_pembayaran', 'pending');
        }

        if ($request->filled('metode_pembayaran')) {
            $query->where('metode_pembayaran', $request->metode_pembayaran);
        }

        $verifySantri = null;
        if ($request->filled('verify')) {
            $verifySantri = Santri::with('verifier')->find($request->verify);
        }

        return view('admin.payments.index', [
            'santris' => $query->paginate(15)->withQueryString(),
            'verifyPayload' => $verifySantri ? $this->modalPayload($verifySantri) : null,
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

        $santri->update([
            'status_pembayaran' => 'lunas',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.payments.index', ['verify' => $santri->id])
            ->with('success', 'Pembayaran berhasil diverifikasi sebagai lunas.');
    }

    public function kwitansi(Santri $santri): Response
    {
        if (! $santri->isLunas()) {
            abort(403, 'Kwitansi hanya tersedia untuk santri yang sudah lunas.');
        }

        $pdf = Pdf::loadView('admin.kwitansi', [
            'santri' => $santri,
            'biaya' => Setting::biayaPendaftaran(),
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
            'biaya_formatted' => 'Rp '.number_format(Setting::biayaPendaftaran(), 0, ',', '.'),
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
