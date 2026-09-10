<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Foto Peserta</title>
    @include('print.styles.photo-sheets')
</head>
<body>
    <div class="toolbar">
        <div>Total foto: <strong>{{ $santris->count() }}</strong> | Per halaman: <strong>{{ $perHalaman }}</strong></div>
        <div>
            <button type="button" class="primary" onclick="window.print()">Cetak Sekarang</button>
            <a href="{{ route('admin.photo-sheets.index') }}">Kembali</a>
        </div>
    </div>

    <div class="pages">
        @foreach ($santris->chunk($perHalaman) as $pageSantris)
            <section class="page">
                <div class="grid">
                    @foreach ($pageSantris as $santri)
                        <div class="item">
                            <div class="photo-wrap">
                                <img src="{{ asset('storage/'.$santri->pas_foto) }}" alt="{{ $santri->nama_lengkap }}">
                            </div>
                            <div class="name">{{ $santri->nama_lengkap }}</div>
                            <div class="meta">{{ $santri->nomor_pendaftaran }}</div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</body>
</html>
