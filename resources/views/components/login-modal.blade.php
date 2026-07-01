@guest
<div x-show="loginOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4"
    @keydown.escape.window="loginOpen = false">
    <div x-show="loginOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="loginOpen = false"></div>

    <div x-show="loginOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative w-full max-w-md overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
        @click.stop>
        <div class="border-b border-slate-100 bg-gradient-to-r from-emerald-700 to-emerald-600 px-6 py-5 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold">Login Admin</h3>
                    <p class="text-sm text-emerald-100">Masuk ke panel administrasi</p>
                </div>
                <button type="button" @click="loginOpen = false"
                    class="rounded-lg p-1.5 text-emerald-200 hover:bg-white/10 hover:text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-4 p-6">
            @csrf

            @if (session('status'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('status') }}
                </div>
            @endif

            <div>
                <label for="login_email" class="public-label">Email</label>
                <input id="login_email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="public-input">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="login_password" class="public-label">Password</label>
                <input id="login_password" type="password" name="password" required class="public-input">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <label class="inline-flex cursor-pointer items-center gap-2">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                <span class="text-sm text-slate-600">Ingat saya</span>
            </label>

            <button type="submit" class="public-btn-primary w-full !py-3">Masuk</button>
        </form>
    </div>
</div>
@endguest
