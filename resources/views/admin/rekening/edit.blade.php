@extends('layouts.admin')

@section('title', 'Rekening Transfer')

@section('content')
<div class="admin-page" x-data="{ editOpen: {{ ($errors->any() || request()->boolean('edit')) ? 'true' : 'false' }} }">
    <x-admin.page-header title="Pengaturan Rekening Transfer" description="Nomor rekening yang ditampilkan ke calon santri saat pendaftaran">
        <x-slot:actions>
            <button type="button" @click="editOpen = true" class="admin-btn-primary">Edit Rekening</button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-body">
        <div class="admin-card">
            <div class="admin-card-body">
                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-5">
                        <dt class="admin-label !normal-case !tracking-normal">Nama Bank</dt>
                        <dd class="mt-2 text-lg font-semibold text-slate-900">{{ $rekening['bank'] }}</dd>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-5">
                        <dt class="admin-label !normal-case !tracking-normal">Nomor Rekening</dt>
                        <dd class="mt-2 font-mono text-lg font-semibold text-slate-900">{{ $rekening['nomor'] }}</dd>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-5">
                        <dt class="admin-label !normal-case !tracking-normal">Atas Nama</dt>
                        <dd class="mt-2 text-lg font-semibold text-slate-900">{{ $rekening['atas_nama'] }}</dd>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-5 sm:col-span-3">
                        <dt class="admin-label !normal-case !tracking-normal">Biaya Pendaftaran</dt>
                        <dd class="mt-2 text-lg font-semibold text-slate-900">Rp {{ number_format($biaya, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <x-admin.modal show="editOpen" title="Edit Rekening Transfer">
        <form action="{{ route('admin.rekening.update') }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="admin-label">Nama Bank</label>
                <input type="text" name="bank" value="{{ old('bank', $rekening['bank']) }}" required class="admin-input">
                @error('bank')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="admin-label">Nomor Rekening</label>
                <input type="text" name="nomor" value="{{ old('nomor', $rekening['nomor']) }}" required class="admin-input font-mono">
                @error('nomor')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="admin-label">Atas Nama</label>
                <input type="text" name="atas_nama" value="{{ old('atas_nama', $rekening['atas_nama']) }}" required class="admin-input">
                @error('atas_nama')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="admin-label">Biaya Pendaftaran (Rp)</label>
                <input type="number" name="biaya_pendaftaran" value="{{ old('biaya_pendaftaran', $biaya) }}" min="0" required class="admin-input">
                @error('biaya_pendaftaran')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="editOpen = false" class="admin-btn-secondary flex-1">Batal</button>
                <button type="submit" class="admin-btn-primary flex-1">Simpan</button>
            </div>
        </form>
    </x-admin.modal>
</div>
@endsection
