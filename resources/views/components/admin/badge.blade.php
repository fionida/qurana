@props(['status'])

@if ($status === 'lunas')
    <span {{ $attributes->merge(['class' => 'admin-badge-success']) }}>Lunas</span>
@elseif ($status === 'pending')
    <span {{ $attributes->merge(['class' => 'admin-badge-warning']) }}>Pending</span>
@elseif ($status === 'active')
    <span {{ $attributes->merge(['class' => 'admin-badge-success']) }}>Aktif</span>
@elseif ($status === 'inactive')
    <span {{ $attributes->merge(['class' => 'admin-badge-neutral']) }}>Nonaktif</span>
@else
    <span {{ $attributes->merge(['class' => 'admin-badge-neutral']) }}>{{ $status }}</span>
@endif
