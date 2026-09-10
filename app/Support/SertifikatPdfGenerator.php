<?php

namespace App\Support;

use App\Models\Gelombang;
use App\Models\Penandatangan;
use App\Models\Santri;
use Illuminate\Support\Collection;
use setasign\Fpdi\Tcpdf\Fpdi;

class SertifikatPdfGenerator
{
    /**
     * @param  array<string, mixed>  $data  Output dari CertificateController::prepareCertificate()
     */
    public static function generate(array $data): string
    {
        /** @var Santri $santri */
        $santri = $data['santri'];
        /** @var Gelombang $gelombang */
        $gelombang = $data['gelombang'];
        $pageLayout = $data['pageLayout'] ?? SertifikatPageLayout::fromConfig();
        $pw = (float) $pageLayout['width_mm'];
        $ph = (float) $pageLayout['height_mm'];

        $templatePath = $data['templateHalaman1'] ?? $gelombang->templateHalaman1Path();
        if (! $templatePath || ! is_file($templatePath)) {
            throw new \RuntimeException('Template halaman 1 tidak ditemukan.');
        }

        $pdf = new Fpdi('L', 'mm', [$pw, $ph], true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(0, 0, 0);
        $pdf->SetAutoPageBreak(false, 0);

        self::renderHalamanDepan($pdf, $gelombang, $santri, $templatePath, $pw, $ph);

        if ($data['showCertificateBack'] ?? false) {
            self::renderHalamanBelakang(
                $pdf,
                $pw,
                $ph,
                $data['judulSertifikat'] ?? 'Sertifikat',
                $santri,
                $gelombang,
                $data['komponenTesRows'] ?? collect(),
                $data['nilaiAkhirFormatted'] ?? '—',
                $data['penandatangans'] ?? collect(),
            );
        }

        return $pdf->Output('', 'S');
    }

    private static function renderHalamanDepan(
        Fpdi $pdf,
        Gelombang $gelombang,
        Santri $santri,
        string $templatePath,
        float $pw,
        float $ph,
    ): void {
        $pdf->AddPage('L', [$pw, $ph]);

        if ($gelombang->isTemplateHalaman1Pdf()) {
            $pageCount = $pdf->setSourceFile($templatePath);
            if ($pageCount < 1) {
                throw new \RuntimeException('Template PDF tidak memiliki halaman.');
            }
            $tpl = $pdf->importPage(1);
            $pdf->useTemplate($tpl, 0, 0, $pw, $ph);
        } else {
            $pdf->Image($templatePath, 0, 0, $pw, $ph, '', '', '', false, 300);
        }

        $layout = $gelombang->layoutHalaman1();
        $designed = (bool) $gelombang->template_siap_cetak;

        if ($designed) {
            self::writeField($pdf, $layout['nomor_sertifikat'], 'NO. '.$santri->nomor_sertifikat, $pw, $ph);
            self::writeField($pdf, $layout['nama_lengkap'], (string) $santri->nama_lengkap, $pw, $ph);
            self::writeField($pdf, $layout['ttl'], (string) $santri->ttl, $pw, $ph);
            self::writeField($pdf, $layout['niq'], (string) $santri->niq, $pw, $ph);
            self::writeField($pdf, $layout['alamat'], (string) $santri->alamat_lengkap, $pw, $ph);
            self::writeField($pdf, $layout['lembaga'], (string) $santri->lembaga, $pw, $ph);
            self::writeField($pdf, $layout['tanggal_sertifikasi'], $gelombang->tanggalSertifikasiLabel().'.', $pw, $ph, bold: false);
            self::writeField(
                $pdf,
                $layout['tanggal_terbit'],
                $gelombang->kota_terbit.', '.$gelombang->tanggalTerbitMasehiLabel()."\n".$gelombang->tanggal_terbit_hijriyah,
                $pw,
                $ph,
                bold: false,
            );
        } else {
            $blockMm = SertifikatLayout::percentBoxToMm($layout['nama_lengkap'], $pw, $ph);
            $blockMm['top'] = max(0, $blockMm['top'] - 0.08 * $ph);
            $blockMm['width'] = $pw * 0.78;
            $blockMm['size'] = 11;
            $blockMm['align'] = 'left';
            $plain = "Sertifikat ini diberikan kepada :\n\n"
                .'Nama Lengkap : '.$santri->nama_lengkap."\n"
                .'Tempat, Tanggal Lahir : '.$santri->ttl."\n"
                .'N.I.Q : '.$santri->niq."\n"
                .'Alamat : '.$santri->alamat_lengkap."\n"
                .'Lembaga asal : '.$santri->lembaga;
            self::writeField($pdf, $blockMm, $plain, $pw, $ph, bold: false);

            self::writeField(
                $pdf,
                $layout['tanggal_sertifikasi'],
                'Telah dinyatakan LULUS … '.$gelombang->tanggalSertifikasiLabel().'.',
                $pw,
                $ph,
                bold: false,
            );
            self::writeField($pdf, $layout['nomor_sertifikat'], 'NO. '.$santri->nomor_sertifikat, $pw, $ph);
            self::writeField(
                $pdf,
                $layout['tanggal_terbit'],
                $gelombang->kota_terbit.', '.$gelombang->tanggalTerbitMasehiLabel()."\n".$gelombang->tanggal_terbit_hijriyah,
                $pw,
                $ph,
                bold: false,
            );
        }
    }

    /**
     * @param  array<string, mixed>  $box  Layout persen (atau mm jika sudah punya top/left/width)
     */
    private static function writeField(
        Fpdi $pdf,
        array $box,
        string $text,
        float $pw,
        float $ph,
        bool $bold = true,
    ): void {
        $mm = isset($box['top_pct'])
            ? SertifikatLayout::percentBoxToMm($box, $pw, $ph)
            : $box;

        $size = (float) ($mm['size'] ?? 11);
        $align = ($mm['align'] ?? 'left') === 'center' ? 'C' : 'L';
        $style = $bold ? 'B' : '';

        $pdf->SetFont('dejavuserif', $style, $size);
        $pdf->SetTextColor(17, 17, 17);
        $pdf->MultiCell(
            (float) $mm['width'],
            0,
            $text,
            0,
            $align,
            false,
            0,
            (float) $mm['left'],
            (float) $mm['top'],
            true,
            0,
            false,
            true,
            0,
            'T',
        );
    }

    /**
     * @param  Collection<int, array{nama: string, nilai_maksimal: string, nilai: string}>|iterable  $komponenTesRows
     * @param  Collection<int, Penandatangan>  $penandatangans
     */
    private static function renderHalamanBelakang(
        Fpdi $pdf,
        float $pw,
        float $ph,
        string $judulSertifikat,
        Santri $santri,
        Gelombang $gelombang,
        iterable $komponenTesRows,
        string $nilaiAkhirFormatted,
        Collection $penandatangans,
    ): void {
        $pdf->AddPage('L', [$pw, $ph]);
        $pdf->SetFont('dejavuserif', '', 10.5);
        $pdf->SetTextColor(17, 17, 17);

        $rows = $komponenTesRows instanceof Collection ? $komponenTesRows : collect($komponenTesRows);

        $tableRows = '';
        $index = 0;
        foreach ($rows as $row) {
            $index++;
            $tableRows .= '<tr>'
                .'<td style="text-align:center;width:8%;">'.$index.'</td>'
                .'<td>'.htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8').'</td>'
                .'<td style="text-align:center;width:14%;">'.htmlspecialchars($row['nilai_maksimal'], ENT_QUOTES, 'UTF-8').'</td>'
                .'<td style="text-align:center;width:14%;">'.htmlspecialchars($row['nilai'], ENT_QUOTES, 'UTF-8').'</td>'
                .'</tr>';
        }
        if ($tableRows === '') {
            $tableRows = '<tr><td colspan="4" style="text-align:center;color:#666;">Belum ada komponen tes pada gelombang ini.</td></tr>';
        } elseif ($rows->isNotEmpty()) {
            $tableRows .= '<tr style="font-weight:bold;background-color:#fafafa;">'
                .'<td colspan="3" style="text-align:right;padding-right:12px;">Nilai akhir</td>'
                .'<td style="text-align:center;">'.htmlspecialchars($nilaiAkhirFormatted, ENT_QUOTES, 'UTF-8').'</td>'
                .'</tr>';
        }

        $signBlocks = '';
        if ($penandatangans->isNotEmpty()) {
            foreach ($penandatangans->chunk(3) as $signRow) {
                $signBlocks .= '<table cellpadding="6" cellspacing="0" width="100%" style="margin-top:18px;"><tr>';
                foreach ($signRow as $penandatangan) {
                    $signBlocks .= '<td width="33%" style="text-align:center;vertical-align:bottom;">'
                        .'<div style="height:22mm;border-bottom:1px solid #333;margin:0 auto 6px;max-width:55mm;"></div>'
                        .'<p style="font-weight:bold;margin:0;font-size:10pt;">'.htmlspecialchars($penandatangan->nama, ENT_QUOTES, 'UTF-8').'</p>'
                        .'<p style="margin:2px 0 0;font-size:9.5pt;">'.htmlspecialchars($penandatangan->jabatan, ENT_QUOTES, 'UTF-8').'</p>'
                        .'</td>';
                }
                for ($i = $signRow->count(); $i < 3; $i++) {
                    $signBlocks .= '<td width="33%"></td>';
                }
                $signBlocks .= '</tr></table>';
            }
            $footnote = 'Ruang di atas nama diperuntukkan tanda tangan basah saat penyerahan sertifikat.';
        } else {
            $footnote = 'Penandatangan belum diatur pada program ini. Atur di menu Program → Penandatangan.';
        }

        $html = '
<div style="font-family:dejavuserif;font-size:10.5pt;color:#111;padding:14mm 16mm 12mm;">
<h1 style="text-align:center;font-size:13pt;font-weight:bold;text-transform:uppercase;margin:0 0 4px;">'
            .htmlspecialchars($judulSertifikat, ENT_QUOTES, 'UTF-8').'</h1>
<p style="text-align:center;font-size:10pt;margin:0 0 10px;color:#333;">Lampiran Penilaian — Halaman Belakang Sertifikat</p>
<table cellpadding="3" cellspacing="0" width="100%" style="font-size:10pt;margin-bottom:12px;">
<tr>
<td width="28%" style="font-weight:bold;">Nomor sertifikat</td>
<td>NO. '.htmlspecialchars((string) $santri->nomor_sertifikat, ENT_QUOTES, 'UTF-8').'</td>
<td width="28%" style="font-weight:bold;">Gelombang</td>
<td>'.htmlspecialchars($gelombang->nama, ENT_QUOTES, 'UTF-8').'</td>
</tr>
<tr>
<td style="font-weight:bold;">Nama peserta</td>
<td>'.htmlspecialchars((string) $santri->nama_lengkap, ENT_QUOTES, 'UTF-8').'</td>
<td style="font-weight:bold;">N.I.Q</td>
<td>'.htmlspecialchars((string) ($santri->niq ?? '—'), ENT_QUOTES, 'UTF-8').'</td>
</tr>
<tr>
<td style="font-weight:bold;">Lembaga asal</td>
<td colspan="3">'.htmlspecialchars((string) ($santri->lembaga ?? '—'), ENT_QUOTES, 'UTF-8').'</td>
</tr>
</table>
<table cellpadding="6" cellspacing="0" width="100%" style="border-collapse:collapse;font-size:10pt;margin:8px 0 14px;">
<thead>
<tr style="background-color:#f1f5f9;font-weight:bold;text-align:center;">
<th style="border:1px solid #333;width:8%;">No</th>
<th style="border:1px solid #333;">Komponen penilaian</th>
<th style="border:1px solid #333;width:14%;">Nilai maks.</th>
<th style="border:1px solid #333;width:14%;">Nilai</th>
</tr>
</thead>
<tbody>'.$tableRows.'</tbody>
</table>
'.$signBlocks.'
<p style="margin-top:10px;font-size:8.5pt;color:#555;text-align:center;">'.htmlspecialchars($footnote, ENT_QUOTES, 'UTF-8').'</p>
</div>';

        $pdf->writeHTML($html, true, false, true, false, '');
    }
}
