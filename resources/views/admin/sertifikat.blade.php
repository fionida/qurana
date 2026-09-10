<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sertifikat {{ $santri->nomor_sertifikat }}</title>
    @include('print.styles.sertifikat-pages', ['pageLayout' => $pageLayout])
    @include('print.styles.sertifikat-back', ['pageLayout' => $pageLayout])
</head>
<body>
@include('admin.sertifikat._content')
</body>
</html>
