@extends('layouts.admin')

@section('title', 'Template Cetak')

@section('content')
<div class="admin-page">
    <x-admin.page-header title="Template cetak" description="Unggah master sertifikat depan, kwitansi, dan kartu ujian (PNG/JPG, PDF, atau Word). PNG dipakai otomatis sebagai latar kwitansi & kartu; sertifikat depan tetap di-assign per gelombang (bisa salin dari master di sini).">
        <x-slot:actions>
            <a href="{{ route('admin.gelombangs.index') }}" class="admin-btn-secondary">Gelombang</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-body">
        <div class="admin-card">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Jenis</th>
                            <th class="w-40">Berkas</th>
                            <th>Format</th>
                            <th class="w-[22rem]">Unggah / ganti</th>
                            <th class="w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $row)
                            @php
                                $kindLabel = match ($row['kind']) {
                                    'image' => 'Gambar (cetak otomatis)',
                                    'pdf' => 'PDF',
                                    'word' => 'Word (unduh & edit)',
                                    default => '—',
                                };
                            @endphp
                            <tr>
                                <td>
                                    <p class="font-medium text-slate-900">{{ $row['meta']['label'] }}</p>
                                    <p class="mt-0.5 max-w-md text-xs text-slate-500">{{ $row['meta']['description'] }}</p>
                                </td>
                                <td>
                                    @if ($row['original_name'])
                                        <span class="text-xs font-medium text-slate-700">{{ $row['original_name'] }}</span>
                                    @else
                                        <span class="text-xs text-slate-400">Belum diunggah</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($row['kind'])
                                        <span class="admin-badge-neutral text-[11px]">{{ $kindLabel }}</span>
                                    @else
                                        <span class="text-xs text-slate-400">—</span>
                                    @endif
                                    @if ($row['preview_url'])
                                        <a href="{{ $row['preview_url'] }}" target="_blank" class="mt-1 block text-xs font-medium text-emerald-600 hover:text-emerald-700">Pratinjau gambar</a>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.templates.update', $row['slot']) }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-end gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="file" name="file" accept="{{ $row['meta']['accept'] }}" required
                                            class="block min-w-0 flex-1 text-xs text-slate-600 file:mr-2 file:rounded-lg file:border-0 file:bg-slate-100 file:px-2 file:py-1.5 file:text-xs file:font-semibold file:text-slate-700">
                                        <button type="submit" class="admin-btn-primary !py-1.5 !text-xs shrink-0">Simpan</button>
                                    </form>
                                    <p class="mt-1 text-[10px] text-slate-400">Maks. {{ (int) (($row['meta']['max_kb'] ?? 10240) / 1024) }} MB · {{ str_replace(',', ', ', $row['meta']['mimes'] ?? '') }}</p>
                                </td>
                                <td>
                                    <div class="flex flex-col gap-1.5">
                                        @if ($row['download_url'])
                                            <a href="{{ $row['download_url'] }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Unduh</a>
                                        @endif
                                        @if ($row['original_name'])
                                            <form x-ref="delTpl{{ $row['slot'] }}" action="{{ route('admin.templates.destroy', $row['slot']) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    @click="askConfirm(@js('Hapus template '.$row['meta']['label'].'?'), $refs.delTpl{{ $row['slot'] }}, { title: 'Hapus template', variant: 'danger', confirmText: 'Hapus' })"
                                                    class="text-sm font-medium text-red-600 hover:text-red-700">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-5 py-4 text-xs text-slate-500 space-y-1">
                <p><strong>Sertifikat depan:</strong> setelah master diunggah, buka <strong>Gelombang → Edit</strong> dan unggah file yang sama (atau PDF/PNG per gelombang). Overlay teks di menu Layout.</p>
                <p><strong>Word:</strong> sistem menyimpan &amp; mengunduh; untuk cetak otomatis export halaman dari Word ke <strong>PNG</strong> lalu unggah di sini.</p>
            </div>
        </div>
    </div>
</div>
@endsection
