<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pratinjau Sertifikat — {{ $santri->nama_lengkap }}</title>
    @include('print.styles.sertifikat-preview', ['pageLayout' => $pageLayout])
    @include('print.styles.sertifikat-back', ['pageLayout' => $pageLayout])
</head>
<body>
    <div class="toolbar no-print">
        <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
        <a class="secondary" href="{{ route('admin.certificates.print', $santri) }}" target="_blank">Unduh PDF (DomPDF)</a>
        <a class="secondary" href="{{ route('admin.certificates.index') }}">Kembali</a>
    </div>

    <div class="pages">
        @include('admin.sertifikat._content')
    </div>
</body>
</html>
