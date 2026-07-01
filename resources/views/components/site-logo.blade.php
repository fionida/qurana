@props(['size' => 'md'])

@php
    $sizes = [
        'sm' => ['box' => 'h-9 w-9 text-sm', 'img' => 'h-9', 'text' => 'text-base'],
        'md' => ['box' => 'h-11 w-11 text-lg', 'img' => 'h-11', 'text' => 'text-xl'],
        'lg' => ['box' => 'h-16 w-16 text-2xl', 'img' => 'h-16', 'text' => 'text-2xl'],
    ];
    $s = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
    @if ($siteLogo ?? null)
        <img src="{{ $siteLogo }}" alt="Logo Qurana" class="{{ $s['img'] }} w-auto max-w-[140px] object-contain">
    @else
        <div class="flex {{ $s['box'] }} shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 font-extrabold text-white shadow-md shadow-emerald-900/20">
            Q
        </div>
        <div>
            <p class="{{ $s['text'] }} font-bold text-slate-900 leading-tight">Qurana</p>
            @if ($size !== 'sm')
                <p class="text-xs text-slate-500">Pendaftaran Santri</p>
            @endif
        </div>
    @endif
</div>
