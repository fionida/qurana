@props(['href', 'active' => false])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'admin-nav-item ' . ($active ? 'admin-nav-item-active' : 'admin-nav-item-inactive')]) }}>
    <span class="flex h-8 w-8 items-center justify-center rounded-lg {{ $active ? 'bg-emerald-700/50' : 'bg-emerald-950/30' }}">
        {{ $icon }}
    </span>
    <span>{{ $slot }}</span>
</a>
