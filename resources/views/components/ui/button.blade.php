@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-all duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 active:scale-[.98] disabled:opacity-50 disabled:pointer-events-none';

    $variants = [
        'primary'   => 'bg-primary text-white shadow-sm hover:bg-primary-600 hover:shadow',
        'secondary' => 'bg-secondary text-white shadow-sm hover:bg-slate-700 hover:shadow',
        'ghost'     => 'bg-transparent text-primary hover:bg-primary-50',
        'outline'   => 'border border-slate-300 bg-white text-slate-700 shadow-sm hover:border-slate-400 hover:bg-slate-50',
        'danger'    => 'bg-danger text-white shadow-sm hover:opacity-90 hover:shadow',
        'success'   => 'bg-success text-white shadow-sm hover:opacity-90 hover:shadow',
        'warning'   => 'bg-tile-warning text-black shadow-sm hover:opacity-90 hover:shadow',
        'info'      => 'bg-tile-info text-white shadow-sm hover:opacity-90 hover:shadow',
    ];

    $sizes = [
        'sm' => 'h-8 px-3 text-sm',
        'md' => 'h-10 px-4 text-sm',
        'lg' => 'h-12 px-6 text-base',
    ];

    $classes = $base.' '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
