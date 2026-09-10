@php
    use App\Support\SertifikatLayout;

    $L = $gelombang->layoutHalaman1();
    $designed = $gelombang->template_siap_cetak;
@endphp

<div class="cert-page">
    <img src="{{ $templateHalaman1 }}" class="cert-bg" alt="">
    <div class="cert-overlay">
        @if ($designed)
            <div class="{{ SertifikatLayout::boxClasses($L['nomor_sertifikat']) }}" style="{{ SertifikatLayout::boxStyle($L['nomor_sertifikat']) }}">NO. {{ $santri->nomor_sertifikat }}</div>
            <div class="{{ SertifikatLayout::boxClasses($L['nama_lengkap']) }}" style="{{ SertifikatLayout::boxStyle($L['nama_lengkap']) }}">{{ $santri->nama_lengkap }}</div>
            <div class="{{ SertifikatLayout::boxClasses($L['ttl']) }}" style="{{ SertifikatLayout::boxStyle($L['ttl']) }}">{{ $santri->ttl }}</div>
            <div class="{{ SertifikatLayout::boxClasses($L['niq']) }}" style="{{ SertifikatLayout::boxStyle($L['niq']) }}">{{ $santri->niq }}</div>
            <div class="{{ SertifikatLayout::boxClasses($L['alamat']) }}" style="{{ SertifikatLayout::boxStyle($L['alamat']) }}">{{ $santri->alamat_lengkap }}</div>
            <div class="{{ SertifikatLayout::boxClasses($L['lembaga']) }}" style="{{ SertifikatLayout::boxStyle($L['lembaga']) }}">{{ $santri->lembaga }}</div>
            <div class="{{ SertifikatLayout::boxClasses($L['tanggal_sertifikasi'], 'field field-normal') }}" style="{{ SertifikatLayout::boxStyle($L['tanggal_sertifikasi']) }}">
                {{ $gelombang->tanggalSertifikasiLabel() }}.
            </div>
            <div class="{{ SertifikatLayout::boxClasses($L['tanggal_terbit'], 'field field-normal') }}" style="{{ SertifikatLayout::boxStyle($L['tanggal_terbit']) }}">
                {{ $gelombang->kota_terbit }}, {{ $gelombang->tanggalTerbitMasehiLabel() }}<br>
                {{ $gelombang->tanggal_terbit_hijriyah }}
            </div>
        @else
            {{-- mode template polos: satu blok teks --}}
            @php $block = $L['nama_lengkap']; @endphp
            <div class="field field-normal" style="top:{{ ($block['top_pct'] - 8) }}%;left:{{ $block['left_pct'] }}%;width:78%;font-size:11pt;">
                Sertifikat ini diberikan kepada :<br><br>
                Nama Lengkap : <strong>{{ $santri->nama_lengkap }}</strong><br>
                Tempat, Tanggal Lahir : <strong>{{ $santri->ttl }}</strong><br>
                N.I.Q : <strong>{{ $santri->niq }}</strong><br>
                Alamat : <strong>{{ $santri->alamat_lengkap }}</strong><br>
                Lembaga asal : <strong>{{ $santri->lembaga }}</strong>
            </div>
            <div class="{{ SertifikatLayout::boxClasses($L['tanggal_sertifikasi'], 'field field-normal') }}" style="{{ SertifikatLayout::boxStyle($L['tanggal_sertifikasi']) }}">
                Telah dinyatakan LULUS … {{ $gelombang->tanggalSertifikasiLabel() }}.
            </div>
            <div class="{{ SertifikatLayout::boxClasses($L['nomor_sertifikat']) }}" style="{{ SertifikatLayout::boxStyle($L['nomor_sertifikat']) }}">NO. {{ $santri->nomor_sertifikat }}</div>
            <div class="{{ SertifikatLayout::boxClasses($L['tanggal_terbit'], 'field field-normal') }}" style="{{ SertifikatLayout::boxStyle($L['tanggal_terbit']) }}">
                {{ $gelombang->kota_terbit }}, {{ $gelombang->tanggalTerbitMasehiLabel() }}<br>
                {{ $gelombang->tanggal_terbit_hijriyah }}
            </div>
        @endif
    </div>
</div>

@if ($showCertificateBack ?? false)
    @include('admin.sertifikat._back-system')
@endif
