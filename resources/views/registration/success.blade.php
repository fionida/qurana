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
            <div><dt class="text-slate-400">Asal lembaga</dt><dd class="mt-0.5 font-semibold text-slate-900">{{ $santri->lembaga }}</dd></div>
            <div class="sm:col-span-2"><dt class="text-slate-400">Alamat</dt><dd class="mt-0.5 font-semibold text-slate-900">{{ $santri->alamat_lengkap }}</dd></div>
            <div><dt class="text-slate-400">Metode Bayar</dt><dd class="mt-0.5 font-semibold text-slate-900">{{ $santri->metode_pembayaran_label }}</dd></div>
        </dl>

        @if ($santri->metode_pembayaran === 'transfer')
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-5">
                <h3 class="font-semibold text-slate-900">Instruksi Transfer</h3>
                <p class="mt-2 text-sm text-slate-600">Transfer sebesar <strong>Rp {{ number_format($biaya, 0, ',', '.') }}</strong>@if(isset($nominalDasar) && $nominalDasar > $biaya) <span class="text-emerald-700">(diskon dari Rp {{ number_format($nominalDasar, 0, ',', '.') }})</span>@endif ke:</p>
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

        <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:flex-wrap sm:justify-center">
            <a href="{{ route('kartu-peserta.show', ['santri' => $santri->id, 'tanggal_lahir' => $santri->tanggal_lahir->format('Y-m-d')]) }}"
                target="_blank"
                class="public-btn-primary inline-flex w-full items-center justify-center gap-2 sm:w-auto sm:min-w-[180px]">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18.75 6.75h.008v.008h-.008V6.75Zm-12 0h.008v.008H6.75V6.75Z" /></svg>
                Cetak Kartu Peserta
            </a>
            <a href="{{ route('status-check.show') }}"
                class="public-btn-secondary inline-flex w-full items-center justify-center gap-2 sm:w-auto sm:min-w-[140px]">
                Cek Status
            </a>
            <a href="{{ route('portal.home') }}"
                class="public-btn-secondary inline-flex w-full items-center justify-center gap-2 sm:w-auto sm:min-w-[140px]">
                Kembali
            </a>
        </div>
    </div>
</div>
@endsection
