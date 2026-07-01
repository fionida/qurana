@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="admin-page">
    <x-admin.page-header title="Dashboard" description="Rekap data pendaftaran santri Qurana" />

    <div class="admin-page-body admin-page-body--scroll">
        <div class="grid shrink-0 grid-cols-2 gap-4 lg:grid-cols-5">
            <x-admin.stat-card label="Total Santri" :value="$stats['total']" color="slate">
                <x-slot:icon><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg></x-slot:icon>
            </x-admin.stat-card>
            <x-admin.stat-card label="Pending" :value="$stats['pending']" color="amber">
                <x-slot:icon><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg></x-slot:icon>
            </x-admin.stat-card>
            <x-admin.stat-card label="Lunas" :value="$stats['lunas']" color="emerald">
                <x-slot:icon><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg></x-slot:icon>
            </x-admin.stat-card>
            <x-admin.stat-card label="Transfer" :value="$stats['transfer']" color="blue">
                <x-slot:icon><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.375M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" /></svg></x-slot:icon>
            </x-admin.stat-card>
            <x-admin.stat-card label="Bayar di Tempat" :value="$stats['bayar_ditempat']" color="violet">
                <x-slot:icon><svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg></x-slot:icon>
            </x-admin.stat-card>
        </div>

        @if ($lembagaStats->isNotEmpty())
        <div class="admin-card mt-4 shrink-0">
            <div class="admin-card-header">
                <h3 class="font-semibold text-slate-900">Rekap per Lembaga</h3>
            </div>
            <div class="admin-card-body pt-0">
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($lembagaStats as $item)
                        <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 px-4 py-3">
                            <span class="text-sm font-medium text-slate-700">{{ $item->lembaga }}</span>
                            <span class="rounded-lg bg-white px-2.5 py-1 text-sm font-bold text-emerald-600 shadow-sm">{{ $item->total }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="admin-card mt-4 shrink-0">
            <div class="admin-card-header">
                <h3 class="font-semibold text-slate-900">Filter Data</h3>
            </div>
            <div class="admin-card-body">
                <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-3 lg:grid-cols-4">
                    <div><label class="admin-label">Nama</label><input type="text" name="nama_lengkap" value="{{ $filters['nama_lengkap'] ?? '' }}" class="admin-input"></div>
                    <div><label class="admin-label">Lembaga</label><select name="lembaga" class="admin-select"><option value="">Semua</option>@foreach ($lembagaOptions as $lembaga)<option value="{{ $lembaga }}" @selected(($filters['lembaga'] ?? '') === $lembaga)>{{ $lembaga }}</option>@endforeach</select></div>
                    <div><label class="admin-label">Jenis Kelamin</label><select name="jenis_kelamin" class="admin-select"><option value="">Semua</option><option value="L" @selected(($filters['jenis_kelamin'] ?? '') === 'L')>Laki-laki</option><option value="P" @selected(($filters['jenis_kelamin'] ?? '') === 'P')>Perempuan</option></select></div>
                    <div><label class="admin-label">Metode Bayar</label><select name="metode_pembayaran" class="admin-select"><option value="">Semua</option><option value="transfer" @selected(($filters['metode_pembayaran'] ?? '') === 'transfer')>Transfer</option><option value="bayar_ditempat" @selected(($filters['metode_pembayaran'] ?? '') === 'bayar_ditempat')>Bayar di Tempat</option></select></div>
                    <div><label class="admin-label">Status Bayar</label><select name="status_pembayaran" class="admin-select"><option value="">Semua</option><option value="pending" @selected(($filters['status_pembayaran'] ?? '') === 'pending')>Pending</option><option value="lunas" @selected(($filters['status_pembayaran'] ?? '') === 'lunas')>Lunas</option></select></div>
                    <div><label class="admin-label">Tanggal Dari</label><input type="date" name="tanggal_dari" value="{{ $filters['tanggal_dari'] ?? '' }}" class="admin-input"></div>
                    <div><label class="admin-label">Tanggal Sampai</label><input type="date" name="tanggal_sampai" value="{{ $filters['tanggal_sampai'] ?? '' }}" class="admin-input"></div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="admin-btn-primary">Terapkan Filter</button>
                        <a href="{{ route('admin.dashboard') }}" class="admin-btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="admin-card admin-card-grow mt-4">
            <div class="admin-card-header">
                <h3 class="font-semibold text-slate-900">Data Pendidik Terbaru</h3>
                <a href="{{ route('admin.santris.index') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Lihat semua →</a>
            </div>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>No. Daftar</th>
                            <th>Nama</th>
                            <th>Lembaga</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($santris as $santri)
                            <tr>
                                <td><a href="{{ route('admin.santris.index', ['detail' => $santri->id]) }}" class="font-mono text-xs font-semibold text-emerald-600 hover:text-emerald-700">{{ $santri->nomor_pendaftaran }}</a></td>
                                <td class="font-medium text-slate-900">{{ $santri->nama_lengkap }}</td>
                                <td>{{ $santri->lembaga }}</td>
                                <td>{{ $santri->metode_pembayaran_label }}</td>
                                <td><x-admin.badge :status="$santri->status_pembayaran" /></td>
                                <td class="text-slate-500">{{ $santri->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-12 text-center text-slate-400">Belum ada data santri</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($santris->hasPages())
                <div class="border-t border-slate-100 px-5 py-4">{{ $santris->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
