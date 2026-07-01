@props(['label', 'value', 'icon' => null, 'color' => 'emerald'])

@php
    $iconColors = [
        'emerald' => 'from-emerald-500/10 to-emerald-600/5 text-emerald-600',
        'amber' => 'from-amber-500/10 to-amber-600/5 text-amber-600',
        'blue' => 'from-blue-500/10 to-blue-600/5 text-blue-600',
        'violet' => 'from-violet-500/10 to-violet-600/5 text-violet-600',
        'slate' => 'from-slate-500/10 to-slate-600/5 text-slate-600',
    ];
@endphp

<div class="admin-stat-card">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ $value }}</p>
        </div>
        @if ($icon)
            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br {{ $iconColors[$color] ?? $iconColors['emerald'] }}">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>
