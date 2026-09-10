@props([
    'programOptions',
    'gelombangOptions',
    'selectedProgram' => null,
    'selectedGelombang' => null,
    'action' => null,
    'method' => 'GET',
    'submitLabel' => 'Terapkan',
    'showSubmit' => true,
    'preserve' => [],
])

@php
    $action = $action ?? url()->current();
@endphp

<form method="{{ $method }}" action="{{ $action }}" {{ $attributes->merge(['class' => 'flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-end']) }}>
    @foreach ($preserve as $name => $value)
        @if ($value !== null && $value !== '')
            <input type="hidden" name="{{ $name }}" value="{{ $value }}">
        @endif
    @endforeach

    @include('admin.partials.program-gelombang-fields', [
        'programOptions' => $programOptions,
        'gelombangOptions' => $gelombangOptions,
        'selectedProgram' => $selectedProgram,
        'selectedGelombang' => $selectedGelombang,
    ])

    @if ($showSubmit)
        <button type="submit" class="admin-btn-primary !py-2 sm:shrink-0">{{ $submitLabel }}</button>
    @endif

    {{ $slot }}
</form>
