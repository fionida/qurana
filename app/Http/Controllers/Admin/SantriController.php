<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SantriController extends Controller
{
    public function index(Request $request): View
    {
        $query = Santri::query()->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nomor_pendaftaran', 'like', "%{$search}%")
                    ->orWhere('no_wa', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('lembaga')) {
            $query->where('lembaga', $request->lembaga);
        }

        if ($request->filled('status_pembayaran')) {
            $query->where('status_pembayaran', $request->status_pembayaran);
        }

        $detailSantri = null;
        if ($request->filled('detail')) {
            $detailSantri = Santri::with('verifier')->find($request->detail);
        }

        return view('admin.santris.index', [
            'santris' => $query->paginate(15)->withQueryString(),
            'lembagaOptions' => Santri::lembagaOptions(),
            'detailPayload' => $detailSantri ? $this->modalPayload($detailSantri) : null,
        ]);
    }

    public function show(Santri $santri): RedirectResponse
    {
        return redirect()->route('admin.santris.index', ['detail' => $santri->id]);
    }

    public function destroy(Santri $santri): RedirectResponse
    {
        if ($santri->pas_foto) {
            Storage::disk('public')->delete($santri->pas_foto);
        }

        if ($santri->bukti_transfer) {
            Storage::disk('public')->delete($santri->bukti_transfer);
        }

        $santri->delete();

        return back()->with('success', 'Data santri berhasil dihapus.');
    }

    public static function modalPayload(Santri $santri): array
    {
        return [
            'nomor_pendaftaran' => $santri->nomor_pendaftaran,
            'nama_lengkap' => $santri->nama_lengkap,
            'ttl' => $santri->ttl,
            'jenis_kelamin_label' => $santri->jenis_kelamin_label,
            'lembaga' => $santri->lembaga,
            'no_wa' => $santri->no_wa,
            'email' => $santri->email,
            'alamat' => $santri->alamat,
            'alamat_lengkap' => $santri->alamat_lengkap,
            'wilayah_lengkap' => $santri->wilayah_lengkap,
            'provinsi' => $santri->provinsi,
            'kota_kab' => $santri->kota_kab,
            'kecamatan' => $santri->kecamatan,
            'desa' => $santri->desa,
            'metode_pembayaran_label' => $santri->metode_pembayaran_label,
            'status_pembayaran_label' => $santri->status_pembayaran_label,
            'is_lunas' => $santri->isLunas(),
            'foto_url' => asset('storage/'.$santri->pas_foto),
            'bukti_url' => $santri->bukti_transfer ? asset('storage/'.$santri->bukti_transfer) : null,
            'kwitansi_url' => route('admin.payments.kwitansi', $santri),
            'sertifikat_url' => route('admin.certificates.print', $santri),
            'verify_url' => route('admin.payments.index', ['verify' => $santri->id]),
        ];
    }
}
