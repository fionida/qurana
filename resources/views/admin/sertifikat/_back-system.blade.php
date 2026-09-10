<div class="cert-page cert-page-back">
    <h1 class="cert-back-title">{{ $judulSertifikat }}</h1>
    <p class="cert-back-subtitle">Lampiran Penilaian — Halaman Belakang Sertifikat</p>

    <table class="cert-back-meta">
        <tr>
            <td class="label">Nomor sertifikat</td>
            <td>NO. {{ $santri->nomor_sertifikat }}</td>
            <td class="label">Gelombang</td>
            <td>{{ $gelombang->nama }}</td>
        </tr>
        <tr>
            <td class="label">Nama peserta</td>
            <td>{{ $santri->nama_lengkap }}</td>
            <td class="label">N.I.Q</td>
            <td>{{ $santri->niq ?? '—' }}</td>
        </tr>
        <tr>
            <td class="label">Lembaga asal</td>
            <td colspan="3">{{ $santri->lembaga ?? '—' }}</td>
        </tr>
    </table>

    <table class="cert-back-table">
        <thead>
            <tr>
                <th class="num">No</th>
                <th>Komponen penilaian</th>
                <th class="nilai">Nilai maks.</th>
                <th class="nilai">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($komponenTesRows as $index => $row)
                <tr>
                    <td class="num">{{ $index + 1 }}</td>
                    <td>{{ $row['nama'] }}</td>
                    <td class="nilai">{{ $row['nilai_maksimal'] }}</td>
                    <td class="nilai">{{ $row['nilai'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align:center;color:#666;">Belum ada komponen tes pada gelombang ini.</td>
                </tr>
            @endforelse
            @if (($komponenTesRows ?? collect())->isNotEmpty())
                <tr class="total">
                    <td colspan="3" style="text-align:right;padding-right:12px;">Nilai akhir</td>
                    <td class="nilai">{{ $nilaiAkhirFormatted ?? '—' }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    @if (($penandatangans ?? collect())->isNotEmpty())
        @foreach ($penandatangans->chunk(3) as $signRow)
            <table class="cert-back-signatures">
                <tr>
                    @foreach ($signRow as $penandatangan)
                        <td>
                            <div class="cert-back-sign-space"></div>
                            <p class="cert-back-sign-name">{{ $penandatangan->nama }}</p>
                            <p class="cert-back-sign-role">{{ $penandatangan->jabatan }}</p>
                        </td>
                    @endforeach
                    @for ($i = $signRow->count(); $i < 3; $i++)
                        <td></td>
                    @endfor
                </tr>
            </table>
        @endforeach
        <p class="cert-back-footnote">Ruang di atas nama diperuntukkan tanda tangan basah saat penyerahan sertifikat.</p>
    @else
        <p class="cert-back-footnote">Penandatangan belum diatur pada program ini. Atur di menu Program → Penandatangan.</p>
    @endif
</div>
