<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Peserta — Qurana</title>
    @include('print.styles.santris-export')
</head>
<body>
    <div class="toolbar">
        <div>
            Total peserta: <strong>{{ $santris->count() }}</strong>
            · Siap cetak A4 landscape
        </div>
        <div class="toolbar-actions">
            <button type="button" class="primary" onclick="window.print()">Cetak / Simpan PDF</button>
            <a href="{{ route('admin.santris.export', request()->query()) }}">Unduh Excel</a>
            <a href="{{ route('admin.santris.index', request()->query()) }}">Kembali</a>
        </div>
    </div>

    <div class="sheet">
        <header class="doc-header">
            <div>
                <div class="doc-brand">Qurana · Portal Pendaftaran</div>
                <h1 class="doc-title">Daftar Peserta</h1>
            </div>
            <div class="doc-meta">
                <div>Dicetak: {{ now()->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</div>
                <div>Halaman dicetak dari filter aktif</div>
            </div>
        </header>

        @if ($filterSummary !== [])
            <div class="filters">
                Filter:
                {{ implode(' · ', $filterSummary) }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th class="col-no">No</th>
                    <th class="col-noreg">No. Daftar</th>
                    <th class="col-nama">Nama Lengkap</th>
                    <th class="col-jk">L/P</th>
                    <th class="col-lembaga">Asal Lembaga</th>
                    <th class="col-program">Program</th>
                    <th class="col-gelombang">Gelombang</th>
                    <th class="col-wa">No. WA</th>
                    <th class="col-bayar">Pembayaran</th>
                    <th class="col-status">Status</th>
                    <th class="col-nilai">Nilai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($santris as $i => $santri)
                    <tr>
                        <td class="col-no">{{ $i + 1 }}</td>
                        <td class="col-noreg">{{ $santri->nomor_pendaftaran }}</td>
                        <td class="col-nama">{{ $santri->nama_lengkap }}</td>
                        <td class="col-jk">{{ $santri->jenis_kelamin ?: '-' }}</td>
                        <td class="col-lembaga">{{ $santri->lembaga }}</td>
                        <td class="col-program">{{ $santri->gelombang?->program?->nama ?? '-' }}</td>
                        <td class="col-gelombang">{{ $santri->gelombang?->nama ?? '-' }}</td>
                        <td class="col-wa">{{ $santri->no_wa }}</td>
                        <td class="col-bayar">{{ $santri->status_pembayaran_label }}</td>
                        <td class="col-status">{{ $santri->statusPendaftarLabel() }}</td>
                        <td class="col-nilai">{{ $santri->nilai_akhir ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align:center;padding:24px;color:#64748b;">Tidak ada data peserta untuk filter ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer-note">
            <span>Dokumen internal panitia Qurana.</span>
            <span>{{ $santris->count() }} baris</span>
        </div>
    </div>
</body>
</html>
