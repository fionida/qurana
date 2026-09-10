<div class="header">
    <h1>METODE PENDIDIKAN QURANA</h1>
    <p>Pendaftaran Calon Guru Pendidik</p>
</div>

<div class="title">KWITANSI PEMBAYARAN</div>

<table class="info">
    <tr>
        <td>No. Kwitansi</td>
        <td>: <strong>{{ $santri->nomor_pendaftaran }}</strong></td>
    </tr>
    <tr>
        <td>Tanggal</td>
        <td>: {{ $santri->verified_at?->format('d F Y') ?? now()->format('d F Y') }}</td>
    </tr>
    <tr>
        <td>Sudah terima dari</td>
        <td>: <strong>{{ $santri->nama_lengkap }}</strong></td>
    </tr>
    <tr>
        <td>Lembaga Asal</td>
        <td>: {{ $santri->lembaga }}</td>
    </tr>
    <tr>
        <td>Untuk pembayaran</td>
        <td>: Biaya Pendaftaran Santri Qurana</td>
    </tr>
    <tr>
        <td>Metode pembayaran</td>
        <td>: {{ $santri->metode_pembayaran_label }}</td>
    </tr>
</table>

<div class="amount-box">
    <div class="label">Jumlah</div>
    <div class="value">Rp {{ number_format($biaya, 0, ',', '.') }}</div>
</div>

<p class="kwitansi-status">
    Status: <strong>LUNAS</strong> — Diverifikasi pada {{ $santri->verified_at?->format('d/m/Y H:i') }}
</p>

<div class="footer">
    <p>Admin Qurana</p>
    <div class="sign">{{ $santri->verifier?->name ?? 'Administrator' }}</div>
</div>
