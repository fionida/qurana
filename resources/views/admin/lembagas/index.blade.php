@extends('layouts.admin')

@section('title', 'Kelola Lembaga')

@section('content')
@php
    $editData = [
        'id' => old('_lembaga_id', $editLembaga?->id),
        'nama' => old('nama', $editLembaga?->nama ?? ''),
        'is_active' => old('is_active', $editLembaga?->is_active ?? true),
    ];
@endphp

<div class="admin-page" x-data="{
    createOpen: {{ ($openCreate ?? false) ? 'true' : 'false' }},
    editOpen: {{ ($openEdit ?? false) ? 'true' : 'false' }},
    editForm: @js($editData),
    openEdit(lembaga) {
        this.editForm = lembaga;
        this.editOpen = true;
    }
}">
    <x-admin.page-header title="Kelola Lembaga" description="Atur daftar lembaga yang tampil di form pendaftaran">
        <x-slot:actions>
            <button type="button" @click="createOpen = true" class="admin-btn-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Lembaga
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-body">
        <div class="admin-card">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nama Lembaga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($lembagas as $lembaga)
                            <tr>
                                <td class="font-medium text-slate-900">{{ $lembaga->nama }}</td>
                                <td><x-admin.badge :status="$lembaga->is_active ? 'active' : 'inactive'" /></td>
                                <td>
                                    <div class="flex gap-3">
                                        <button type="button"
                                            @click="openEdit({ id: {{ $lembaga->id }}, nama: @js($lembaga->nama), is_active: {{ $lembaga->is_active ? 'true' : 'false' }} })"
                                            class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Edit</button>
                                        <form x-ref="deleteForm{{ $lembaga->id }}" action="{{ route('admin.lembagas.destroy', $lembaga) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                @click="askConfirm(@js('Yakin hapus lembaga '.$lembaga->nama.'?'), $refs.deleteForm{{ $lembaga->id }}, { title: 'Hapus Lembaga', confirmText: 'Ya, hapus', variant: 'danger' })"
                                                class="text-sm font-medium text-red-600 hover:text-red-700">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-16 text-center text-slate-400">Belum ada data lembaga</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($lembagas->hasPages())
                <div class="shrink-0 border-t border-slate-100 px-5 py-4">{{ $lembagas->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Modal Tambah --}}
    <x-admin.modal show="createOpen" title="Tambah Lembaga">
        <form action="{{ route('admin.lembagas.store') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="_modal" value="create">
            <div>
                <label class="admin-label">Nama Lembaga</label>
                <input type="text" name="nama" value="{{ old('_modal') === 'create' ? old('nama') : '' }}" required class="admin-input">
                @if (old('_modal') === 'create') @error('nama')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror @endif
            </div>
            <label class="inline-flex cursor-pointer items-center gap-2.5">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span class="text-sm text-slate-700">Aktif — tampil di form pendaftaran</span>
            </label>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="createOpen = false" class="admin-btn-secondary flex-1">Batal</button>
                <button type="submit" class="admin-btn-primary flex-1">Simpan</button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Modal Edit --}}
    <x-admin.modal show="editOpen" title="Edit Lembaga">
        <form :action="`/admin/lembagas/${editForm.id}`" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <input type="hidden" name="_modal" value="edit">
            <input type="hidden" name="_lembaga_id" :value="editForm.id">
            <div>
                <label class="admin-label">Nama Lembaga</label>
                <input type="text" name="nama" x-model="editForm.nama" required class="admin-input">
                @if (old('_modal') === 'edit') @error('nama')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror @endif
            </div>
            <label class="inline-flex cursor-pointer items-center gap-2.5">
                <input type="checkbox" name="is_active" value="1" x-model.boolean="editForm.is_active" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span class="text-sm text-slate-700">Aktif — tampil di form pendaftaran</span>
            </label>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="editOpen = false" class="admin-btn-secondary flex-1">Batal</button>
                <button type="submit" class="admin-btn-primary flex-1">Perbarui</button>
            </div>
        </form>
    </x-admin.modal>
</div>
@endsection
