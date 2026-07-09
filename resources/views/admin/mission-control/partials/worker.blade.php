{{-- Robo — the Wedevs AI worker. $size = svg width, $busy = fast bob + tilt. --}}
@php
    $size = $size ?? 40;
    $busy = $busy ?? false;
    $h = (int) round($size * 0.95);
@endphp
<span class="mc-bot {{ $busy ? 'mc-bot--busy' : '' }}">
    <svg width="{{ $size }}" height="{{ $h }}" viewBox="0 0 64 60" fill="none" aria-hidden="true">
        <line x1="32" y1="8" x2="32" y2="15" stroke="#6e756d" stroke-width="2.2"/>
        <circle class="mc-bot-ant" cx="32" cy="6" r="3.2" fill="#9efb25"/>
        <rect x="9.5" y="25" width="5" height="9" rx="2.5" fill="#3a3e40"/>
        <rect x="49.5" y="25" width="5" height="9" rx="2.5" fill="#3a3e40"/>
        <rect x="14" y="15" width="36" height="29" rx="12" fill="#f2f4ee"/>
        <rect x="20" y="23" width="24" height="13" rx="6.5" fill="#17191a"/>
        <circle class="mc-bot-eye" cx="27.5" cy="29.5" r="2.6" fill="#9efb25"/>
        <circle class="mc-bot-eye" cx="36.5" cy="29.5" r="2.6" fill="#9efb25"/>
        <rect x="23" y="47" width="18" height="8" rx="4" fill="#33373a"/>
    </svg>
    <i class="mc-bot-shadow"></i>
</span>
