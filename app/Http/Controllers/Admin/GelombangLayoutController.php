<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gelombang;
use App\Support\CertificateTemplatePreview;
use App\Support\SertifikatLayout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class GelombangLayoutController extends Controller
{
    public function edit(Gelombang $gelombang): View
    {
        abort_unless($gelombang->template_halaman_1, 404, 'Upload template halaman 1 terlebih dahulu.');

        $templatePath = $gelombang->templateHalaman1Path();
        $templateReady = $templatePath !== null;
        $templatePreviewSrc = CertificateTemplatePreview::srcForLayoutEditor($gelombang);

        return view('admin.gelombangs.layout.edit', [
            'gelombang' => $gelombang,
            'layoutH1' => $gelombang->layoutHalaman1(),
            'fieldLabels' => $this->fieldLabels(),
            'samplesH1' => $this->sampleHalaman1($gelombang),
            'templatePreviewSrc' => $templatePreviewSrc,
            'templateReady' => $templateReady,
            'templateIsPdf' => $gelombang->isTemplateHalaman1Pdf(),
        ]);
    }

    public function templateDepan(Gelombang $gelombang): Response
    {
        abort_unless($gelombang->template_halaman_1, 404);

        $path = $gelombang->templateHalaman1Path();

        abort_unless($path && is_file($path), 404, 'Berkas template tidak ditemukan di server. Unggah ulang template halaman 1.');

        $mime = match (strtolower(pathinfo($path, PATHINFO_EXTENSION))) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            default => mime_content_type($path) ?: 'application/octet-stream',
        };

        return response()->file($path, [
            'Content-Type' => $mime,
            'Cache-Control' => 'private, max-age=300',
        ]);
    }

    public function update(Request $request, Gelombang $gelombang): RedirectResponse
    {
        $validated = $request->validate([
            'halaman_1' => ['required', 'array'],
            'halaman_1.*.top_pct' => ['required', 'numeric', 'min:0', 'max:100'],
            'halaman_1.*.left_pct' => ['required', 'numeric', 'min:0', 'max:100'],
            'halaman_1.*.width_pct' => ['required', 'numeric', 'min:1', 'max:100'],
            'halaman_1.*.size' => ['required', 'numeric', 'min:6', 'max:24'],
            'halaman_1.*.align' => ['required', 'in:left,center'],
        ]);

        $halaman2 = $gelombang->overlay_layout['halaman_2'] ?? SertifikatLayout::normalizeHalaman2([]);

        $gelombang->update([
            'overlay_layout' => [
                'halaman_1' => $validated['halaman_1'],
                'halaman_2' => $halaman2,
            ],
        ]);

        return redirect()
            ->route('admin.gelombangs.layout.edit', $gelombang)
            ->with('success', 'Posisi overlay berhasil disimpan.');
    }

    public function reset(Gelombang $gelombang): RedirectResponse
    {
        $gelombang->update(['overlay_layout' => null]);

        return redirect()
            ->route('admin.gelombangs.layout.edit', $gelombang)
            ->with('success', 'Posisi dikembalikan ke default.');
    }

    /**
     * @return array<string, string>
     */
    private function fieldLabels(): array
    {
        return [
            'nomor_sertifikat' => 'Nomor sertifikat (NO. …)',
            'nama_lengkap' => 'Nama lengkap',
            'ttl' => 'Tempat, tanggal lahir',
            'niq' => 'N.I.Q',
            'alamat' => 'Alamat',
            'lembaga' => 'Asal lembaga',
            'tanggal_sertifikasi' => 'Tanggal sertifikasi',
            'tanggal_terbit' => 'Tanggal terbit (M + H)',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function sampleHalaman1(Gelombang $gelombang): array
    {
        return [
            'nomor_sertifikat' => 'NO. 0001/35.73/S.S/VII/2026',
            'nama_lengkap' => 'NAMA LENGKAP CONTOH',
            'ttl' => 'Malang, 18 Juli 2000',
            'niq' => '0001.35.73.180700',
            'alamat' => 'Jl. Contoh Alamat No. 1',
            'lembaga' => 'TPQ Contoh',
            'tanggal_sertifikasi' => $gelombang->tanggalSertifikasiLabel().'.',
            'tanggal_terbit' => $gelombang->kota_terbit.', '.$gelombang->tanggalTerbitMasehiLabel()."\n".$gelombang->tanggal_terbit_hijriyah,
        ];
    }
}
