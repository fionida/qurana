@extends('layouts.admin')

@section('title', 'Komponen Tes')

@section('content')
<div class="admin-page">
    <x-admin.page-header :title="'Komponen tes — '.$gelombang->nama" description="Tambah atau hapus komponen sesuai kebutuhan gelombang. Bobot dipakai untuk nilai akhir (normalisasi ke skala 100).">
        <x-slot:actions>
            <a href="{{ route('admin.gelombangs.index') }}" class="admin-btn-secondary">Gelombang</a>
            <a href="{{ route('admin.tes.index', ['gelombang' => $gelombang->id]) }}" class="admin-btn-secondary">Input nilai</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-body admin-page-body--scroll">
        <div class="grid gap-4 lg:grid-cols-3">
            <div class="admin-card">
                <div class="border-b border-slate-100 px-5 py-3.5">
                    <p class="text-sm font-semibold text-slate-800">Tambah komponen</p>
                    <p class="mt-0.5 text-xs text-slate-500">Komponen baru langsung dipakai di input nilai & sertifikat.</p>
                </div>
                <form action="{{ route('admin.gelombangs.komponen-tes.store', $gelombang) }}" method="POST" class="space-y-4 p-5">
                    @csrf
                    <div>
                        <label class="admin-label">Nama komponen</label>
                        <input type="text" name="nama_komponen" value="{{ old('nama_komponen') }}" required placeholder="Contoh: Wawancara" class="admin-input">
                        @error('nama_komponen')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="admin-label">Bobot (%)</label>
                            <input type="number" step="0.01" name="bobot" value="{{ old('bobot') }}" placeholder="Opsional" class="admin-input">
                        </div>
                        <div>
                            <label class="admin-label">Nilai maks.</label>
                            <input type="number" step="0.01" name="nilai_maksimal" value="{{ old('nilai_maksimal', 100) }}" required min="1" class="admin-input">
                        </div>
                    </div>
                    <button type="submit" class="admin-btn-primary w-full">Tambah</button>
                </form>
            </div>

            <div class="admin-card lg:col-span-2">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-5 py-3.5">
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Daftar komponen</p>
                        <p class="text-xs text-slate-500">{{ $komponen->count() }} baris · urutan mengikuti baris tabel</p>
                    </div>
                </div>

                <form id="komponen-tes-save" method="POST" action="{{ route('admin.gelombangs.komponen-tes.update', $gelombang) }}" class="hidden">
                    @csrf
                    @method('PUT')
                </form>

                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th class="w-12">No</th>
                                <th>Komponen</th>
                                <th class="w-28">Bobot (%)</th>
                                <th class="w-28">Nilai maks.</th>
                                <th class="w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($komponen as $item)
                                <tr>
                                    <td class="text-center font-mono text-xs text-slate-500">{{ $loop->iteration }}</td>
                                    <td>
                                        <input type="hidden" form="komponen-tes-save" name="komponen[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                        <input type="text" form="komponen-tes-save" name="komponen[{{ $loop->index }}][nama_komponen]" value="{{ old('komponen.'.$loop->index.'.nama_komponen', $item->nama_komponen) }}" required class="admin-input !py-1.5 !text-sm">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" form="komponen-tes-save" name="komponen[{{ $loop->index }}][bobot]" value="{{ old('komponen.'.$loop->index.'.bobot', $item->bobot) }}" class="admin-input !py-1.5 !text-sm">
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" form="komponen-tes-save" name="komponen[{{ $loop->index }}][nilai_maksimal]" value="{{ old('komponen.'.$loop->index.'.nilai_maksimal', $item->nilai_maksimal) }}" required class="admin-input !py-1.5 !text-sm">
                                    </td>
                                    <td>
                                        @if ($komponen->count() > 1)
                                            <form x-ref="delKomponen{{ $item->id }}" action="{{ route('admin.gelombangs.komponen-tes.destroy', [$gelombang, $item]) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    @click="askConfirm(@js('Hapus komponen «'.$item->nama_komponen.'»? Nilai tes terkait ikut terhapus.'), $refs.delKomponen{{ $item->id }}, { title: 'Hapus komponen', variant: 'danger', confirmText: 'Hapus' })"
                                                    class="text-sm font-medium text-red-600 hover:text-red-700">Hapus</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-end border-t border-slate-100 px-5 py-4">
                    <button type="submit" form="komponen-tes-save" class="admin-btn-primary">Simpan perubahan</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
