<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kartu Peserta — {{ $santri->nomor_pendaftaran }}</title>
    @if ($backgroundPath ?? null)
        <style>
            body { font-family: DejaVu Sans, sans-serif; margin: 0; padding: 24px; background: #f1f5f9; }
            .sheet { max-width: 420px; margin: 0 auto; position: relative; }
            .bg { width: 100%; height: auto; display: block; border-radius: 12px; }
            .overlay { position: absolute; inset: 0; padding: 18% 8% 12%; display: flex; flex-direction: column; justify-content: center; }
            .body { display: flex; gap: 12px; align-items: flex-start; }
            .photo { width: 88px; height: 112px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1; }
            .meta { flex: 1; font-size: 11px; line-height: 1.45; color: #0f172a; }
            .meta strong { font-size: 13px; display: block; margin-bottom: 6px; }
            .meta-tes { margin-top: 6px; font-size: 10px; }
        </style>
    @else
        @include('print.styles.kartu-peserta')
    @endif
</head>
<body onload="window.print()">
@if ($backgroundPath ?? null)
    <div class="sheet">
        <img src="{{ $backgroundPath }}" alt="" class="bg">
        <div class="overlay">
            <div class="body">
                <img src="{{ asset('storage/'.$santri->pas_foto) }}" alt="" class="photo">
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
    </div>
@else
    <div class="card">
        <div class="head">
            <div class="head-title">KARTU PESERTA</div>
            <div class="head-sub">{{ $santri->gelombang?->nama ?? 'Sertifikasi Guru QFI' }}</div>
        </div>
        <div class="body">
            <img src="{{ asset('storage/'.$santri->pas_foto) }}" alt="" class="photo">
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
        <div class="foot">Bawa kartu ini saat tes / verifikasi · Qurana PMB</div>
    </div>
@endif
</body>
</html>
