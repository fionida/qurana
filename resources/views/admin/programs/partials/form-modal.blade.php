@php
    $isCreate = $mode === 'create';
    $title = $isCreate ? 'Tambah program' : 'Edit program';
@endphp

<x-admin.modal :show="$show" :title="$title">
    <form
        @if ($isCreate) action="{{ route('admin.programs.store') }}" @else :action="`/admin/programs/${editForm.id}`" @endif
        method="POST"
        class="max-h-[70vh] space-y-4 overflow-y-auto pr-1"
    >
        @csrf
        @unless ($isCreate) @method('PUT') @endunless
        <input type="hidden" name="_modal" value="{{ $mode }}">
        @unless ($isCreate) <input type="hidden" name="_program_id" :value="editForm.id"> @endunless

        <div>
            <label class="admin-label">Nama program</label>
            @if ($isCreate)
                <input type="text" name="nama" value="{{ old('_modal') === 'create' ? old('nama') : '' }}" required class="admin-input">
            @else
                <input type="text" name="nama" x-model="editForm.nama" required class="admin-input">
            @endif
        </div>

        <div>
            <label class="admin-label">Judul sertifikat (halaman belakang)</label>
            @if ($isCreate)
                <input type="text" name="judul_sertifikat" value="{{ old('_modal') === 'create' ? old('judul_sertifikat') : '' }}" placeholder="Kosongkan = sama dengan nama program" class="admin-input">
            @else
                <input type="text" name="judul_sertifikat" x-model="editForm.judul_sertifikat" placeholder="Kosongkan = sama dengan nama program" class="admin-input">
            @endif
            <p class="mt-1 text-xs text-slate-500">Dipakai sebagai judul lampiran penilaian di halaman belakang sertifikat.</p>
        </div>

        <div>
            <label class="admin-label">Tagline (opsional)</label>
            @if ($isCreate)
                <input type="text" name="tagline" value="{{ old('_modal') === 'create' ? old('tagline') : '' }}" class="admin-input">
            @else
                <input type="text" name="tagline" x-model="editForm.tagline" class="admin-input">
            @endif
        </div>

        <div>
            <label class="admin-label">Deskripsi</label>
            @if ($isCreate)
                <textarea name="deskripsi" rows="3" class="admin-input">{{ old('_modal') === 'create' ? old('deskripsi') : '' }}</textarea>
            @else
                <textarea name="deskripsi" x-model="editForm.deskripsi" rows="3" class="admin-input"></textarea>
            @endif
        </div>

        <div>
            <label class="admin-label">Urutan tampil</label>
            @if ($isCreate)
                <input type="number" name="urutan" min="0" value="{{ old('_modal') === 'create' ? old('urutan', 0) : 0 }}" class="admin-input">
            @else
                <input type="number" name="urutan" min="0" x-model="editForm.urutan" class="admin-input">
            @endif
        </div>

        <div class="space-y-2 rounded-xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-sm font-semibold text-slate-800">Perilaku alur PMB</p>
            <label class="flex cursor-pointer items-start gap-2">
                @if ($isCreate)
                    <input type="checkbox" name="butuh_seleksi_tes" value="1" checked class="mt-1 rounded border-slate-300 text-emerald-600">
                @else
                    <input type="checkbox" name="butuh_seleksi_tes" value="1" x-model.boolean="editForm.butuh_seleksi_tes" class="mt-1 rounded border-slate-300 text-emerald-600">
                @endif
                <span class="text-sm text-slate-700">Butuh seleksi & input nilai tes</span>
            </label>
            <label class="flex cursor-pointer items-start gap-2">
                @if ($isCreate)
                    <input type="checkbox" name="butuh_kelulusan" value="1" checked class="mt-1 rounded border-slate-300 text-emerald-600">
                @else
                    <input type="checkbox" name="butuh_kelulusan" value="1" x-model.boolean="editForm.butuh_kelulusan" class="mt-1 rounded border-slate-300 text-emerald-600">
                @endif
                <span class="text-sm text-slate-700">Butuh keputusan lulus / tidak lulus</span>
            </label>
            <label class="flex cursor-pointer items-start gap-2">
                @if ($isCreate)
                    <input type="checkbox" name="butuh_sertifikat_resmi" value="1" checked class="mt-1 rounded border-slate-300 text-emerald-600">
                @else
                    <input type="checkbox" name="butuh_sertifikat_resmi" value="1" x-model.boolean="editForm.butuh_sertifikat_resmi" class="mt-1 rounded border-slate-300 text-emerald-600">
                @endif
                <span class="text-sm text-slate-700">Butuh modul cetak sertifikat resmi (nomor urut, template)</span>
            </label>
        </div>

        <label class="inline-flex cursor-pointer items-center gap-2.5">
            @if ($isCreate)
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            @else
                <input type="checkbox" name="is_active" value="1" x-model.boolean="editForm.is_active" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            @endif
            <span class="text-sm text-slate-700">Aktif di portal publik</span>
        </label>

        <div class="flex gap-3 pt-2">
            <button type="button" @click="{{ $isCreate ? 'createOpen' : 'editOpen' }} = false" class="admin-btn-secondary flex-1">Batal</button>
            <button type="submit" class="admin-btn-primary flex-1">{{ $isCreate ? 'Simpan' : 'Perbarui' }}</button>
        </div>
    </form>
</x-admin.modal>
