@extends('layouts.public')

@section('title', 'Cek Status Pendaftaran')

@section('content')
<div class="public-card max-w-lg mx-auto">
    <div class="border-b border-slate-100 px-6 py-6">
        <h2 class="text-xl font-bold text-slate-900">Cek Status Pendaftaran</h2>
        <p class="mt-1 text-sm text-slate-500">Masukkan nomor pendaftaran dan tanggal lahir Anda.</p>
    </div>
    <div class="p-6">
        <form method="POST" action="{{ route('status-check.lookup') }}" class="space-y-4">
            @csrf
            <div>
                <label class="public-label">Nomor pendaftaran</label>
                <input type="text" name="nomor_pendaftaran" value="{{ old('nomor_pendaftaran', $input['nomor_pendaftaran'] ?? '') }}" required class="public-input font-mono" placeholder="QRN-2026-0001">
            </div>
            <div>
                <label class="public-label">Tanggal lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $input['tanggal_lahir'] ?? '') }}" required class="public-input">
            </div>
            <button type="submit" class="public-btn w-full">Cek status</button>
        </form>

        @if (! empty($searched))
            <div class="mt-6 rounded-xl border p-4 {{ $santri ? 'border-emerald-200 bg-emerald-50/50' : 'border-amber-200 bg-amber-50/50' }}">
                @if ($santri)
                    <p class="font-semibold text-slate-900">{{ $santri->nama_lengkap }}</p>
                    <p class="text-sm text-slate-600">{{ $santri->nomor_pendaftaran }}</p>
                    @if ($santri->gelombang)
                        <p class="mt-2 text-sm">Gelombang: <strong>{{ $santri->gelombang->nama }}</strong></p>
                    @endif
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Status</dt><dd class="font-medium">{{ $santri->statusPendaftarLabel() }}</dd></div>
                        <div class="flex justify-between gap-4"><dt class="text-slate-500">Pembayaran</dt><dd>{{ $santri->status_pembayaran_label }}</dd></div>
                        @if ($santri->nilai_akhir !== null)
                            <div class="flex justify-between gap-4"><dt class="text-slate-500">Nilai akhir</dt><dd>{{ $santri->nilai_akhir }}</dd></div>
                        @endif
                        @if ($santri->gelombang?->jadwal_tes_mulai)
                            <div class="flex justify-between gap-4"><dt class="text-slate-500">Jadwal tes</dt><dd>{{ $santri->gelombang->jadwal_tes_mulai->format('d/m/Y') }}@if($santri->gelombang->jadwal_tes_selesai) – {{ $santri->gelombang->jadwal_tes_selesai->format('d/m/Y') }}@endif</dd></div>
                        @endif
                    </dl>
                @else
                    <p class="text-sm text-amber-800">Data tidak ditemukan. Periksa nomor pendaftaran dan tanggal lahir.</p>
                @endif
            </div>
        @endif

        <p class="mt-6 text-center text-sm"><a href="{{ route('portal.home') }}" class="text-emerald-700 hover:underline">Kembali ke portal kegiatan</a></p>
    </div>
</div>
@endsection
