<div class="reg-hero">
    <div class="pointer-events-none absolute inset-0 opacity-20" aria-hidden="true">
        <div class="absolute -right-8 top-4 h-32 w-32 rounded-full bg-white/30 blur-2xl"></div>
        <div class="absolute -left-6 bottom-0 h-24 w-24 rounded-full bg-teal-300/40 blur-xl"></div>
    </div>
    <div class="relative">
        @if ($siteLogo ?? null)
            <img src="{{ $siteLogo }}" alt="Logo Qurana" class="mx-auto mb-4 h-16 w-auto max-w-[180px] object-contain drop-shadow-md">
        @endif
        <h1 class="text-xl font-bold sm:text-2xl">{{ $program->nama }}</h1>
        <p class="mx-auto mt-2 max-w-lg text-sm text-emerald-100">{{ $program->tagline ?? 'Tingkatkan kompetensi, tebarkan manfaat' }}</p>
        @if (! empty($gelombang))
            <div class="reg-hero-badge">
                <span>{{ $gelombang->nama }}</span>
                @if ($sisaKuota !== null)
                    <span class="text-white/70">|</span>
                    <span>Kuota tersisa: {{ $sisaKuota }} orang</span>
                @endif
            </div>
        @endif
        <p class="mt-4">
            <a href="{{ route('portal.home') }}" class="text-xs text-emerald-100 underline hover:text-white">← Portal kegiatan</a>
        </p>
        @if ($loggedInUsername ?? null)
            <p class="mt-2 inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-medium">Login: {{ $loggedInUsername }}</p>
        @endif
    </div>
</div>
