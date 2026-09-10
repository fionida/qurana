@extends('layouts.admin')

@section('title', 'Penandatangan Sertifikat')

@section('content')
<div class="admin-page" x-data="{
    editOpen: false,
    editForm: { id: null, nama: '', jabatan: '', urutan: 1, is_active: true },
    openEdit(row) {
        this.editForm = { ...row, is_active: !!row.is_active };
        this.editOpen = true;
    }
}">
    <x-admin.page-header :title="'Penandatangan — '.$program->nama" description="Nama & jabatan untuk kolom TTD basah di halaman belakang sertifikat (tanpa gambar tanda tangan).">
        <x-slot:actions>
            <a href="{{ route('admin.programs.index') }}" class="admin-btn-secondary">Kembali ke program</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-body grid gap-4 lg:grid-cols-2">
        <div class="admin-card">
            <div class="border-b border-slate-100 px-4 py-3">
                <p class="text-sm font-semibold text-slate-800">Tambah penandatangan</p>
            </div>
            <form action="{{ route('admin.programs.penandatangan.store', $program) }}" method="POST" class="space-y-4 p-4">
                @csrf
                <div>
                    <label class="admin-label">Nama</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan') }}" required placeholder="Ketua Panitia, Direktur, …" class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Urutan (kiri → kanan)</label>
                    <input type="number" name="urutan" min="1" value="{{ old('urutan', ($penandatangans->max('urutan') ?? 0) + 1) }}" class="admin-input">
                </div>
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-emerald-600">
                    Aktif (tampil di cetak)
                </label>
                <button type="submit" class="admin-btn-primary w-full">Simpan</button>
            </form>
        </div>

        <div class="admin-card">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Urut</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penandatangans as $row)
                            <tr>
                                <td class="font-mono text-sm">{{ $row->urutan }}</td>
                                <td class="font-medium">{{ $row->nama }}</td>
                                <td class="text-sm text-slate-600">{{ $row->jabatan }}</td>
                                <td><x-admin.badge :status="$row->is_active ? 'active' : 'inactive'" /></td>
                                <td>
                                    <div class="flex gap-2">
                                        <button type="button"
                                            @click="openEdit({ id: {{ $row->id }}, nama: @js($row->nama), jabatan: @js($row->jabatan), urutan: {{ $row->urutan }}, is_active: {{ $row->is_active ? 'true' : 'false' }} })"
                                            class="text-sm font-medium text-emerald-600">Edit</button>
                                        <form x-ref="del{{ $row->id }}" action="{{ route('admin.programs.penandatangan.destroy', [$program, $row]) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                @click="askConfirm(@js('Hapus penandatangan '.$row->nama.'?'), $refs.del{{ $row->id }}, { title: 'Hapus', variant: 'danger', confirmText: 'Hapus' })"
                                                class="text-sm font-medium text-red-600">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-12 text-center text-slate-400">Belum ada penandatangan untuk program ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-admin.modal show="editOpen" title="Edit penandatangan">
        <form :action="`/admin/programs/{{ $program->id }}/penandatangan/${editForm.id}`" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="admin-label">Nama</label>
                <input type="text" name="nama" x-model="editForm.nama" required class="admin-input">
            </div>
            <div>
                <label class="admin-label">Jabatan</label>
                <input type="text" name="jabatan" x-model="editForm.jabatan" required class="admin-input">
            </div>
            <div>
                <label class="admin-label">Urutan</label>
                <input type="number" name="urutan" min="1" x-model.number="editForm.urutan" class="admin-input">
            </div>
            <label class="inline-flex items-center gap-2 text-sm">
                <input type="checkbox" name="is_active" value="1" x-model.boolean="editForm.is_active" class="rounded border-slate-300 text-emerald-600">
                Aktif
            </label>
            <div class="flex gap-2">
                <button type="button" @click="editOpen = false" class="admin-btn-secondary flex-1">Batal</button>
                <button type="submit" class="admin-btn-primary flex-1">Simpan</button>
            </div>
        </form>
    </x-admin.modal>
</div>
@endsection
