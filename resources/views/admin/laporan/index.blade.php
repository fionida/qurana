@extends('layouts.admin')

@section('title', 'Laporan & Export')

@section('content')
<div class="admin-page">
    <x-admin.page-header title="Laporan & Export" description="Unduh CSV per program (semua gelombang) atau per gelombang tertentu" />

    <div class="admin-page-body grid gap-4 lg:grid-cols-2">
        <div class="admin-card admin-card-body lg:col-span-2">
            <h3 class="font-semibold text-slate-900">Filter cakupan export</h3>
            <p class="mt-1 text-sm text-slate-500">Pilih program untuk membatasi daftar gelombang di bawah. Export CSV memakai pilihan program/gelombang pada masing-masing tombol.</p>
            <x-admin.program-gelombang-filter
                :action="route('admin.laporan.index')"
                :program-options="$programOptions"
                :gelombang-options="$gelombangOptions"
                :selected-program="$selectedProgram"
                :selected-gelombang="$selectedGelombang"
                submit-label="Atur filter"
                class="mt-4"
            />
        </div>

        @php
            $exports = [
                ['route' => 'admin.laporan.export.pendaftar', 'title' => 'Data pendaftar', 'desc' => 'Semua peserta beserta status dan nilai'],
                ['route' => 'admin.laporan.export.pembayaran', 'title' => 'Riwayat pembayaran', 'desc' => 'Transaksi terverifikasi dari bendahara'],
                ['route' => 'admin.laporan.export.rekonsiliasi', 'title' => 'Rekonsiliasi', 'desc' => 'Peserta lunas vs nominal & voucher'],
            ];
        @endphp

        @foreach ($exports as $item)
            <div class="admin-card admin-card-body">
                <h3 class="font-semibold text-slate-900">{{ $item['title'] }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ $item['desc'] }}</p>
                <x-admin.program-gelombang-filter
                    :action="route($item['route'])"
                    :program-options="$programOptions"
                    :gelombang-options="$gelombangOptions"
                    :selected-program="$selectedProgram"
                    :selected-gelombang="$selectedGelombang"
                    submit-label="Download CSV"
                    class="mt-4"
                />
            </div>
        @endforeach

        <div class="admin-card admin-card-body lg:col-span-2">
            <h3 class="font-semibold text-slate-900">Export nilai tes per gelombang</h3>
            <p class="mt-1 text-sm text-slate-500">Pilih satu gelombang (termasuk kolom program di CSV).</p>
            <form method="GET" action="#" id="nilai-export-form" class="mt-4 flex flex-wrap gap-2">
                <select id="nilai-gelombang" class="admin-select min-w-[16rem]" required>
                    <option value="">Pilih gelombang</option>
                    @foreach ($gelombangOptions as $g)
                        <option value="{{ route('admin.laporan.export.nilai', $g) }}">
                            @if ($g->program){{ $g->program->nama }} — @endif{{ $g->nama }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="admin-btn-secondary" onclick="event.preventDefault(); const u=document.getElementById('nilai-gelombang').value; if(u) window.location=u;">Download CSV</button>
            </form>
            @if ($selectedProgram)
                <p class="mt-2 text-xs text-slate-500">Gelombang ditampilkan untuk program <strong>{{ $selectedProgram->nama }}</strong>.</p>
            @endif
        </div>
    </div>
</div>
@endsection
