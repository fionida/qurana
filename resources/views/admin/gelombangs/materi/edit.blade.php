@extends('layouts.admin')

@section('title', 'Materi Sertifikat')

@section('content')
<div class="admin-page">
    <x-admin.page-header :title="'Materi — '.$gelombang->nama" description="Durasi (menit) dan JPL per materi. Baris kosong tidak ditampilkan di sertifikat; total dihitung otomatis saat cetak.">
        <x-slot:actions>
            <a href="{{ route('admin.gelombangs.index') }}" class="admin-btn-secondary">Kembali ke Gelombang</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="admin-page-body">
        <div class="admin-card admin-card-grow">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-5 py-3.5">
                <div>
                    <p class="text-sm font-semibold text-slate-800">Data materi</p>
                    <p class="text-xs text-slate-500">{{ $materis->count() }} materi · program {{ $gelombang->program?->nama ?? '—' }}</p>
                </div>
            </div>

            <form id="materi-save" action="{{ route('admin.gelombangs.materi.update', $gelombang) }}" method="POST" class="hidden">
                @csrf
                @method('PUT')
            </form>

            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th class="w-14 text-center">No</th>
                            <th>Materi</th>
                            <th class="w-32 text-center">Durasi</th>
                            <th class="w-24 text-center">JPL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($materis as $materi)
                            <tr>
                                <td class="text-center font-mono text-xs text-slate-500">{{ $materi->urutan }}</td>
                                <td>
                                    <input type="hidden" form="materi-save" name="materi[{{ $loop->index }}][id]" value="{{ $materi->id }}">
                                    <input type="text" form="materi-save" name="materi[{{ $loop->index }}][nama_materi]" value="{{ old('materi.'.$loop->index.'.nama_materi', $materi->nama_materi) }}" required class="admin-input !py-1.5 !text-sm">
                                </td>
                                <td class="text-center">
                                    <input type="text" form="materi-save" name="materi[{{ $loop->index }}][durasi]" value="{{ old('materi.'.$loop->index.'.durasi', $materi->durasi) }}" placeholder="—" class="admin-input !py-1.5 !text-sm text-center" inputmode="numeric">
                                </td>
                                <td class="text-center">
                                    <input type="number" form="materi-save" name="materi[{{ $loop->index }}][jpl]" value="{{ old('materi.'.$loop->index.'.jpl', $materi->jpl) }}" min="0" max="999" placeholder="—" class="admin-input !py-1.5 !text-sm text-center">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-slate-200 bg-slate-50/90 font-semibold text-slate-700">
                            <td colspan="2" class="text-right text-sm uppercase tracking-wide text-slate-500">Jumlah</td>
                            <td class="text-center font-mono text-sm">{{ $gelombang->totalMateriDurasiLabel() ?: '—' }}</td>
                            <td class="text-center font-mono text-sm">{{ $gelombang->totalMateriJpl() ?: '—' }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="space-y-3 border-t border-slate-100 px-5 py-4">
                <p class="text-xs text-slate-500">Durasi diisi angka menit (tanda &rsquo; menit ditambahkan otomatis di cetak). Kolom durasi/JPL kosong = baris materi tidak muncul di sertifikat.</p>
                <div class="flex justify-end">
                    <button type="submit" form="materi-save" class="admin-btn-primary">Simpan materi</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
