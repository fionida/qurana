<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Kartu Peserta Massal</title>
    @include('print.styles.kartu-peserta-mass', ['gridCols' => $gridCols])
</head>
<body>
    <div class="toolbar">
        <div>
            Total kartu: <strong>{{ $santris->count() }}</strong>
            · {{ $perHalaman }} kartu / halaman A4
            · Grid {{ $gridCols }} kolom
        </div>
        <div class="toolbar-actions">
            <button type="button" class="primary" onclick="window.print()">Cetak Sekarang</button>
            <a href="{{ route('admin.kartu-peserta.index') }}">Kembali</a>
        </div>
    </div>

    <div class="pages">
        @foreach ($santris->chunk($perHalaman) as $pageSantris)
            <section class="page">
                <div class="grid">
                    @foreach ($pageSantris as $santri)
                        @php
                            $fotoUrl = $santri->pas_foto ? asset('storage/'.$santri->pas_foto) : null;
                            $gelombangLabel = $santri->gelombang?->nama ?? ($santri->gelombang?->program?->nama ?? 'Qurana');
                        @endphp
                        @if ($backgroundUrl)
                            <article class="card has-bg">
                                <img src="{{ $backgroundUrl }}" alt="" class="bg">
                                <div class="card-inner">
                                    <div class="body" style="padding:0;">
                                        @if ($fotoUrl)
                                            <img src="{{ $fotoUrl }}" alt="" class="photo">
                                        @else
                                            <div class="photo photo-missing">Tanpa foto</div>
                                        @endif
                                        <div class="meta">
                                            <strong>{{ $santri->nama_lengkap }}</strong>
                                            <div>No. {{ $santri->nomor_pendaftaran }}</div>
                                            <div>{{ $santri->ttl }}</div>
                                            <div>{{ $santri->lembaga }}</div>
                                            @if ($santri->gelombang?->jadwal_tes_mulai)
                                                <div class="meta-tes">Tes: {{ $santri->gelombang->jadwal_tes_mulai->format('d/m/Y') }}@if($santri->gelombang->lokasi_tes)<br>{{ $santri->gelombang->lokasi_tes }}@endif</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @else
                            <article class="card">
                                <div class="head">
                                    <div class="head-title">KARTU PESERTA</div>
                                    <div class="head-sub">{{ $gelombangLabel }}</div>
                                </div>
                                <div class="body">
                                    @if ($fotoUrl)
                                        <img src="{{ $fotoUrl }}" alt="" class="photo">
                                    @else
                                        <div class="photo photo-missing">Tanpa foto</div>
                                    @endif
                                    <div class="meta">
                                        <strong>{{ $santri->nama_lengkap }}</strong>
                                        <div>No. {{ $santri->nomor_pendaftaran }}</div>
                                        <div>{{ $santri->ttl }}</div>
                                        <div>{{ $santri->lembaga }}</div>
                                        @if ($santri->gelombang?->jadwal_tes_mulai)
                                            <div class="meta-tes">Tes: {{ $santri->gelombang->jadwal_tes_mulai->format('d/m/Y') }}@if($santri->gelombang->lokasi_tes)<br>{{ $santri->gelombang->lokasi_tes }}@endif</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="foot">Bawa kartu ini saat tes / verifikasi · Qurana</div>
                            </article>
                        @endif
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</body>
</html>
