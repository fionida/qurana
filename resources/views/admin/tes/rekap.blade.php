@extends('layouts.admin')

@section('title', 'Rekap Tes')

@section('content')
<div class="admin-page">
    <x-admin.page-header :title="'Rekap — '.$gelombang->nama" description="Ranking berdasarkan nilai akhir. Batas lulus: {{ $gelombang->nilai_lulus_minimal ?? '— (atur di gelombang)' }}">
        <x-slot:actions>
            <a href="{{ route('admin.tes.index', ['gelombang' => $gelombang->id]) }}" class="admin-btn-secondary">Input nilai</a>
            <a href="{{ route('admin.laporan.export.nilai', $gelombang) }}" class="admin-btn-secondary">Export CSV</a>
        </x-slot:actions>
    </x-admin.page-header>
    <div class="admin-page-body admin-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead><tr><th>Peringkat</th><th>Nama</th><th>No. daftar</th><th>Nilai akhir</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($ranking as $santri)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="font-medium">{{ $santri->nama_lengkap }}</td>
                            <td class="font-mono text-xs">{{ $santri->nomor_pendaftaran }}</td>
                            <td>{{ $santri->nilai_akhir }}</td>
                            <td>{{ $santri->statusPendaftarLabel() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-12 text-center text-slate-400">Belum ada nilai akhir.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
