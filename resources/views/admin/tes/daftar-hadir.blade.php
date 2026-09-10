<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Daftar Hadir Tes — {{ $gelombang->nama }}</title>
    @include('print.styles.tes-daftar-hadir')
</head>
<body>
    <h1>DAFTAR HADIR TES</h1>
    <p class="meta">{{ $gelombang->nama }}<br>
        @if ($gelombang->jadwal_tes_mulai)
            {{ $gelombang->jadwal_tes_mulai->translatedFormat('d F Y') }}
            @if ($gelombang->jadwal_tes_selesai && ! $gelombang->jadwal_tes_mulai->isSameDay($gelombang->jadwal_tes_selesai))
                – {{ $gelombang->jadwal_tes_selesai->translatedFormat('d F Y') }}
            @endif
            @if ($gelombang->lokasi_tes) · {{ $gelombang->lokasi_tes }} @endif
        @endif
    </p>
    <table>
        <thead>
            <tr><th>No</th><th>No. Daftar</th><th>Nama</th><th>Asal lembaga</th><th>Tanda tangan</th></tr>
        </thead>
        <tbody>
            @foreach ($santris as $santri)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $santri->nomor_pendaftaran }}</td>
                    <td>{{ $santri->nama_lengkap }}</td>
                    <td>{{ $santri->lembaga }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="sign">
        <div>Panitia tes<br><br><br>________________</div>
        <div>Mengetahui<br><br><br>________________</div>
    </div>
    <script>window.onload = () => window.print();</script>
</body>
</html>
