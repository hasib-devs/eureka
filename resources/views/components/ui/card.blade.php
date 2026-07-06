@props(['header' => null, 'footer' => null])

<div {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white shadow-sm']) }}>
    @isset($header)
        <div class="border-b border-slate-200 px-5 py-4 font-medium text-slate-900">{{ $header }}</div>
    @endisset

    <div class="p-5">{{ $slot }}</div>

    @isset($footer)
        <div class="border-t border-slate-200 px-5 py-4">{{ $footer }}</div>
    @endisset
</div>
