<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use App\Models\Santri;
use App\Support\CertificateTemplatePreview;
use App\Support\SertifikatPageLayout;
use App\Support\SertifikatPdfGenerator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function index(Request $request): View
    {
        $query = Santri::query()
            ->with('gelombang')
            ->eligibleForCertificate()
            ->latest('verified_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nomor_pendaftaran', 'like', "%{$search}%")
                    ->orWhere('nomor_sertifikat', 'like', "%{$search}%")
                    ->orWhere('niq', 'like', "%{$search}%");
            });
        }

        if ($request->filled('lembaga')) {
            $query->where('lembaga', $request->lembaga);
        }

        if ($request->filled('gelombang')) {
            $query->where('gelombang_id', $request->gelombang);
        }

        return view('admin.certificates.index', [
            'santris' => $query->paginate(15)->withQueryString(),
            'lembagaOptions' => Santri::lembagaOptions(),
            'gelombangOptions' => Gelombang::query()->orderByDesc('id')->get(),
        ]);
    }

    public function updateGelombang(Request $request, Santri $santri): RedirectResponse
    {
        if (! $santri->isLunas()) {
            return back()->with('error', 'Hanya pendidik lunas yang dapat diatur gelombangnya.');
        }

        if ($santri->nomor_sertifikat_urut !== null) {
            return back()->with('error', 'Gelombang tidak dapat diubah setelah nomor sertifikat diterbitkan.');
        }

        $validated = $request->validate([
            'gelombang_id' => ['required', 'exists:gelombangs,id'],
        ]);

        $santri->update(['gelombang_id' => $validated['gelombang_id']]);

        return back()->with('success', 'Gelombang pendidik berhasil diperbarui.');
    }

    public function print(Santri $santri): Response
    {
        $data = $this->prepareCertificate($santri);

        $safeName = str_replace(['/', '\\'], '-', $santri->nomor_sertifikat ?? $santri->nomor_pendaftaran);
        $filename = "sertifikat-{$safeName}.pdf";

        if (config('sertifikat_layout.engine', 'fpdi') === 'dompdf') {
            return $this->streamDompdf($data, $filename);
        }

        try {
            $binary = SertifikatPdfGenerator::generate($data);

            return response($binary, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'"',
            ]);
        } catch (\Throwable $e) {
            report($e);

            return $this->streamDompdf($data, $filename);
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function streamDompdf(array $data, string $filename): Response
    {
        $orientation = config('sertifikat_layout.page.orientation', 'landscape');
        $pdf = Pdf::loadView('admin.sertifikat', $data)->setPaper('a4', $orientation);

        return $pdf->stream($filename);
    }

    public function preview(Santri $santri): View
    {
        return view('admin.sertifikat-preview', $this->prepareCertificate($santri, forBrowser: true));
    }

    public function massCetak(Gelombang $gelombang): View
    {
        $santris = Santri::query()
            ->with('gelombang')
            ->where('gelombang_id', $gelombang->id)
            ->eligibleForCertificate()
            ->orderBy('nama_lengkap')
            ->get();

        return view('admin.certificates.mass', [
            'gelombang' => $gelombang,
            'santris' => $santris,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function prepareCertificate(Santri $santri, bool $forBrowser = false): array
    {
        if (! $santri->bolehCetakSertifikat()) {
            abort(403, 'Sertifikat hanya untuk peserta yang lulus seleksi (atau yang sudah pernah diterbitkan nomornya).');
        }

        $gelombang = $santri->gelombang;

        if (! $gelombang) {
            abort(422, 'Pendidik belum memiliki gelombang. Atur gelombang di menu Cetak Sertifikat terlebih dahulu.');
        }

        $gelombang->load('program');

        if (! $gelombang->templateHalaman1Path()) {
            abort(422, 'Template sertifikat halaman 1 belum diunggah pada gelombang ini.');
        }

        $gelombang->ensureKomponenTesSeeded();
        $gelombang->assignCertificateNumbers($santri);

        $santri->refresh()->load(['gelombang.komponenTes', 'nilaiTes']);

        $gelombang = $santri->gelombang->load(['komponenTes', 'program.penandatangans']);
        $program = $gelombang->program;
        $nilaiMap = $santri->nilaiTes->keyBy('gelombang_komponen_tes_id');
        $komponenTesRows = $gelombang->komponenTes->map(function ($komponen) use ($nilaiMap) {
            $nilai = $nilaiMap->get($komponen->id)?->nilai;

            return [
                'nama' => $komponen->nama_komponen,
                'nilai_maksimal' => number_format((float) $komponen->nilai_maksimal, 0, ',', '.'),
                'nilai' => $nilai !== null ? number_format((float) $nilai, 2, ',', '.') : '—',
            ];
        });
        $nilaiAkhir = $santri->nilai_akhir ?? $santri->hitungDanSimpanNilaiAkhir();
        $nilaiAkhirFormatted = $nilaiAkhir !== null ? number_format((float) $nilaiAkhir, 2, ',', '.') : '—';
        $judulSertifikat = $program?->judulSertifikat() ?? 'Sertifikat';
        $penandatangans = $program
            ? $program->penandatangans()->where('is_active', true)->orderBy('urutan')->get()
            : collect();
        $showCertificateBack = (bool) ($program?->butuh_sertifikat_resmi ?? true);

        if (in_array($santri->status_pendaftar, ['lulus', 'sertifikat_diterbitkan'], true)
            || ($gelombang->program && ! $gelombang->program->butuh_kelulusan && $santri->status_pendaftar === 'lunas')) {
            $santri->update(['status_pendaftar' => 'sertifikat_diterbitkan']);
            $santri->refresh();
        }
        $gelombang = $santri->gelombang;

        if ($forBrowser) {
            $templateHalaman1 = CertificateTemplatePreview::publicRelativeUrl($gelombang->template_halaman_1)
                ?? $gelombang->templateHalaman1Path();
        } else {
            $templateHalaman1 = $gelombang->templateHalaman1Path();
        }

        return [
            'santri' => $santri,
            'gelombang' => $gelombang,
            'komponenTesRows' => $komponenTesRows,
            'nilaiAkhirFormatted' => $nilaiAkhirFormatted,
            'judulSertifikat' => $judulSertifikat,
            'penandatangans' => $penandatangans,
            'showCertificateBack' => $showCertificateBack,
            'templateHalaman1' => $templateHalaman1,
            'pageLayout' => SertifikatPageLayout::fromConfig(),
        ];
    }
}
