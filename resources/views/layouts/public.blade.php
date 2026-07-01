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
    <style>body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }</style>
</head>
<body class="min-h-full bg-slate-50 text-slate-800 antialiased"
    x-data="{ loginOpen: {{ ($errors->has('email') || $errors->has('password') || request()->boolean('login')) ? 'true' : 'false' }} }">

    <header class="border-b border-slate-200/80 bg-white/90 backdrop-blur-md">
        <div class="mx-auto flex max-w-3xl items-center justify-between px-4 py-4 sm:px-6">
            <x-site-logo size="md" />
            @guest
                <button type="button" @click="loginOpen = true" class="public-btn-secondary !py-2 !text-xs">
                    Login Admin
                </button>
            @else
                <a href="{{ route('admin.dashboard') }}" class="public-btn-primary !py-2 !text-xs">
                    Dashboard Admin
                </a>
            @endguest
        </div>
    </header>

    <main class="mx-auto max-w-3xl px-4 py-8 sm:px-6 sm:py-10">
        @if (session('success'))
            <x-admin.alert type="success" class="mb-6">{{ session('success') }}</x-admin.alert>
        @endif
        @if (session('error'))
            <x-admin.alert type="error" class="mb-6">{{ session('error') }}</x-admin.alert>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white py-6 text-center text-sm text-slate-400">
        &copy; {{ date('Y') }} Qurana. Semua hak dilindungi.
    </footer>

    <x-login-modal />
</body>
</html>
