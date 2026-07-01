@extends('layouts.public')

@section('title', 'Pendaftaran Berhasil')

@section('content')
<div class="public-card overflow-hidden">
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 px-6 py-10 text-center text-white">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-white/20 backdrop-blur-sm">
            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
        </div>
        <h2 class="text-2xl font-bold">Pendaftaran Berhasil!</h2>
        <p class="mt-2 text-emerald-100">Data Anda telah kami terima</p>
    </div>

    <div class="space-y-6 p-6 sm:p-8">
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 text-center">
            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">Nomor Pendaftaran</p>
            <p class="mt-2 font-mono text-2xl font-bold text-emerald-800">{{ $santri->nomor_pendaftaran }}</p>
            <p class="mt-1 text-xs text-slate-500">Simpan nomor ini untuk verifikasi</p>
        </div>

        <dl class="grid grid-cols-1 gap-4 rounded-xl border border-slate-100 bg-slate-50/50 p-5 text-sm sm:grid-cols-2">
            <div><dt class="text-slate-400">Nama</dt><dd class="mt-0.5 font-semibold text-slate-900">{{ $santri->nama_lengkap }}</dd></div>
            <div><dt class="text-slate-400">TTL</dt><dd class="mt-0.5 font-semibold text-slate-900">{{ $santri->ttl }}</dd></div>
            <div><dt class="text-slate-400">Lembaga</dt><dd class="mt-0.5 font-semibold text-slate-900">{{ $santri->lembaga }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-slate-400">Alamat</dt><dd class="mt-0.5 font-semibold text-slate-900">{{ $santri->alamat_lengkap }}</dd></div>
            <div><dt class="text-slate-400">Metode Bayar</dt><dd class="mt-0.5 font-semibold text-slate-900">{{ $santri->metode_pembayaran_label }}</dd></div>
        </dl>

        @if ($santri->metode_pembayaran === 'transfer')
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">
                <h3 class="font-semibold text-slate-900">Instruksi Transfer</h3>
                <p class="mt-2 text-sm text-slate-600">Transfer sebesar <strong>Rp {{ number_format($biaya, 0, ',', '.') }}</strong> ke:</p>
                <ul class="mt-3 space-y-1 text-sm text-slate-700">
                    <li>{{ $rekening['bank'] }} — <span class="font-mono font-semibold">{{ $rekening['nomor'] }}</span></li>
                    <li>a/n {{ $rekening['atas_nama'] }}</li>
                </ul>
            </div>

            @if (! $santri->bukti_transfer)
                <div class="rounded-xl border border-slate-200 p-5">
                    <h3 class="font-semibold text-slate-900">Upload Bukti Transfer</h3>
                    <form action="{{ route('registration.upload-bukti', $santri) }}" method="POST" enctype="multipart/form-data" class="mt-4 flex flex-col gap-3 sm:flex-row">
                        @csrf
                        <input type="file" name="bukti_transfer" accept="image/jpeg,image/jpg,image/png" required
                            class="public-input flex-1 !py-2 file:mr-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-3 file:py-1 file:text-sm file:font-semibold file:text-emerald-700">
                        <button type="submit" class="public-btn-primary">Upload</button>
                    </form>
                    @error('bukti_transfer')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            @else
                <x-admin.alert type="success">Bukti transfer sudah diunggah. Menunggu verifikasi admin.</x-admin.alert>
            @endif
        @else
            <div class="rounded-xl border border-blue-200 bg-blue-50 p-5 text-sm text-slate-700">
                Silakan datang ke kantor pendaftaran untuk melakukan pembayaran. Bawa nomor pendaftaran Anda.
            </div>
        @endif

        <div class="text-center pt-2">
            <a href="{{ route('registration.create') }}" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">← Kembali ke halaman pendaftaran</a>
        </div>
    </div>
</div>
@endsection
