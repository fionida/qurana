<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSantriRequest;
use App\Models\Gelombang;
use App\Models\Program;
use App\Models\Santri;
use App\Models\SantriDokumen;
use App\Models\Setting;
use App\Models\Voucher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class RegistrationController extends Controller
{
    public function create(Program $program): View|RedirectResponse
    {
        if (! $program->is_active) {
            return redirect()->route('portal.home')->with('error', 'Program tidak tersedia.');
        }

        $gelombang = Gelombang::openForRegistration($program->id);

        return view('registration.create', [
            'program' => $program,
            'rekening' => Setting::rekening(),
            'biaya' => $gelombang?->biayaPendaftaranEfektif() ?? Setting::biayaPendaftaran(),
            'gelombang' => $gelombang,
            'sisaKuota' => $gelombang?->sisaKuotaPendaftaran(),
            'loggedInUsername' => auth()->user()?->name,
            'wilayahOld' => $this->wilayahOldFromInput(),
            'wizardInitialStep' => $this->wizardInitialStepFromErrors(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function wilayahOldFromInput(): array
    {
        return [
            'provinsi_id' => old('provinsi_id'),
            'kota_kab_id' => old('kota_kab_id'),
            'kecamatan_id' => old('kecamatan_id'),
            'desa_id' => old('desa_id'),
            'provinsi' => old('provinsi'),
            'kota_kab' => old('kota_kab'),
            'kecamatan' => old('kecamatan'),
            'desa' => old('desa'),
        ];
    }

    private function wizardInitialStepFromErrors(): int
    {
        $bag = session('errors');
        if (! $bag || ! $bag->any()) {
            return 1;
        }

        $map = [
            'nama_lengkap' => 1, 'tempat_lahir' => 1, 'tanggal_lahir' => 1, 'jenis_kelamin' => 1, 'no_wa' => 1, 'email' => 1,
            'alamat' => 2, 'provinsi_id' => 2, 'provinsi' => 2, 'kota_kab_id' => 2, 'kota_kab' => 2,
            'kecamatan_id' => 2, 'kecamatan' => 2, 'desa_id' => 2, 'desa' => 2,
            'lembaga' => 3, 'dokumen_surat_rekomendasi' => 3,
            'pas_foto' => 4, 'dokumen_ktp' => 4,
            'metode_pembayaran' => 5, 'bukti_transfer' => 5, 'kode_voucher' => 5,
            'pernyataan_benar' => 6,
        ];

        foreach ($map as $field => $step) {
            if ($bag->has($field)) {
                return $step;
            }
        }

        return 1;
    }

    public function store(StoreSantriRequest $request, Program $program): RedirectResponse
    {
        if (! $program->is_active) {
            return back()->withInput()->with('error', 'Program tidak tersedia.');
        }

        $gelombang = Gelombang::openForRegistration($program->id);

        if (! $gelombang) {
            return back()->withInput()->with('error', 'Pendaftaran sedang ditutup atau kuota gelombang sudah penuh.');
        }

        $validated = $request->validated();

        $voucher = null;
        if (! empty($validated['kode_voucher'])) {
            $voucher = Voucher::findValidByCode($validated['kode_voucher'], $gelombang);
            if (! $voucher) {
                return back()->withInput()->withErrors(['kode_voucher' => 'Kode voucher tidak valid atau sudah tidak berlaku.']);
            }
        }

        $nominalDasar = $gelombang->biayaPendaftaranEfektif();
        $jumlahBayar = $voucher ? $voucher->hitungJumlahBayar($nominalDasar) : $nominalDasar;

        $pasFotoPath = $request->file('pas_foto')->store('pas-foto', 'public');

        $buktiTransferPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $buktiTransferPath = $request->file('bukti_transfer')->store('bukti-transfer', 'public');
        }

        unset($validated['kode_voucher'], $validated['dokumen_ktp'], $validated['dokumen_surat_rekomendasi']);

        $santri = Santri::create([
            ...$validated,
            'gelombang_id' => $gelombang->id,
            'voucher_id' => $voucher?->id,
            'jumlah_bayar' => $jumlahBayar,
            'nomor_pendaftaran' => Santri::generateNomorPendaftaran(),
            'pas_foto' => $pasFotoPath,
            'bukti_transfer' => $buktiTransferPath,
            'status_pendaftar' => 'menunggu_pembayaran',
            'status_kelulusan' => 'belum_tes',
        ]);

        $this->storeDokumen($request, $santri, 'dokumen_ktp', 'ktp');
        $this->storeDokumen($request, $santri, 'dokumen_surat_rekomendasi', 'surat_rekomendasi');

        return redirect()
            ->route('registration.success', $santri)
            ->with('success', 'Pendaftaran berhasil dikirim.');
    }

    private function storeDokumen(Request $request, Santri $santri, string $field, string $jenis): void
    {
        if (! $request->hasFile($field)) {
            return;
        }

        $file = $request->file($field);
        SantriDokumen::create([
            'santri_id' => $santri->id,
            'jenis' => $jenis,
            'path' => $file->store('dokumen-pendaftar', 'public'),
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function success(Santri $santri): View
    {
        $santri->load('gelombang.program');

        return view('registration.success', [
            'santri' => $santri,
            'rekening' => Setting::rekening(),
            'biaya' => $santri->jumlahBayarEfektif(),
            'nominalDasar' => $santri->nominalPendaftaranDasar(),
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
