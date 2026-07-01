<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sertifikat {{ $santri->nomor_pendaftaran }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 30px;
            color: #1a1a1a;
        }
        .border-frame {
            border: 8px double #065f46;
            padding: 24px;
            height: 520px;
            position: relative;
        }
        .inner-border {
            border: 2px solid #d97706;
            padding: 20px;
            height: 100%;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            color: #065f46;
            letter-spacing: 2px;
        }
        .header p {
            margin: 4px 0 0;
            color: #666;
            font-size: 12px;
        }
        .title {
            font-size: 22px;
            font-weight: bold;
            margin: 24px 0 8px;
            color: #065f46;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .subtitle {
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }
        .photo {
            width: 90px;
            height: 110px;
            object-fit: cover;
            border: 2px solid #065f46;
            margin: 0 auto 16px;
        }
        .name {
            font-size: 26px;
            font-weight: bold;
            color: #111;
            margin: 8px 0;
        }
        .details {
            font-size: 12px;
            line-height: 1.8;
            margin: 16px auto;
            max-width: 520px;
        }
        .nomor {
            font-size: 11px;
            color: #666;
            margin-top: 20px;
        }
        .footer {
            position: absolute;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
        }
        .footer .date {
            font-size: 12px;
            margin-bottom: 40px;
        }
        .footer .sign {
            display: inline-block;
            border-top: 1px solid #333;
            padding-top: 4px;
            min-width: 200px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="border-frame">
        <div class="inner-border">
            <div class="header">
                <h1>QURANA</h1>
                <p>Yayasan Pendidikan Islam</p>
            </div>

            <div class="title">Sertifikat Pendaftaran</div>
            <div class="subtitle">Diberikan kepada:</div>

            @if ($fotoPath)
                <img src="{{ $fotoPath }}" class="photo" alt="Foto">
            @endif

            <div class="name">{{ $santri->nama_lengkap }}</div>

            <div class="details">
                Tempat, Tanggal Lahir: {{ $santri->ttl }}<br>
                Lembaga: {{ $santri->lembaga }}<br>
                Jenis Kelamin: {{ $santri->jenis_kelamin_label }}
            </div>

            <p style="font-size: 12px; max-width: 520px; margin: 0 auto; line-height: 1.6;">
                Telah terdaftar sebagai calon santri Qurana dan dinyatakan <strong>LUNAS</strong>
                dalam pembayaran biaya pendaftaran.
            </p>

            <div class="nomor">Nomor: {{ $santri->nomor_pendaftaran }}</div>

            <div class="footer">
                <div class="date">{{ $santri->verified_at?->translatedFormat('d F Y') ?? now()->translatedFormat('d F Y') }}</div>
                <div class="sign">{{ $santri->verifier?->name ?? 'Administrator' }}<br><span style="font-size:10px;color:#666;">Kepala Administrasi</span></div>
            </div>
        </div>
    </div>
</body>
</html>
