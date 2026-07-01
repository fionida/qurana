@extends('layouts.admin')

@section('title', 'Cetak Foto Peserta')

@section('content')
<div class="admin-page">
    <x-admin.page-header title="Cetak Foto Peserta" description="Cetak massal pas foto peserta ke layout A4.">
        <x-slot:actions>
            <form method="GET" action="{{ route('admin.photo-sheets.print') }}" target="_blank" class="flex flex-wrap items-end gap-2">
                <input type="hidden" name="search" value="{{ $filters['search'] ?? '' }}">
                <input type="hidden" name="lembaga" value="{{ $filters['lembaga'] ?? '' }}">
                <input type="hidden" name="status_pembayaran" value="{{ $filters['status_pembayaran'] ?? '' }}">
                <div>
                    <label class="admin-label">Foto / Halaman</label>
                    <select name="per_halaman" class="admin-select !py-2 !text-xs">
                        <option value="8">8</option>
                        <option value="12" selected>12</option>
                        <option value="16">16</option>
                    </select>
                </div>
                <div>
                    <label class="admin-label">Jumlah Peserta</label>
                    <input type="number" name="jumlah" min="1" max="500" placeholder="Semua" class="admin-input !py-2 !text-xs w-28">
                </div>
                <button type="submit" class="admin-btn-primary !py-2 !text-xs">Buka Halaman Cetak</button>
            </form>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-toolbar">
        <div class="admin-card">
            <div class="admin-card-body !py-4">
                <form method="GET" class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari nama, nomor, atau lembaga..." class="admin-input flex-1">
                    <select name="lembaga" class="admin-select sm:w-52">
                        <option value="">Semua Lembaga</option>
                        @foreach ($lembagaOptions as $lembaga)
                            <option value="{{ $lembaga }}" @selected(($filters['lembaga'] ?? '') === $lembaga)>{{ $lembaga }}</option>
                        @endforeach
                    </select>
                    <select name="status_pembayaran" class="admin-select sm:w-44">
                        <option value="">Semua Status</option>
                        <option value="pending" @selected(($filters['status_pembayaran'] ?? '') === 'pending')>Pending</option>
                        <option value="lunas" @selected(($filters['status_pembayaran'] ?? '') === 'lunas')>Lunas</option>
                    </select>
                    <button type="submit" class="admin-btn-primary">Terapkan</button>
                </form>
            </div>
        </div>
    </div>

    <div class="admin-page-body">
        <div class="admin-card">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>No. Daftar</th>
                            <th>Nama</th>
                            <th>Lembaga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($santris as $santri)
                            @php $fotoUrl = asset('storage/'.$santri->pas_foto); @endphp
                            <tr>
                                <td>
                                    <button type="button" onclick="openPhotoModal('{{ $fotoUrl }}', '{{ addslashes($santri->nama_lengkap) }}')"
                                        class="block h-10 w-10 overflow-hidden rounded-full ring-2 ring-slate-200 transition hover:ring-emerald-400">
                                        <img src="{{ $fotoUrl }}" alt="" class="h-full w-full object-cover">
                                    </button>
                                </td>
                                <td><span class="font-mono text-xs text-slate-500">{{ $santri->nomor_pendaftaran }}</span></td>
                                <td class="font-medium text-slate-900">{{ $santri->nama_lengkap }}</td>
                                <td>{{ $santri->lembaga }}</td>
                                <td><x-admin.badge :status="$santri->status_pembayaran" /></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-16 text-center text-slate-400">Belum ada data foto peserta.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($santris->hasPages())
                <div class="shrink-0 border-t border-slate-100 px-5 py-4">{{ $santris->links() }}</div>
            @endif
        </div>
    </div>
</div>

<x-photo-preview-modal />
@endsection
