@props(['variant' => 'neutral', 'dot' => false])

@php
    $base = 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset';

    $variants = [
        'neutral' => 'bg-slate-100 text-slate-700 ring-slate-600/10',
        'primary' => 'bg-primary/10 text-primary ring-primary/20',
        'success' => 'bg-success/10 text-success ring-success/20',
        'danger'  => 'bg-danger/10 text-danger ring-danger/20',
        'warning' => 'bg-warning/10 text-warning ring-warning/20',
        'info'    => 'bg-info/10 text-info ring-info/20',
    ];

    $classes = $base.' '.($variants[$variant] ?? $variants['neutral']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>@if ($dot)<span class="h-1.5 w-1.5 shrink-0 rounded-full bg-current" aria-hidden="true"></span>@endif{{ $slot }}</span>
