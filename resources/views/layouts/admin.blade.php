<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Qurana</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif; }</style>
</head>
<body class="h-full overflow-hidden bg-slate-50 text-slate-800 antialiased" x-data="adminShell">

    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
        class="fixed inset-0 z-40 bg-emerald-950/60 backdrop-blur-sm lg:hidden"></div>

    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-emerald-900 transition-transform duration-300 lg:translate-x-0">

        <div class="flex h-16 items-center gap-3 border-b border-emerald-800 px-5">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-lg shadow-emerald-950/40">
                <span class="text-lg font-extrabold text-white">Q</span>
            </div>
            <div>
                <p class="text-sm font-bold text-white">Qurana Admin</p>
                <p class="text-[11px] text-emerald-200">Panel Administrasi</p>
            </div>
            <button @click="sidebarOpen = false" class="ml-auto rounded-lg p-1.5 text-emerald-300 hover:bg-emerald-800 hover:text-white lg:hidden">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <nav class="flex-1 space-y-1 overflow-y-auto p-4">
            <p class="mb-2 px-3 text-[10px] font-bold uppercase tracking-widest text-emerald-400">Menu Utama</p>

            <x-admin.nav-item href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                <x-slot:icon><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg></x-slot:icon>
                Dashboard
            </x-admin.nav-item>

            <x-admin.nav-item href="{{ route('admin.santris.index') }}" :active="request()->routeIs('admin.santris.*')">
                <x-slot:icon><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg></x-slot:icon>
                Database Pendidik
            </x-admin.nav-item>

            <x-admin.nav-item href="{{ route('admin.payments.index') }}" :active="request()->routeIs('admin.payments.*')">
                <x-slot:icon><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg></x-slot:icon>
                Pembayaran Baru
            </x-admin.nav-item>

            <x-admin.nav-item href="{{ route('admin.certificates.index') }}" :active="request()->routeIs('admin.certificates.*')">
                <x-slot:icon><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg></x-slot:icon>
                Cetak Sertifikat
            </x-admin.nav-item>

            <x-admin.nav-item href="{{ route('admin.photo-sheets.index') }}" :active="request()->routeIs('admin.photo-sheets.*')">
                <x-slot:icon><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75 4.5 12m0 0 1.929 2.25M4.5 12h15m-1.929-2.25L19.5 12m0 0-1.929 2.25M7.5 4.5h9A2.25 2.25 0 0 1 18.75 6.75v10.5A2.25 2.25 0 0 1 16.5 19.5h-9a2.25 2.25 0 0 1-2.25-2.25V6.75A2.25 2.25 0 0 1 7.5 4.5Z" /></svg></x-slot:icon>
                Cetak Foto Pendidik
            </x-admin.nav-item>

            <p class="mb-2 mt-6 px-3 text-[10px] font-bold uppercase tracking-widest text-emerald-400">Pengaturan</p>

            <x-admin.nav-item href="{{ route('admin.rekening.edit') }}" :active="request()->routeIs('admin.rekening.*')">
                <x-slot:icon><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6v11.25A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75V9Z" /></svg></x-slot:icon>
                Rekening Transfer
            </x-admin.nav-item>

            <x-admin.nav-item href="{{ route('admin.branding.edit') }}" :active="request()->routeIs('admin.branding.*')">
                <x-slot:icon><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Z" /></svg></x-slot:icon>
                Logo & Branding
            </x-admin.nav-item>

            <x-admin.nav-item href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                <x-slot:icon><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg></x-slot:icon>
                Manajemen User
            </x-admin.nav-item>
        </nav>

        <div class="border-t border-emerald-800 p-4">
            <div class="flex items-center gap-3 rounded-xl bg-emerald-950/30 p-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 text-sm font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-white">{{ auth()->user()->name }}</p>
                    <p class="truncate text-xs text-emerald-200">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="rounded-lg p-2 text-emerald-300 transition hover:bg-emerald-800 hover:text-white">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="flex h-screen flex-col lg:pl-72">
        <header class="sticky top-0 z-30 shrink-0 border-b border-slate-200/80 bg-white/80 backdrop-blur-md">
            <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button @click="sidebarOpen = true" class="rounded-lg border border-slate-200 p-2 text-slate-600 hover:bg-slate-50 lg:hidden">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                    </button>
                    <div class="hidden sm:block">
                        <p class="text-xs font-medium text-slate-400">@yield('title', 'Admin')</p>
                        <p class="text-sm font-semibold text-slate-800">{{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
                <a href="{{ route('home') }}" target="_blank" class="admin-btn-secondary hidden sm:inline-flex !py-2 !text-xs">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                    Lihat Situs
                </a>
            </div>
        </header>

        <main class="flex min-h-0 flex-1 flex-col overflow-hidden px-4 py-4 sm:px-6 sm:py-6 lg:px-8">
            @if (session('success'))
                <x-admin.alert type="success" class="mb-4 shrink-0">{{ session('success') }}</x-admin.alert>
            @endif
            @if (session('error'))
                <x-admin.alert type="error" class="mb-4 shrink-0">{{ session('error') }}</x-admin.alert>
            @endif
            <div class="admin-workspace flex min-h-0 flex-1 flex-col overflow-hidden">
                @yield('content')
            </div>
        </main>
    </div>

    <x-admin.confirm-modal />
</body>
</html>
