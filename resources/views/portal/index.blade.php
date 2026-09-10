@extends('layouts.public')

@section('title', 'Portal Pendaftaran')
@section('bodyClass', 'min-h-full bg-white')
@section('containerClass', 'max-w-7xl')

@section('content')
<div class="portal-home -mx-0 space-y-8 pb-4 pt-2 sm:space-y-10 sm:pt-0">
    {{-- Hero --}}
    <section class="portal-hero overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-900 via-emerald-800 to-emerald-700 text-white shadow-xl shadow-emerald-900/15">
        <div class="grid gap-8 p-6 sm:p-8 lg:grid-cols-2 lg:items-center lg:gap-10 lg:p-10">
            <div>
                <div class="mb-5 flex h-16 w-16 items-center justify-center rounded-2xl bg-white/15 text-3xl font-extrabold backdrop-blur-sm">Q</div>
                <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl lg:text-4xl">Portal Pendaftaran Qurana</h1>
                <p class="mt-3 max-w-xl text-sm leading-relaxed text-emerald-100 sm:text-base">
                    Pilih program kegiatan yang sedang dibuka, lalu lanjutkan ke formulir pendaftaran dengan mudah.
                </p>
                <ul class="mt-6 grid gap-3 sm:grid-cols-3">
                    <li class="flex gap-2 rounded-xl bg-white/10 px-3 py-2.5 text-xs sm:text-[11px] lg:text-xs">
                        <span class="mt-0.5 shrink-0 text-emerald-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" /></svg>
                        </span>
                        <span><strong class="block font-semibold text-white">Aman & Terpercaya</strong>Data Anda terlindungi</span>
                    </li>
                    <li class="flex gap-2 rounded-xl bg-white/10 px-3 py-2.5 text-xs sm:text-[11px] lg:text-xs">
                        <span class="mt-0.5 shrink-0 text-emerald-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        </span>
                        <span><strong class="block font-semibold text-white">Mudah & Cepat</strong>Proses pendaftaran online</span>
                    </li>
                    <li class="flex gap-2 rounded-xl bg-white/10 px-3 py-2.5 text-xs sm:text-[11px] lg:text-xs">
                        <span class="mt-0.5 shrink-0 text-emerald-200">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                        </span>
                        <span><strong class="block font-semibold text-white">Transparan</strong>Informasi jelas & akurat</span>
                    </li>
                </ul>
            </div>
            <div class="relative hidden min-h-[220px] lg:block">
                <div class="absolute inset-0 rounded-2xl bg-emerald-600/40"></div>
                <svg class="relative mx-auto h-full w-full max-h-[280px] text-emerald-200/90" viewBox="0 0 400 280" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <ellipse cx="200" cy="250" rx="160" ry="20" fill="currentColor" opacity="0.15"/>
                    <rect x="120" y="80" width="160" height="140" rx="8" fill="white" opacity="0.95"/>
                    <rect x="135" y="100" width="30" height="25" rx="2" fill="#059669" opacity="0.7"/>
                    <rect x="175" y="100" width="30" height="25" rx="2" fill="#059669" opacity="0.5"/>
                    <rect x="215" y="100" width="30" height="25" rx="2" fill="#059669" opacity="0.7"/>
                    <rect x="255" y="100" width="15" height="25" rx="2" fill="#059669" opacity="0.4"/>
                    <rect x="135" y="135" width="30" height="25" rx="2" fill="#059669" opacity="0.5"/>
                    <rect x="175" y="135" width="30" height="25" rx="2" fill="#059669" opacity="0.7"/>
                    <rect x="215" y="135" width="30" height="25" rx="2" fill="#059669" opacity="0.5"/>
                    <rect x="135" y="170" width="30" height="25" rx="2" fill="#059669" opacity="0.6"/>
                    <rect x="175" y="170" width="70" height="50" rx="2" fill="#047857" opacity="0.8"/>
                    <polygon points="120,80 200,30 280,80" fill="white" opacity="0.9"/>
                    <circle cx="320" cy="60" r="28" fill="#fef3c7" opacity="0.35"/>
                </svg>
            </div>
        </div>
    </section>

    {{-- Pengumuman --}}
    @if ($hasOpenRegistration)
        <section class="flex flex-col gap-3 rounded-2xl border border-emerald-100 bg-emerald-50/80 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
            <p class="flex items-start gap-2 text-sm text-emerald-900 sm:items-center">
                <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600 sm:mt-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.662.331a.75.75 0 0 1-.928-.094l-1.086-1.362a.75.75 0 0 0-.928-.094l-.662.331c-.523.301-.71.961-.463 1.511.401.891.732 1.821.985 2.783m0 0 .006.006M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                <span><strong>Pendaftaran program saat ini sedang dibuka.</strong> Segera daftarkan diri Anda sekarang!</span>
            </p>
            <a href="{{ route('status-check.show') }}" class="inline-flex shrink-0 items-center justify-center rounded-xl border border-emerald-600 bg-white px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50">
                Cek Status Pendaftaran
            </a>
        </section>
    @endif

    {{-- Program --}}
    <section>
        <p class="text-xs font-bold uppercase tracking-widest text-emerald-600">Program tersedia</p>
        <h2 class="mt-1 text-2xl font-extrabold text-slate-900 sm:text-3xl">Pilih Program yang Ingin Diikuti</h2>
        <p class="mt-2 max-w-2xl text-sm text-slate-600">Klik salah satu program di bawah untuk melihat informasi lengkap dan melakukan pendaftaran.</p>

        <div class="mt-8 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($programs as $program)
                @php
                    $gelombangBuka = $program->openGelombangForRegistration();
                    $iconSet = ['doc', 'book', 'users'];
                    $icon = $iconSet[$loop->index % 3];
                    $checks = ['Pendaftaran online', 'Pembayaran & verifikasi'];
                    if ($program->butuh_seleksi_tes) {
                        $checks[] = 'Seleksi & tes';
                    }
                    if ($program->butuh_kelulusan) {
                        $checks[] = 'Pengumuman kelulusan';
                    }
                    if ($program->butuh_sertifikat_resmi) {
                        $checks[] = 'Sertifikat resmi';
                    }
                @endphp
                <article class="portal-program-card flex flex-col rounded-2xl border border-slate-200/80 bg-white p-5 shadow-sm transition hover:border-emerald-200 hover:shadow-md">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                            @if ($icon === 'book')
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" /></svg>
                            @elseif ($icon === 'users')
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" /></svg>
                            @else
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                            @endif
                        </div>
                        @if ($gelombangBuka)
                            <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">Dibuka</span>
                        @else
                            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">Tutup</span>
                        @endif
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-slate-900">{{ $program->nama }}</h3>
                    @if ($program->tagline)
                        <p class="mt-1 text-sm font-medium text-emerald-700">{{ $program->tagline }}</p>
                    @endif
                    @if ($program->deskripsi)
                        <p class="mt-2 line-clamp-3 flex-1 text-sm leading-relaxed text-slate-600">{{ $program->deskripsi }}</p>
                    @endif
                    <ul class="mt-4 space-y-1.5">
                        @foreach ($checks as $item)
                            <li class="flex items-center gap-2 text-xs text-slate-600">
                                <svg class="h-4 w-4 shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                {{ $item }}
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-5 flex flex-col gap-2 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                        <a href="{{ route('portal.program.show', $program) }}" class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                            Lihat Detail →
                        </a>
                        @if ($gelombangBuka)
                            <a href="{{ route('registration.form', $program) }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                Daftar Sekarang →
                            </a>
                        @else
                            <span class="text-xs font-medium text-amber-700">Menunggu gelombang buka</span>
                        @endif
                    </div>
                </article>
            @empty
                <p class="col-span-full py-16 text-center text-slate-500">Belum ada program kegiatan aktif. Hubungi panitia.</p>
            @endforelse
        </div>
    </section>

    {{-- Fitur --}}
    <section class="rounded-2xl border border-slate-100 bg-slate-50/80 px-4 py-8 sm:px-8">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="text-center sm:text-left">
                <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm sm:mx-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" /></svg>
                </div>
                <h3 class="font-bold text-slate-900">100% Online</h3>
                <p class="mt-1 text-xs leading-relaxed text-slate-600">Daftar kapan saja dari perangkat Anda tanpa antre di loket.</p>
            </div>
            <div class="text-center sm:text-left">
                <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm sm:mx-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                </div>
                <h3 class="font-bold text-slate-900">Data Aman</h3>
                <p class="mt-1 text-xs leading-relaxed text-slate-600">Kami menjaga kerahasiaan data pendaftar sesuai kebijakan yang berlaku.</p>
            </div>
            <div class="text-center sm:text-left">
                <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm sm:mx-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.697c0-1.355-.812-2.573-2.066-3.066A48.908 48.908 0 0 0 12 3c-2.078 0-4.092.325-5.934.971C4.812 4.464 4 5.682 4 6.697v1.814" /></svg>
                </div>
                <h3 class="font-bold text-slate-900">Bantuan</h3>
                <p class="mt-1 text-xs leading-relaxed text-slate-600">Tim support siap membantu jika Anda mengalami kendala saat mendaftar.</p>
            </div>
            <div class="text-center sm:text-left">
                <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-white text-emerald-600 shadow-sm sm:mx-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" /></svg>
                </div>
                <h3 class="font-bold text-slate-900">FAQ</h3>
                <p class="mt-1 text-xs leading-relaxed text-slate-600">
                    <a href="{{ route('status-check.show') }}" class="font-medium text-emerald-700 hover:underline">Cek status</a>
                    atau hubungi panitia untuk pertanyaan umum.
                </p>
            </div>
        </div>
    </section>
</div>
@endsection

@section('footer')
<footer class="border-t border-slate-200 bg-white py-8 text-center">
    <p class="text-sm text-slate-500">&copy; {{ date('Y') }} Qurana. Semua hak dilindungi.</p>
    <div class="mt-3 flex flex-wrap items-center justify-center gap-4 text-sm font-medium text-emerald-700">
        <a href="{{ route('status-check.show') }}" class="hover:underline">Cek Status Pendaftaran</a>
        <span class="text-slate-300" aria-hidden="true">·</span>
        <a href="#" class="hover:underline">Kebijakan Privasi</a>
        <span class="text-slate-300" aria-hidden="true">·</span>
        <a href="#" class="hover:underline">Syarat &amp; Ketentuan</a>
    </div>
</footer>
@endsection
