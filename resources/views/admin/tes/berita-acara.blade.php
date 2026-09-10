<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Berita Acara Tes — {{ $gelombang->nama }}</title>
    @include('print.styles.tes-berita-acara')
</head>
<body>
    <h1>BERITA ACARA HASIL SELEKSI TES</h1>
    <p class="center">{{ $gelombang->nama }} · {{ now()->translatedFormat('d F Y') }}</p>
    <p>Pada hari ini telah dilaksanakan penilaian seleksi dengan rincian peserta <strong>lulus</strong> sebagai berikut:</p>
    <table>
        <thead><tr><th>No</th><th>No. Daftar</th><th>Nama</th><th>Nilai Akhir</th><th>Keterangan</th></tr></thead>
        <tbody>
            @forelse ($lulus as $santri)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $santri->nomor_pendaftaran }}</td>
                    <td>{{ $santri->nama_lengkap }}</td>
                    <td>{{ $santri->nilai_akhir }}</td>
                    <td>Lulus</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Belum ada peserta lulus.</td></tr>
            @endforelse
        </tbody>
    </table>
    <p style="margin-top:16px">Demikian berita acara ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
    <div class="sign">
        <div>Panitia tes<br><br><br>________________</div>
        <div>Mengetahui<br><br><br>________________</div>
    </div>
    <script>window.onload = () => window.print();</script>
</body>
</html>
