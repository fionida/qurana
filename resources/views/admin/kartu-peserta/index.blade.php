@extends('layouts.admin')

@section('title', 'Cetak Kartu Peserta')

@section('content')
<div class="admin-page">
    <x-admin.page-header title="Cetak Kartu Peserta" description="Cetak massal kartu peserta ke kertas A4 (default 12 kartu per halaman).">
        <x-slot:actions>
            <form method="GET" action="{{ route('admin.kartu-peserta.print') }}" target="_blank" class="flex flex-wrap items-end gap-2">
                <input type="hidden" name="search" value="{{ $filters['search'] ?? '' }}">
                <input type="hidden" name="program" value="{{ $filters['program'] ?? '' }}">
                <input type="hidden" name="gelombang" value="{{ $filters['gelombang'] ?? '' }}">
                <input type="hidden" name="lembaga" value="{{ $filters['lembaga'] ?? '' }}">
                <input type="hidden" name="status_pembayaran" value="{{ $filters['status_pembayaran'] ?? '' }}">
                <div>
                    <label class="admin-label">Kartu / Halaman</label>
                    <select name="per_halaman" class="admin-select !py-2 !text-xs">
                        <option value="8">8 (2×4)</option>
                        <option value="12" selected>12 (3×4)</option>
                        <option value="16">16 (4×4)</option>
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
                <form method="GET" class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 xl:items-end">
                    <div class="min-w-0 xl:col-span-2">
                        <label class="admin-label !mb-1 !normal-case !tracking-normal">Cari</label>
                        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Nama, nomor, lembaga…" class="admin-input w-full !py-2 text-sm">
                    </div>
                    <div class="min-w-0">
                        <label class="admin-label !mb-1 !normal-case !tracking-normal">Program</label>
                        <select name="program" class="admin-select w-full !py-2 text-sm" onchange="this.form.submit()">
                            <option value="">Semua</option>
                            @foreach ($programOptions as $program)
                                <option value="{{ $program->id }}" @selected(($filters['program'] ?? '') == $program->id)>{{ $program->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-0">
                        <label class="admin-label !mb-1 !normal-case !tracking-normal">Gelombang</label>
                        <select name="gelombang" class="admin-select w-full !py-2 text-sm">
                            <option value="">Semua</option>
                            @foreach ($gelombangOptions as $gelombang)
                                <option value="{{ $gelombang->id }}" @selected(($filters['gelombang'] ?? '') == $gelombang->id)>{{ $gelombang->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-0">
                        <label class="admin-label !mb-1 !normal-case !tracking-normal">Lembaga</label>
                        <select name="lembaga" class="admin-select w-full !py-2 text-sm">
                            <option value="">Semua</option>
                            @foreach ($lembagaOptions as $lembaga)
                                <option value="{{ $lembaga }}" @selected(($filters['lembaga'] ?? '') === $lembaga)>{{ $lembaga }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="min-w-0">
                        <label class="admin-label !mb-1 !normal-case !tracking-normal">Pembayaran</label>
                        <select name="status_pembayaran" class="admin-select w-full !py-2 text-sm">
                            <option value="">Semua</option>
                            <option value="pending" @selected(($filters['status_pembayaran'] ?? '') === 'pending')>Pending</option>
                            <option value="lunas" @selected(($filters['status_pembayaran'] ?? '') === 'lunas')>Lunas</option>
                        </select>
                    </div>
                    <div class="flex gap-2 xl:col-span-6">
                        <button type="submit" class="admin-btn-primary">Terapkan Filter</button>
                    </div>
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
                            <th>Program / Gelombang</th>
                            <th>Asal lembaga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($santris as $santri)
                            @php $fotoUrl = $santri->pas_foto ? asset('storage/'.$santri->pas_foto) : null; @endphp
                            <tr>
                                <td>
                                    @if ($fotoUrl)
                                        <button type="button" onclick="openPhotoModal('{{ $fotoUrl }}', '{{ addslashes($santri->nama_lengkap) }}')"
                                            class="block h-10 w-10 overflow-hidden rounded-full ring-2 ring-slate-200 transition hover:ring-emerald-400">
                                            <img src="{{ $fotoUrl }}" alt="" class="h-full w-full object-cover">
                                        </button>
                                    @else
                                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-slate-100 text-[10px] text-slate-400">N/A</span>
                                    @endif
                                </td>
                                <td><span class="font-mono text-xs text-slate-500">{{ $santri->nomor_pendaftaran }}</span></td>
                                <td class="font-medium text-slate-900">{{ $santri->nama_lengkap }}</td>
                                <td class="text-sm text-slate-600">
                                    {{ $santri->gelombang?->program?->nama ?? '—' }}
                                    <span class="block text-xs text-slate-400">{{ $santri->gelombang?->nama }}</span>
                                </td>
                                <td>{{ $santri->lembaga }}</td>
                                <td><x-admin.badge :status="$santri->status_pembayaran" /></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-16 text-center text-slate-400">Belum ada data peserta.</td></tr>
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
