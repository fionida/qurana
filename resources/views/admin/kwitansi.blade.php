<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Kwitansi {{ $santri->nomor_pendaftaran }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #065f46; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #065f46; font-size: 20px; }
        .header p { margin: 4px 0 0; color: #666; }
        .title { text-align: center; font-size: 16px; font-weight: bold; margin: 20px 0; text-decoration: underline; }
        table.info { width: 100%; margin-bottom: 20px; }
        table.info td { padding: 6px 0; vertical-align: top; }
        table.info td:first-child { width: 140px; color: #666; }
        .amount-box { border: 2px solid #065f46; padding: 12px; text-align: center; margin: 20px 0; }
        .amount-box .label { font-size: 11px; color: #666; }
        .amount-box .value { font-size: 22px; font-weight: bold; color: #065f46; }
        .footer { margin-top: 40px; text-align: right; }
        .footer .sign { margin-top: 60px; border-top: 1px solid #333; display: inline-block; padding-top: 4px; min-width: 180px; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>QURANA</h1>
        <p>Pendaftaran Santri Baru</p>
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
            <td>Lembaga</td>
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

    <p style="text-align: center; color: #666; font-size: 11px;">
        Status: <strong>LUNAS</strong> — Diverifikasi pada {{ $santri->verified_at?->format('d/m/Y H:i') }}
    </p>

    <div class="footer">
        <p>Admin Qurana</p>
        <div class="sign">{{ $santri->verifier?->name ?? 'Administrator' }}</div>
    </div>
</body>
</html>
