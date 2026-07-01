@extends('layouts.admin')

@section('title', 'Database Pendidik')

@section('content')
<div class="admin-page" x-data="{
    detailOpen: {{ $detailPayload ? 'true' : 'false' }},
    detail: @js($detailPayload),
    openDetail(data) { this.detail = data; this.detailOpen = true; }
}">
    <x-admin.page-header title="Database Pendidik" description="Daftar lengkap pendidik yang sudah mendaftar">
        <x-slot:actions>
            <a href="{{ route('admin.payments.index') }}" class="admin-btn-secondary">Verifikasi Bayar</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-toolbar">
        <div class="admin-card">
            <div class="admin-card-body !py-4">
                <form method="GET" class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, nomor, WA, email..." class="admin-input flex-1">
                    <select name="lembaga" class="admin-select sm:w-48">
                        <option value="">Semua Lembaga</option>
                        @foreach ($lembagaOptions as $lembaga)
                            <option value="{{ $lembaga }}" @selected(request('lembaga') === $lembaga)>{{ $lembaga }}</option>
                        @endforeach
                    </select>
                    <select name="status_pembayaran" class="admin-select sm:w-40">
                        <option value="">Semua Status</option>
                        <option value="pending" @selected(request('status_pembayaran') === 'pending')>Pending</option>
                        <option value="lunas" @selected(request('status_pembayaran') === 'lunas')>Lunas</option>
                    </select>
                    <button type="submit" class="admin-btn-primary sm:w-auto">Cari</button>
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
                            <th>Nama Lengkap</th>
                            <th>Lembaga</th>
                            <th>JK</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th>Aksi</th>
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
                                <td class="font-semibold text-slate-900">{{ $santri->nama_lengkap }}</td>
                                <td>{{ $santri->lembaga }}</td>
                                <td>{{ $santri->jenis_kelamin_label }}</td>
                                <td class="text-xs">
                                    @if ($santri->no_wa)<div class="text-slate-700">{{ $santri->no_wa }}</div>@endif
                                    @if ($santri->email)<div class="text-slate-400">{{ $santri->email }}</div>@endif
                                </td>
                                <td><x-admin.badge :status="$santri->status_pembayaran" /></td>
                                <td>
                                    <div class="flex flex-wrap gap-2">
                                        <button type="button"
                                            @click="openDetail(@js(\App\Http\Controllers\Admin\SantriController::modalPayload($santri)))"
                                            class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Detail</button>
                                        @if ($santri->isLunas())
                                            <a href="{{ route('admin.payments.kwitansi', $santri) }}" target="_blank" class="text-sm font-medium text-blue-600 hover:text-blue-700">Kwitansi</a>
                                            <a href="{{ route('admin.certificates.print', $santri) }}" target="_blank" class="text-sm font-medium text-violet-600 hover:text-violet-700">Sertifikat</a>
                                        @endif
                                        <form x-ref="deleteForm{{ $santri->id }}" action="{{ route('admin.santris.destroy', $santri) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                @click="askConfirm(@js('Yakin hapus data '.$santri->nama_lengkap.'?'), $refs.deleteForm{{ $santri->id }}, { title: 'Hapus Santri', confirmText: 'Ya, hapus', variant: 'danger' })"
                                                class="text-sm font-medium text-red-600 hover:text-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-16 text-center"><p class="text-slate-400">Belum ada data santri</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($santris->hasPages())
                <div class="shrink-0 border-t border-slate-100 px-5 py-4">{{ $santris->links() }}</div>
            @endif
        </div>
    </div>

    <x-admin.modal show="detailOpen" title="Detail Santri" size="2xl">
        <template x-if="detail">
            <div>@include('admin.santris.partials.detail-modal-body')</div>
        </template>
    </x-admin.modal>
</div>

<x-photo-preview-modal />
@endsection
