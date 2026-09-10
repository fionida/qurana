<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use App\Models\GelombangKomponenTes;
use App\Models\NilaiTes;
use App\Models\Santri;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class TesController extends Controller
{
    public function index(Request $request): View
    {
        $gelombangId = $request->integer('gelombang');

        $gelombang = $gelombangId
            ? Gelombang::find($gelombangId)
            : Gelombang::query()->orderByDesc('id')->first();

        if ($gelombang) {
            $gelombang->ensureKomponenTesSeeded();
        }

        $santris = collect();

        if ($gelombang) {
            $santris = Santri::query()
                ->with('nilaiTes')
                ->where('gelombang_id', $gelombang->id)
                ->whereIn('status_pendaftar', ['lunas', 'mengikuti_tes', 'lulus', 'tidak_lulus', 'sertifikat_diterbitkan'])
                ->orderBy('nama_lengkap')
                ->get();
        }

        return view('admin.tes.index', [
            'gelombang' => $gelombang,
            'gelombangOptions' => Gelombang::query()
                ->with('program')
                ->whereHas('program', fn ($q) => $q->where('butuh_seleksi_tes', true))
                ->orderByDesc('id')
                ->get(),
            'komponen' => $gelombang?->komponenTes ?? collect(),
            'santris' => $santris,
        ]);
    }

    public function storeNilai(Request $request, Gelombang $gelombang): RedirectResponse
    {
        $gelombang->ensureKomponenTesSeeded();

        $validated = $request->validate([
            'santri_id' => ['required', 'exists:santris,id'],
            'nilai' => ['required', 'array'],
            'nilai.*' => ['nullable', 'numeric', 'min:0', 'max:1000'],
        ]);

        $santri = Santri::query()
            ->where('gelombang_id', $gelombang->id)
            ->findOrFail($validated['santri_id']);

        foreach ($validated['nilai'] as $komponenId => $nilai) {
            if ($nilai === null || $nilai === '') {
                continue;
            }

            GelombangKomponenTes::query()
                ->where('gelombang_id', $gelombang->id)
                ->whereKey($komponenId)
                ->firstOrFail();

            NilaiTes::query()->updateOrCreate(
                [
                    'santri_id' => $santri->id,
                    'gelombang_komponen_tes_id' => $komponenId,
                ],
                [
                    'nilai' => $nilai,
                    'diinput_oleh' => auth()->id(),
                ]
            );
        }

        $santri->hitungDanSimpanNilaiAkhir();

        if ($santri->status_pendaftar === 'lunas') {
            $santri->update(['status_pendaftar' => 'mengikuti_tes']);
        }

        return back()->with('success', 'Nilai tes berhasil disimpan untuk '.$santri->nama_lengkap);
    }

    public function rekap(Gelombang $gelombang): View
    {
        $gelombang->ensureKomponenTesSeeded();

        return view('admin.tes.rekap', [
            'gelombang' => $gelombang,
            'ranking' => $this->rankingFor($gelombang),
        ]);
    }

    public function daftarHadir(Gelombang $gelombang): View
    {
        $santris = Santri::query()
            ->where('gelombang_id', $gelombang->id)
            ->whereIn('status_pendaftar', ['lunas', 'mengikuti_tes', 'lulus', 'tidak_lulus', 'sertifikat_diterbitkan'])
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.tes.daftar-hadir', [
            'gelombang' => $gelombang,
            'santris' => $santris,
        ]);
    }

    public function beritaAcara(Gelombang $gelombang): View
    {
        $ranking = $this->rankingFor($gelombang);

        return view('admin.tes.berita-acara', [
            'gelombang' => $gelombang,
            'ranking' => $ranking,
            'lulus' => $ranking->filter(fn (Santri $s) => $s->status_pendaftar === 'lulus' || $s->status_pendaftar === 'sertifikat_diterbitkan'),
        ]);
    }

    public function tentukanKelulusan(Gelombang $gelombang): RedirectResponse
    {
        $gelombang->ensureKomponenTesSeeded();
        $batas = $gelombang->nilai_lulus_minimal;

        $santris = Santri::query()
            ->where('gelombang_id', $gelombang->id)
            ->where('status_pendaftar', 'mengikuti_tes')
            ->get();

        foreach ($santris as $santri) {
            $santri->hitungDanSimpanNilaiAkhir();
            $santri->refresh();

            if ($santri->nilai_akhir === null) {
                continue;
            }

            $lulus = $batas === null || $santri->nilai_akhir >= (float) $batas;

            $santri->update([
                'status_pendaftar' => $lulus ? 'lulus' : 'tidak_lulus',
                'status_kelulusan' => $lulus ? 'lulus' : 'tidak_lulus',
            ]);
        }

        return back()->with('success', 'Kelulusan diperbarui berdasarkan nilai akhir'.($batas !== null ? " (batas {$batas})" : '').'.');
    }

    /**
     * @return Collection<int, Santri>
     */
    private function rankingFor(Gelombang $gelombang)
    {
        return Santri::query()
            ->where('gelombang_id', $gelombang->id)
            ->whereNotNull('nilai_akhir')
            ->orderByDesc('nilai_akhir')
            ->get();
    }
}
