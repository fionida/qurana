<?php

namespace App\Support;

use App\Models\Santri;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class SantriExcelExport
{
    /**
     * Stream a print-ready Excel file (SpreadsheetML .xls).
     * Works without PhpSpreadsheet / ext-zip on shared hosting.
     *
     * @param  iterable<int, Santri>  $santris
     * @param  list<string>  $filterSummary
     */
    public static function download(iterable $santris, array $filterSummary = []): StreamedResponse
    {
        $rows = [];
        foreach ($santris as $santri) {
            $rows[] = $santri;
        }

        $filename = 'daftar-peserta-'.now()->format('Ymd-His').'.xls';
        $generatedAt = now()->timezone(config('app.timezone'))->format('d/m/Y H:i');
        $filterText = $filterSummary === [] ? 'Semua data (tanpa filter)' : implode(' · ', $filterSummary);

        return response()->streamDownload(function () use ($rows, $generatedAt, $filterText) {
            echo self::workbookXml($rows, $generatedAt, $filterText);
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
        ]);
    }

    /**
     * @param  list<Santri>  $rows
     */
    private static function workbookXml(array $rows, string $generatedAt, string $filterText): string
    {
        $lastDataRow = 3 + max(count($rows), 1);
        $printArea = 'Peserta!R1C1:R'.$lastDataRow.'C11';

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<?mso-application progid="Excel.Sheet"?>'."\n";
        $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"'
            .' xmlns:o="urn:schemas-microsoft-com:office:office"'
            .' xmlns:x="urn:schemas-microsoft-com:office:excel"'
            .' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"'
            .' xmlns:html="http://www.w3.org/TR/REC-html40">'."\n";

        $xml .= '<DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">'
            .'<Title>Daftar Peserta Qurana</Title>'
            .'<Author>Qurana</Author>'
            .'<Created>'.now()->toAtomString().'</Created>'
            .'</DocumentProperties>'."\n";

        $xml .= '<Styles>'
            .'<Style ss:ID="Default" ss:Name="Normal"><Alignment ss:Vertical="Center"/><Font ss:FontName="Calibri" ss:Size="10"/></Style>'
            .'<Style ss:ID="Title"><Font ss:FontName="Calibri" ss:Size="16" ss:Bold="1" ss:Color="#064E3B"/><Alignment ss:Vertical="Center"/></Style>'
            .'<Style ss:ID="Meta"><Font ss:FontName="Calibri" ss:Size="9" ss:Color="#475569"/><Alignment ss:Vertical="Center" ss:WrapText="1"/></Style>'
            .'<Style ss:ID="Header"><Font ss:FontName="Calibri" ss:Size="9" ss:Bold="1" ss:Color="#064E3B"/><Interior ss:Color="#D1FAE5" ss:Pattern="Solid"/><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>'
            .'<Borders>'
            .'<Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#64748B"/>'
            .'<Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#64748B"/>'
            .'<Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#64748B"/>'
            .'<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#64748B"/>'
            .'</Borders></Style>'
            .'<Style ss:ID="Cell"><Font ss:FontName="Calibri" ss:Size="9"/><Alignment ss:Vertical="Center" ss:WrapText="1"/>'
            .'<Borders>'
            .'<Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#94A3B8"/>'
            .'<Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#94A3B8"/>'
            .'<Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#94A3B8"/>'
            .'<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#94A3B8"/>'
            .'</Borders></Style>'
            .'<Style ss:ID="CellCenter" ss:Parent="Cell"><Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/></Style>'
            .'<Style ss:ID="CellAlt" ss:Parent="Cell"><Interior ss:Color="#F8FAFC" ss:Pattern="Solid"/></Style>'
            .'<Style ss:ID="CellAltCenter" ss:Parent="CellCenter"><Interior ss:Color="#F8FAFC" ss:Pattern="Solid"/></Style>'
            .'</Styles>'."\n";

        $xml .= '<Worksheet ss:Name="Peserta">'."\n";
        $xml .= '<Names><NamedRange ss:Name="Print_Titles" ss:RefersTo="=Peserta!R3"/><NamedRange ss:Name="Print_Area" ss:RefersTo="='.$printArea.'"/></Names>'."\n";
        $xml .= '<Table ss:ExpandedColumnCount="11" ss:ExpandedRowCount="'.$lastDataRow.'" x:FullColumns="1" x:FullRows="1" ss:DefaultRowHeight="16">'."\n";

        $widths = [28, 88, 160, 28, 120, 110, 80, 92, 72, 88, 44];
        foreach ($widths as $width) {
            $xml .= '<Column ss:AutoFitWidth="0" ss:Width="'.$width.'"/>';
        }
        $xml .= "\n";

        // Title
        $xml .= '<Row ss:Height="24">'
            .'<Cell ss:MergeAcross="10" ss:StyleID="Title"><Data ss:Type="String">'.self::esc('Daftar Peserta — Qurana').'</Data></Cell>'
            .'</Row>'."\n";

        // Meta
        $xml .= '<Row ss:Height="30">'
            .'<Cell ss:MergeAcross="10" ss:StyleID="Meta"><Data ss:Type="String">'.self::esc('Dibuat: '.$generatedAt.' | Filter: '.$filterText.' | Total: '.count($rows).' peserta').'</Data></Cell>'
            .'</Row>'."\n";

        // Headers
        $headers = [
            'No', 'No. Daftar', 'Nama Lengkap', 'L/P', 'Asal Lembaga',
            'Program', 'Gelombang', 'No. WA', 'Pembayaran', 'Status', 'Nilai',
        ];
        $xml .= '<Row ss:Height="28">';
        foreach ($headers as $header) {
            $xml .= '<Cell ss:StyleID="Header"><Data ss:Type="String">'.self::esc($header).'</Data></Cell>';
        }
        $xml .= '</Row>'."\n";

        if ($rows === []) {
            $xml .= '<Row ss:Height="20">'
                .'<Cell ss:MergeAcross="10" ss:StyleID="CellCenter"><Data ss:Type="String">'.self::esc('Tidak ada data peserta untuk filter ini.').'</Data></Cell>'
                .'</Row>'."\n";
        } else {
            foreach ($rows as $i => $santri) {
                $alt = $i % 2 === 1;
                $cell = $alt ? 'CellAlt' : 'Cell';
                $center = $alt ? 'CellAltCenter' : 'CellCenter';
                $values = [
                    ['Number', (string) ($i + 1), $center],
                    ['String', (string) $santri->nomor_pendaftaran, $cell],
                    ['String', (string) $santri->nama_lengkap, $cell],
                    ['String', (string) ($santri->jenis_kelamin ?: '-'), $center],
                    ['String', (string) ($santri->lembaga ?: '-'), $cell],
                    ['String', (string) ($santri->gelombang?->program?->nama ?? '-'), $cell],
                    ['String', (string) ($santri->gelombang?->nama ?? '-'), $cell],
                    ['String', (string) ($santri->no_wa ?: '-'), $cell],
                    ['String', (string) $santri->status_pembayaran_label, $center],
                    ['String', (string) $santri->statusPendaftarLabel(), $cell],
                    ['String', $santri->nilai_akhir !== null ? (string) $santri->nilai_akhir : '-', $center],
                ];

                $xml .= '<Row ss:Height="18">';
                foreach ($values as [$type, $value, $style]) {
                    $xml .= '<Cell ss:StyleID="'.$style.'"><Data ss:Type="'.$type.'">'.self::esc($value).'</Data></Cell>';
                }
                $xml .= '</Row>'."\n";
            }
        }

        $xml .= '</Table>'."\n";
        $xml .= '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">'
            .'<PageSetup>'
            .'<Layout x:Orientation="Landscape" x:CenterHorizontal="1"/>'
            .'<Header x:Margin="0.2" x:Data="&amp;LQurana&amp;CDaftar Peserta&amp;R&amp;D"/>'
            .'<Footer x:Margin="0.2" x:Data="&amp;LDokumen internal panitia&amp;RHalaman &amp;P / &amp;N"/>'
            .'<PageMargins x:Bottom="0.4" x:Left="0.35" x:Right="0.35" x:Top="0.5"/>'
            .'</PageSetup>'
            .'<FitToPage/>'
            .'<Print>'
            .'<FitWidth>1</FitWidth>'
            .'<FitHeight>0</FitHeight>'
            .'<ValidPrinterInfo/>'
            .'<PaperSizeIndex>9</PaperSizeIndex>'
            .'<HorizontalResolution>600</HorizontalResolution>'
            .'<VerticalResolution>600</VerticalResolution>'
            .'</Print>'
            .'<Selected/>'
            .'<FreezePanes/>'
            .'<FrozenNoSplit/>'
            .'<SplitHorizontal>3</SplitHorizontal>'
            .'<TopRowBottomPane>3</TopRowBottomPane>'
            .'<ActivePane>2</ActivePane>'
            .'<ProtectObjects>False</ProtectObjects>'
            .'<ProtectScenarios>False</ProtectScenarios>'
            .'</WorksheetOptions>'."\n";
        $xml .= '</Worksheet>'."\n";

        // Full data sheet for archive / further editing
        $xml .= self::fullSheetXml($rows);

        $xml .= '</Workbook>';

        return $xml;
    }

    /**
     * @param  list<Santri>  $rows
     */
    private static function fullSheetXml(array $rows): string
    {
        $headers = [
            'No', 'No. Daftar', 'Nama Lengkap', 'L/P', 'Tempat Lahir', 'Tanggal Lahir',
            'Program', 'Gelombang', 'Asal Lembaga', 'Alamat', 'Desa/Kelurahan', 'Kecamatan',
            'Kota/Kabupaten', 'Provinsi', 'No. WhatsApp', 'Email', 'Metode Pembayaran',
            'Status Pembayaran', 'Status Pendaftar', 'Nilai Akhir', 'N.I.Q', 'Nomor Sertifikat',
            'Jumlah Bayar (Rp)', 'Voucher', 'Tgl Verifikasi', 'Tgl Daftar',
        ];

        $lastRow = 1 + max(count($rows), 1);
        $xml = '<Worksheet ss:Name="Lengkap">'."\n";
        $xml .= '<Table ss:ExpandedColumnCount="'.count($headers).'" ss:ExpandedRowCount="'.$lastRow.'" x:FullColumns="1" x:FullRows="1">'."\n";

        $xml .= '<Row ss:Height="22">';
        foreach ($headers as $header) {
            $xml .= '<Cell ss:StyleID="Header"><Data ss:Type="String">'.self::esc($header).'</Data></Cell>';
        }
        $xml .= '</Row>'."\n";

        foreach ($rows as $i => $santri) {
            $values = [
                ['Number', (string) ($i + 1)],
                ['String', (string) $santri->nomor_pendaftaran],
                ['String', (string) $santri->nama_lengkap],
                ['String', (string) ($santri->jenis_kelamin ?: '')],
                ['String', (string) ($santri->tempat_lahir ?: '')],
                ['String', $santri->tanggal_lahir?->format('d/m/Y') ?? ''],
                ['String', (string) ($santri->gelombang?->program?->nama ?? '')],
                ['String', (string) ($santri->gelombang?->nama ?? '')],
                ['String', (string) ($santri->lembaga ?: '')],
                ['String', (string) ($santri->alamat ?: '')],
                ['String', (string) ($santri->desa ?: '')],
                ['String', (string) ($santri->kecamatan ?: '')],
                ['String', (string) ($santri->kota_kab ?: '')],
                ['String', (string) ($santri->provinsi ?: '')],
                ['String', (string) ($santri->no_wa ?: '')],
                ['String', (string) ($santri->email ?: '')],
                ['String', (string) $santri->metode_pembayaran_label],
                ['String', (string) $santri->status_pembayaran_label],
                ['String', (string) $santri->statusPendaftarLabel()],
                ['String', $santri->nilai_akhir !== null ? (string) $santri->nilai_akhir : ''],
                ['String', (string) ($santri->niq ?? '')],
                ['String', (string) ($santri->nomor_sertifikat ?? '')],
                ['String', $santri->isLunas() ? number_format($santri->jumlahBayarEfektif(), 0, ',', '.') : ''],
                ['String', (string) ($santri->voucher?->kode ?? '')],
                ['String', $santri->verified_at?->format('d/m/Y H:i') ?? ''],
                ['String', $santri->created_at?->format('d/m/Y H:i') ?? ''],
            ];

            $xml .= '<Row>';
            foreach ($values as [$type, $value]) {
                $xml .= '<Cell ss:StyleID="Cell"><Data ss:Type="'.$type.'">'.self::esc($value).'</Data></Cell>';
            }
            $xml .= '</Row>'."\n";
        }

        $xml .= '</Table>'."\n";
        $xml .= '<WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">'
            .'<PageSetup><Layout x:Orientation="Landscape"/></PageSetup>'
            .'<Print><ValidPrinterInfo/><PaperSizeIndex>9</PaperSizeIndex></Print>'
            .'</WorksheetOptions>'."\n";
        $xml .= '</Worksheet>'."\n";

        return $xml;
    }

    private static function esc(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
