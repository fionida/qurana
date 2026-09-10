@extends('layouts.admin')

@section('title', 'Cetak Massal')

@section('content')
<div class="admin-page">
    <x-admin.page-header :title="'Cetak massal — '.$gelombang->nama" description="Buka pratinjau per peserta lalu cetak / Save as PDF (landscape)">
        <x-slot:actions>
            <a href="{{ route('admin.certificates.index', ['gelombang' => $gelombang->id]) }}" class="admin-btn-secondary">Kembali</a>
        </x-slot:actions>
    </x-admin.page-header>
    <div class="admin-page-body admin-card admin-card-body">
        <p class="mb-4 text-sm text-slate-600">{{ $santris->count() }} peserta layak cetak. Klik nama untuk pratinjau di tab baru.</p>
        <ul class="space-y-2">
            @foreach ($santris as $santri)
                <li class="flex flex-wrap items-center justify-between gap-2 rounded-lg border border-slate-100 px-4 py-3">
                    <span class="font-medium">{{ $santri->nama_lengkap }} <span class="text-xs font-mono text-slate-500">{{ $santri->nomor_pendaftaran }}</span></span>
                    <a href="{{ route('admin.certificates.preview', $santri) }}" target="_blank" class="admin-btn-primary !py-1.5 !text-xs">Pratinjau & cetak</a>
                </li>
            @endforeach
        </ul>
        @if ($santris->isEmpty())
            <p class="text-center text-slate-400 py-8">Tidak ada peserta lulus di gelombang ini.</p>
        @endif
    </div>
</div>
@endsection
