@extends('layouts.admin')

@section('title', 'Database Pengajar')

@section('content')
@php
    $editData = [
        'id' => old('_pengajar_id', $editPengajar?->id),
        'nama_lengkap' => old('nama_lengkap', $editPengajar?->nama_lengkap ?? ''),
        'nip' => old('nip', $editPengajar?->nip ?? ''),
        'jenis_kelamin' => old('jenis_kelamin', $editPengajar?->jenis_kelamin ?? ''),
        'no_wa' => old('no_wa', $editPengajar?->no_wa ?? ''),
        'email' => old('email', $editPengajar?->email ?? ''),
        'lembaga' => old('lembaga', $editPengajar?->lembaga ?? ''),
        'is_active' => old('is_active', $editPengajar?->is_active ?? true),
    ];
@endphp

<div class="admin-page" x-data="{
    createOpen: {{ ($openCreate ?? false) ? 'true' : 'false' }},
    editOpen: {{ ($openEdit ?? false) ? 'true' : 'false' }},
    editForm: @js($editData),
    openEdit(pengajar) {
        this.editForm = pengajar;
        this.editOpen = true;
    }
}">
    <x-admin.page-header title="Database Pengajar" description="Kelola data pengajar Qurana">
        <x-slot:actions>
            <button type="button" @click="createOpen = true" class="admin-btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Pengajar
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-toolbar">
        <div class="admin-card">
            <div class="admin-card-body !py-4">
                <form method="GET" class="flex flex-col gap-3 sm:flex-row">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, email, WA..." class="admin-input flex-1">
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
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Lembaga</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajars as $pengajar)
                            <tr>
                                <td>
                                    <div class="font-medium text-slate-900">{{ $pengajar->nama_lengkap }}</div>
                                    @if ($pengajar->jenis_kelamin_label)
                                        <div class="text-xs text-slate-400">{{ $pengajar->jenis_kelamin_label }}</div>
                                    @endif
                                </td>
                                <td class="font-mono text-xs text-slate-500">{{ $pengajar->nip ?? '—' }}</td>
                                <td>{{ $pengajar->lembaga ?? '—' }}</td>
                                <td class="text-xs">
                                    @if ($pengajar->no_wa)<div>{{ $pengajar->no_wa }}</div>@endif
                                    @if ($pengajar->email)<div class="text-slate-400">{{ $pengajar->email }}</div>@endif
                                </td>
                                <td><x-admin.badge :status="$pengajar->is_active ? 'active' : 'inactive'" /></td>
                                <td>
                                    <div class="flex gap-3">
                                        <button type="button"
                                            @click="openEdit(@js([
                                                'id' => $pengajar->id,
                                                'nama_lengkap' => $pengajar->nama_lengkap,
                                                'nip' => $pengajar->nip ?? '',
                                                'jenis_kelamin' => $pengajar->jenis_kelamin ?? '',
                                                'no_wa' => $pengajar->no_wa ?? '',
                                                'email' => $pengajar->email ?? '',
                                                'lembaga' => $pengajar->lembaga ?? '',
                                                'is_active' => $pengajar->is_active,
                                            ]))"
                                            class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Edit</button>
                                        <form x-ref="deleteForm{{ $pengajar->id }}" action="{{ route('admin.pengajars.destroy', $pengajar) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                @click="askConfirm(@js('Yakin hapus pengajar '.$pengajar->nama_lengkap.'?'), $refs.deleteForm{{ $pengajar->id }}, { title: 'Hapus Pengajar', confirmText: 'Ya, hapus', variant: 'danger' })"
                                                class="text-sm font-medium text-red-600 hover:text-red-700">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-16 text-center text-slate-400">Belum ada data pengajar</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($pengajars->hasPages())
                <div class="shrink-0 border-t border-slate-100 px-5 py-4">{{ $pengajars->links() }}</div>
            @endif
        </div>
    </div>

    <x-admin.modal show="createOpen" title="Tambah Pengajar" size="lg">
        <form action="{{ route('admin.pengajars.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="_modal" value="create">
            @include('admin.pengajars.partials.form-fields', ['prefix' => 'create'])
            <div class="flex gap-3 pt-2">
                <button type="button" @click="createOpen = false" class="admin-btn-secondary flex-1">Batal</button>
                <button type="submit" class="admin-btn-primary flex-1">Simpan</button>
            </div>
        </form>
    </x-admin.modal>

    <x-admin.modal show="editOpen" title="Edit Pengajar" size="lg">
        <form :action="`/admin/pengajars/${editForm.id}`" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <input type="hidden" name="_modal" value="edit">
            <input type="hidden" name="_pengajar_id" :value="editForm.id">
            <div>
                <label class="admin-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" x-model="editForm.nama_lengkap" required class="admin-input">
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">NIP</label>
                    <input type="text" name="nip" x-model="editForm.nip" class="admin-input font-mono">
                </div>
                <div>
                    <label class="admin-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" x-model="editForm.jenis_kelamin" class="admin-select">
                        <option value="">— Pilih —</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">No. WhatsApp</label>
                    <input type="text" name="no_wa" x-model="editForm.no_wa" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Email</label>
                    <input type="email" name="email" x-model="editForm.email" class="admin-input">
                </div>
            </div>
            <div>
                <label class="admin-label">Lembaga</label>
                <select name="lembaga" x-model="editForm.lembaga" class="admin-select">
                    <option value="">— Pilih Lembaga —</option>
                    @foreach ($lembagaOptions as $lembaga)
                        <option value="{{ $lembaga }}">{{ $lembaga }}</option>
                    @endforeach
                </select>
            </div>
            <label class="inline-flex cursor-pointer items-center gap-2.5">
                <input type="checkbox" name="is_active" value="1" x-model.boolean="editForm.is_active" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span class="text-sm text-slate-700">Aktif</span>
            </label>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="editOpen = false" class="admin-btn-secondary flex-1">Batal</button>
                <button type="submit" class="admin-btn-primary flex-1">Perbarui</button>
            </div>
        </form>
    </x-admin.modal>
</div>
@endsection
