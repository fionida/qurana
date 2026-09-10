@extends('layouts.public')

@section('title', $program->nama)

@section('content')
<div class="public-card overflow-hidden">
    <div class="border-b border-slate-100 bg-gradient-to-r from-emerald-700 to-emerald-600 px-6 py-8 text-white">
        <p class="text-xs font-medium uppercase tracking-wider text-emerald-100"><a href="{{ route('portal.home') }}" class="hover:underline">Portal</a> / Program</p>
        <h1 class="mt-2 text-2xl font-bold">{{ $program->nama }}</h1>
        @if ($program->tagline)
            <p class="mt-1 text-sm text-emerald-100">{{ $program->tagline }}</p>
        @endif
    </div>

    <div class="space-y-6 p-6 sm:p-8">
        @if ($program->deskripsi)
            <div class="prose prose-sm max-w-none text-slate-600">
                <p>{{ $program->deskripsi }}</p>
            </div>
        @endif

        <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4 text-sm text-slate-700">
            <p class="font-semibold text-slate-900">Alur kegiatan</p>
            <p class="mt-1">{{ $program->alurRingkasLabel() }}</p>
        </div>

        @if ($gelombang)
            <div class="rounded-xl border border-emerald-200 bg-emerald-50/60 p-4">
                <p class="text-sm font-semibold text-emerald-900">Pendaftaran dibuka</p>
                <p class="mt-1 text-sm text-emerald-800">Gelombang: <strong>{{ $gelombang->nama }}</strong></p>
                <p class="mt-1 text-sm text-emerald-800">Biaya: Rp {{ number_format($gelombang->biayaPendaftaranEfektif(), 0, ',', '.') }}</p>
                <a href="{{ route('registration.form', $program) }}" class="public-btn-primary mt-4 inline-flex">Isi formulir pendaftaran</a>
            </div>
        @else
            <x-admin.alert type="error">Pendaftaran untuk program ini sedang ditutup atau kuota penuh.</x-admin.alert>
        @endif

        <p class="text-center text-sm">
            <a href="{{ route('portal.home') }}" class="text-emerald-700 hover:underline">← Semua program</a>
        </p>
    </div>
</div>
@endsection
