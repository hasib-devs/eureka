@props([
    'variant' => 'info',
    'value' => '',
    'label' => '',
    'icon' => '',
    'href' => null,
])

@php
    $tiles = [
        'info'    => 'bg-tile-info',
        'warning' => 'bg-tile-warning',
        'success' => 'bg-tile-success',
        'primary' => 'bg-tile-primary',
        'danger'  => 'bg-tile-danger',
    ];
    $bg = $tiles[$variant] ?? $tiles['info'];
@endphp

<div {{ $attributes->merge(['class' => "relative overflow-hidden rounded-lg text-white shadow-sm $bg"]) }}>
    <div class="p-4">
        <h3 class="text-3xl font-bold leading-none">{{ $value }}</h3>
        <p class="mt-1 text-sm">{{ $label }}</p>
    </div>

    @if ($icon)
        <i class="{{ $icon }} pointer-events-none absolute right-3 top-3 text-5xl text-white/30"></i>
    @endif

    @if ($href)
        <a href="{{ $href }}" class="flex items-center justify-center gap-1 bg-black/10 py-2 text-sm text-white/90 transition-colors hover:bg-black/20">
            More info <i class="fas fa-arrow-circle-right"></i>
        </a>
    @endif
</div>
