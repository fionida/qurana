<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Support\SantriExcelExport;
use App\Support\SantriReportScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SantriController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filteredQuery($request);

        $detailSantri = null;
        if ($request->filled('detail')) {
            $detailSantri = Santri::with('verifier')->find($request->detail);
        }

        return view('admin.santris.index', [
            'santris' => $query->paginate(15)->withQueryString(),
            'lembagaOptions' => Santri::lembagaOptions(),
            'detailPayload' => $detailSantri ? $this->modalPayload($detailSantri) : null,
            ...SantriReportScope::filterOptions($request),
        ]);
    }

    public function show(Santri $santri): RedirectResponse
    {
        return redirect()->route('admin.santris.index', ['detail' => $santri->id]);
    }

    public function export(Request $request): StreamedResponse
    {
        $santris = $this->filteredQuery($request)
            ->with(['gelombang.program', 'voucher'])
            ->orderBy('nomor_pendaftaran')
            ->cursor();

        return SantriExcelExport::download($santris);
    }

    /**
     * @return Builder<Santri>
     */
    private function filteredQuery(Request $request): Builder
    {
        $query = Santri::query()->with('gelombang.program')->latest();

        SantriReportScope::apply($query, $request);

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

        return $query;
    }

    public function updatePhoto(Request $request, Santri $santri): RedirectResponse
    {
        $request->validate([
            'pas_foto' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
        ], [
            'pas_foto.required' => 'Pas foto wajib diunggah.',
            'pas_foto.image' => 'Pas foto harus berupa gambar.',
            'pas_foto.max' => 'Pas foto maksimal 2 MB.',
        ]);

        if ($santri->pas_foto) {
            Storage::disk('public')->delete($santri->pas_foto);
        }

        $santri->update([
            'pas_foto' => $request->file('pas_foto')->store('pas-foto', 'public'),
        ]);

        return back()->with('success', 'Pas foto pendaftar berhasil diperbarui.');
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
            'metode_pembayaran_label' => $santri->metode_pembayaran_label,
            'status_pembayaran_label' => $santri->status_pembayaran_label,
            'status_pendaftar_label' => $santri->statusPendaftarLabel(),
            'nilai_akhir' => $santri->nilai_akhir,
            'is_lunas' => $santri->isLunas(),
            'kartu_url' => route('admin.santris.kartu-peserta', $santri),
            'foto_url' => asset('storage/'.$santri->pas_foto),
            'bukti_url' => $santri->bukti_transfer ? asset('storage/'.$santri->bukti_transfer) : null,
            'kwitansi_url' => route('admin.payments.kwitansi', $santri),
            'sertifikat_url' => route('admin.certificates.print', $santri),
            'verify_url' => route('admin.payments.index', ['verify' => $santri->id]),
        ];
    }
}
