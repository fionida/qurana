<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pendaftaran Santri') — Qurana</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</head>
<body class="@yield('bodyClass', 'min-h-full bg-slate-50') text-slate-800 antialiased"
    x-data="{ loginOpen: {{ ($errors->has('email') || $errors->has('password') || request()->boolean('login')) ? 'true' : 'false' }} }">

    <header class="border-b border-slate-200/80 bg-white">
        <div class="mx-auto flex @yield('containerClass', 'max-w-3xl') items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <x-site-logo size="md" />
            @guest
                <button type="button" @click="loginOpen = true" class="inline-flex items-center gap-2 rounded-xl border border-emerald-600 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:bg-emerald-50">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    Login Admin
                </button>
            @else
                <a href="{{ route('admin.dashboard') }}" class="public-btn-primary !py-2 !text-xs">
                    Dashboard Admin
                </a>
            @endguest
        </div>
    </header>

    <main class="mx-auto @yield('containerClass', 'max-w-3xl') px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
        @if (session('success'))
            <x-admin.alert type="success" class="mb-6">{{ session('success') }}</x-admin.alert>
        @endif
        @if (session('error'))
            <x-admin.alert type="error" class="mb-6">{{ session('error') }}</x-admin.alert>
        @endif

        @yield('content')
    </main>

    @hasSection('footer')
        @yield('footer')
    @else
    <footer class="border-t border-slate-200 bg-white py-6 text-center text-sm text-slate-400">
        <p>&copy; {{ date('Y') }} Qurana. Semua hak dilindungi.</p>
        <p class="mt-2"><a href="{{ route('status-check.show') }}" class="text-emerald-600 hover:underline">Cek status pendaftaran</a></p>
    </footer>
    @endif

    <x-login-modal />
</body>
</html>
