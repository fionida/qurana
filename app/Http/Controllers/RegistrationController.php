<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSantriRequest;
use App\Models\Santri;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(): View
    {
        return view('registration.create', [
            'rekening' => Setting::rekening(),
            'biaya' => Setting::biayaPendaftaran(),
        ]);
    }

    public function store(StoreSantriRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $pasFotoPath = $request->file('pas_foto')->store('pas-foto', 'public');

        $buktiTransferPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $buktiTransferPath = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
        }

        $santri = Santri::create([
            ...$validated,
            'nomor_pendaftaran' => Santri::generateNomorPendaftaran(),
            'pas_foto' => $pasFotoPath,
            'bukti_transfer' => $buktiTransferPath,
        ]);

        return redirect()
            ->route('registration.success', $santri)
            ->with('success', 'Pendaftaran berhasil dikirim.');
    }

    public function success(Santri $santri): View
    {
        return view('registration.success', [
            'santri' => $santri,
            'rekening' => Setting::rekening(),
            'biaya' => Setting::biayaPendaftaran(),
        ]);
    }

    public function uploadBukti(Request $request, Santri $santri): RedirectResponse
    {
        if ($santri->metode_pembayaran !== 'transfer') {
            return back()->with('error', 'Upload bukti transfer hanya untuk metode transfer.');
        }

        $request->validate([
            'bukti_transfer' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:5120'],
        ], [
            'bukti_transfer.required' => 'Bukti transfer wajib diunggah.',
        ]);

        if ($santri->bukti_transfer) {
            Storage::disk('public')->delete($santri->bukti_transfer);
        }

        $santri->update([
            'bukti_transfer' => $request->file('bukti_transfer')->store('bukti-transfer', 'public'),
        ]);

        return back()->with('success', 'Bukti transfer berhasil diunggah.');
    }
}
