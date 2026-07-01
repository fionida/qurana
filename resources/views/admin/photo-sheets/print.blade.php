<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Foto Peserta</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #0f172a;
            background: #f8fafc;
        }
        .toolbar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
        }
        .toolbar button,
        .toolbar a {
            border: 1px solid #cbd5e1;
            background: #ffffff;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 600;
            color: #0f172a;
            text-decoration: none;
            cursor: pointer;
        }
        .toolbar button.primary {
            background: #065f46;
            color: #ffffff;
            border-color: #065f46;
        }
        .pages {
            padding: 16px;
        }
        .page {
            width: 100%;
            min-height: calc(297mm - 24mm);
            background: #ffffff;
            border: 1px solid #e2e8f0;
            margin: 0 auto 16px;
            padding: 8mm;
            page-break-after: always;
        }
        .page:last-child {
            page-break-after: auto;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8mm 5mm;
        }
        .item {
            text-align: center;
        }
        .photo-wrap {
            width: 100%;
            aspect-ratio: 3 / 4;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 4px;
        }
        .photo-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .name {
            font-size: 10px;
            font-weight: 700;
            line-height: 1.3;
            text-transform: uppercase;
        }
        .meta {
            font-size: 9px;
            color: #475569;
        }

        @media print {
            body {
                background: #ffffff;
            }
            .toolbar {
                display: none;
            }
            .pages {
                padding: 0;
            }
            .page {
                border: 0;
                margin: 0;
                padding: 0;
                min-height: auto;
            }
        }
    </style>
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
