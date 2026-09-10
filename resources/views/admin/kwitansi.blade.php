<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kwitansi {{ $santri->nomor_pendaftaran }}</title>
    @if ($backgroundPath ?? null)
        <style>
            @page { margin: 0; }
            body { margin: 0; font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111; }
            .page { position: relative; width: 148mm; min-height: 210mm; overflow: hidden; }
            .bg { position: absolute; top: 0; left: 0; width: 148mm; height: 210mm; z-index: 0; }
            .content { position: relative; z-index: 1; padding: 14mm 12mm; }
            table.info { width: 100%; margin-bottom: 16px; }
            table.info td { padding: 5px 0; vertical-align: top; }
            table.info td:first-child { width: 130px; color: #333; }
            .amount-box { border: 2px solid #065f46; padding: 10px; text-align: center; margin: 16px 0; background: rgb(255 255 255 / 0.85); }
            .amount-box .label { font-size: 11px; color: #666; }
            .amount-box .value { font-size: 20px; font-weight: bold; color: #065f46; }
            .kwitansi-status { text-align: center; color: #333; font-size: 11px; }
            .footer { margin-top: 24px; text-align: right; }
            .footer .sign { margin-top: 40px; border-top: 1px solid #333; display: inline-block; padding-top: 4px; min-width: 160px; text-align: center; }
        </style>
    @else
        @include('print.styles.kwitansi')
    @endif
</head>
<body>
@if ($backgroundPath ?? null)
    <div class="page">
        <img src="{{ $backgroundPath }}" class="bg" alt="">
        <div class="content">
            @include('admin.kwitansi._body')
        </div>
    </div>
@else
    @include('admin.kwitansi._body')
@endif
</body>
</html>
