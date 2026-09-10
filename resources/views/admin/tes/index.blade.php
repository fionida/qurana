@extends('layouts.admin')

@section('title', 'Seleksi & Tes')

@section('content')
<div class="admin-page">
    <x-admin.page-header title="Seleksi & Tes" description="Input nilai, rekap ranking, dan daftar hadir per gelombang">
        @if ($gelombang)
            <x-slot:actions>
                <a href="{{ route('admin.tes.rekap', $gelombang) }}" class="admin-btn-secondary">Rekap & ranking</a>
                <a href="{{ route('admin.tes.daftar-hadir', $gelombang) }}" target="_blank" class="admin-btn-secondary">Daftar hadir</a>
                <a href="{{ route('admin.tes.berita-acara', $gelombang) }}" target="_blank" class="admin-btn-secondary">Berita acara</a>
                <form method="POST" action="{{ route('admin.tes.kelulusan', $gelombang) }}" class="inline" onsubmit="return confirm('Terapkan kelulusan otomatis untuk peserta yang sudah punya nilai akhir?')">
                    @csrf
                    <button type="submit" class="admin-btn-primary">Proses kelulusan</button>
                </form>
            </x-slot:actions>
        @endif
    </x-admin.page-header>

    <div class="admin-page-toolbar">
        <div class="admin-card admin-card-body !py-4">
            <form method="GET" class="flex flex-wrap gap-3">
                <select name="gelombang" class="admin-select min-w-[14rem]" onchange="this.form.submit()">
                    @foreach ($gelombangOptions as $g)
                        <option value="{{ $g->id }}" @selected($gelombang?->id === $g->id)>{{ $g->nama }}</option>
                    @endforeach
                </select>
                @if ($gelombang)
                    <a href="{{ route('admin.gelombangs.komponen-tes.edit', $gelombang) }}" class="admin-btn-secondary">Komponen tes</a>
                @endif
            </form>
        </div>
    </div>

    @if (! $gelombang)
        <p class="text-slate-500">Buat gelombang terlebih dahulu.</p>
    @else
        <div class="admin-page-body space-y-4">
            @foreach ($santris as $santri)
                @php
                    $nilaiMap = $santri->nilaiTes->keyBy('gelombang_komponen_tes_id');
                @endphp
                <div class="admin-card admin-card-body">
                    <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <p class="font-semibold text-slate-900">{{ $santri->nama_lengkap }}</p>
                            <p class="text-xs text-slate-500">{{ $santri->nomor_pendaftaran }} · {{ $santri->statusPendaftarLabel() }}@if($santri->nilai_akhir !== null) · Nilai {{ $santri->nilai_akhir }}@endif</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.tes.nilai.store', $gelombang) }}" class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                        @csrf
                        <input type="hidden" name="santri_id" value="{{ $santri->id }}">
                        @foreach ($komponen as $k)
                            <div>
                                <label class="text-xs font-medium text-slate-600">{{ $k->nama_komponen }} <span class="text-slate-400">(maks {{ $k->nilai_maksimal }})</span></label>
                                <input type="number" step="0.01" name="nilai[{{ $k->id }}]" value="{{ $nilaiMap->get($k->id)?->nilai }}" class="admin-input !py-1.5 !text-sm">
                            </div>
                        @endforeach
                        <div class="flex items-end sm:col-span-2 lg:col-span-4">
                            <button type="submit" class="admin-btn-primary !py-2 !text-xs">Simpan nilai</button>
                        </div>
                    </form>
                </div>
            @endforeach
            @if ($santris->isEmpty())
                <p class="text-center text-slate-400 py-12">Belum ada pendidik lunas di gelombang ini.</p>
            @endif
        </div>
    @endif
</div>
@endsection
