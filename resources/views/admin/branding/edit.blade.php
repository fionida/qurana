@extends('layouts.admin')

@section('title', 'Logo & Branding')

@section('content')
<div class="admin-page" x-data="{ uploadOpen: {{ ($errors->has('logo') || request()->boolean('upload')) ? 'true' : 'false' }} }">
    <x-admin.page-header title="Logo & Branding" description="Logo Qurana yang tampil di form pendaftaran santri">
        <x-slot:actions>
            <button type="button" @click="uploadOpen = true" class="admin-btn-primary">
                {{ $logoUrl ? 'Ganti Logo' : 'Unggah Logo' }}
            </button>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-body">
        <div class="grid min-h-0 flex-1 grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="admin-card">
                <div class="admin-card-header"><h3 class="font-semibold text-slate-900">Logo Saat Ini</h3></div>
                <div class="admin-card-body flex flex-1 flex-col items-center justify-center">
                    <div class="flex min-h-[200px] w-full flex-col items-center justify-center rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 p-8">
                        @if ($logoUrl)
                            <img src="{{ $logoUrl }}" alt="Logo Qurana" class="max-h-36 w-auto max-w-full object-contain">
                        @else
                            <div class="flex h-24 w-24 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-400 to-emerald-600 text-4xl font-extrabold text-white">Q</div>
                            <p class="mt-4 text-sm text-slate-500">Logo default (belum diunggah)</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="admin-card">
                <div class="admin-card-header"><h3 class="font-semibold text-slate-900">Preview di Form Pendaftaran</h3></div>
                <div class="admin-card-body flex flex-1 items-center justify-center">
                    <div class="w-full rounded-xl bg-gradient-to-r from-emerald-700 to-emerald-600 p-10 text-center">
                        @if ($logoUrl)
                            <img src="{{ $logoUrl }}" alt="Preview" class="mx-auto max-h-20 w-auto object-contain">
                        @else
                            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-white/20 text-3xl font-extrabold text-white">Q</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-admin.modal show="uploadOpen" title="{{ $logoUrl ? 'Ganti Logo' : 'Unggah Logo' }}">
        <form action="{{ route('admin.branding.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="admin-label">File Logo</label>
                <input type="file" name="logo" accept="image/jpeg,image/jpg,image/png,image/svg+xml,image/webp" required
                    class="block w-full text-sm text-slate-600 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-emerald-700">
                <p class="mt-2 text-xs text-slate-400">PNG, JPG, SVG, atau WebP. Maks. 2 MB.</p>
                @error('logo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="flex gap-3 pt-2">
                <button type="button" @click="uploadOpen = false" class="admin-btn-secondary flex-1">Batal</button>
                <button type="submit" class="admin-btn-primary flex-1">Simpan Logo</button>
            </div>
        </form>
        @if ($logoUrl)
            <form x-ref="deleteLogoForm" action="{{ route('admin.branding.destroy') }}" method="POST" class="mt-4 border-t border-slate-100 pt-4">
                @csrf @method('DELETE')
                <button type="button"
                    @click="askConfirm('Hapus logo dan kembali ke default?', $refs.deleteLogoForm, { title: 'Hapus Logo', confirmText: 'Ya, hapus', variant: 'danger' })"
                    class="admin-btn-secondary w-full text-red-600 hover:text-red-700">Hapus Logo</button>
            </form>
        @endif
    </x-admin.modal>
</div>
@endsection
