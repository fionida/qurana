@props(['show', 'title' => '', 'size' => 'lg'])

@php
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        '2xl' => 'max-w-5xl',
    ];
@endphp

<div x-show="{{ $show }}" x-cloak
    class="fixed inset-0 z-[60] flex items-center justify-center p-4"
    @keydown.escape.window="{{ $show }} = false">
    <div x-show="{{ $show }}" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"
        @click="{{ $show }} = false"></div>

    <div x-show="{{ $show }}" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-2"
        class="relative flex max-h-[90vh] w-full {{ $sizes[$size] ?? $sizes['lg'] }} flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"
        @click.stop>
        @if ($title)
            <div class="flex shrink-0 items-center justify-between border-b border-slate-100 px-6 py-4">
                <h3 class="text-lg font-bold text-slate-900">{{ $title }}</h3>
                <button type="button" @click="{{ $show }} = false"
                    class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
        <div class="overflow-y-auto {{ $title ? 'p-6' : '' }}">
            {{ $slot }}
        </div>
    </div>
</div>
