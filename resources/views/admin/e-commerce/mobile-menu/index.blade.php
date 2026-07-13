@extends('layouts.admin.app')

@section('title', 'Mobile Menu')

@php
    $inp = 'w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary';
    $lbl = 'mb-1.5 block text-[11px] font-semibold uppercase tracking-wide text-slate-500';
    $socialMeta = [
        'call' => ['label' => 'Call', 'hint' => 'tel:01777549412', 'icon' => 'bx-phone'],
        'whatsapp' => ['label' => 'WhatsApp', 'hint' => 'https://wa.me/8801777549412', 'icon' => 'bxl-whatsapp'],
        'facebook' => ['label' => 'Facebook', 'hint' => 'https://facebook.com/...', 'icon' => 'bxl-facebook-circle'],
        'instagram' => ['label' => 'Instagram', 'hint' => 'https://instagram.com/...', 'icon' => 'bxl-instagram'],
        'tiktok' => ['label' => 'TikTok', 'hint' => 'https://tiktok.com/@...', 'icon' => 'bxl-tiktok'],
    ];
@endphp

@section('content')

    <form id="mm-form" method="POST" action="{{ route('admin.mobile-menu.update') }}" enctype="multipart/form-data"
        class="mx-auto max-w-3xl space-y-4">
        @csrf

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Mobile Menu</h1>
                <p class="mt-1 text-sm text-slate-500">Controls the full-screen mobile menu (phones/tablets only).</p>
            </div>
            <x-ui.button variant="primary" type="submit"><i class="fas fa-check"></i> Save</x-ui.button>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Background video --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-film"></i></div>
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Background Video</h3>
                    <p class="text-xs text-slate-500">.webm or .mp4 — plays muted behind the menu. Leave empty for a plain dark background.</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-4 p-5">
                <div class="grid h-24 w-40 place-items-center overflow-hidden rounded-lg border border-slate-200 bg-slate-900">
                    @if ($video)
                        <video src="{{ asset('uploads/menu/'.$video) }}" class="h-full w-full object-cover" muted loop playsinline></video>
                    @else
                        <span class="text-xs text-slate-400">No video</span>
                    @endif
                </div>
                <div>
                    <label class="cursor-pointer rounded-lg bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-200">
                        Upload Video<input type="file" name="menu_video" accept="video/webm,video/mp4,video/quicktime" class="hidden">
                    </label>
                    @if ($video)<p class="mt-2 text-xs text-slate-400">Current: {{ $video }}</p>@endif
                </div>
            </div>
        </div>

        {{-- Primary links --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-list"></i></div>
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Primary Links</h3>
                    <p class="text-xs text-slate-500">Rename, show/hide, and reorder. Links point to your existing pages.</p>
                </div>
            </div>
            <div id="links-wrap" class="divide-y divide-slate-100 p-2">
                @foreach ($links as $i => $link)
                    <div class="flex items-center gap-3 px-3 py-2.5" data-link-row>
                        <div class="flex flex-col">
                            <button type="button" class="text-slate-400 hover:text-primary" data-move="up"><i class="fas fa-chevron-up text-[10px]"></i></button>
                            <button type="button" class="text-slate-400 hover:text-primary" data-move="down"><i class="fas fa-chevron-down text-[10px]"></i></button>
                        </div>
                        <input type="hidden" data-name="key" name="links[{{ $i }}][key]" value="{{ $link['key'] }}">
                        <input type="text" data-name="label" name="links[{{ $i }}][label]" value="{{ $link['label'] }}" class="{{ $inp }} flex-1">
                        <span class="w-24 text-xs text-slate-400">{{ $link['key'] === 'categories' ? 'accordion' : $link['key'] }}</span>
                        <label class="inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-slate-600">
                            <input type="checkbox" data-name="visible" name="links[{{ $i }}][visible]" value="1" @checked($link['visible']) class="h-4 w-4 accent-primary">
                            Show
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Categories --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-tags"></i></div>
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Featured Categories</h3>
                    <p class="text-xs text-slate-500">Pick which categories show under the "Categories" accordion.</p>
                </div>
            </div>
            <div class="p-5">
                @if ($categories->isEmpty())
                    <p class="text-sm text-slate-400">No active categories.</p>
                @else
                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                        @foreach ($categories as $cat)
                            <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm hover:border-primary">
                                <input type="checkbox" name="categories[]" value="{{ $cat->id }}" @checked(in_array($cat->id, $curatedIds, true)) class="h-4 w-4 accent-primary">
                                {{ $cat->name }}
                            </label>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Socials --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-share-alt"></i></div>
                <div>
                    <h3 class="text-base font-semibold text-slate-900">Get in Touch</h3>
                    <p class="text-xs text-slate-500">Social / contact chips shown at the bottom of the menu.</p>
                </div>
            </div>
            <div class="space-y-3 p-5">
                @foreach ($socials as $i => $social)
                    @php $meta = $socialMeta[$social['type']]; @endphp
                    <div class="grid grid-cols-1 items-center gap-3 sm:grid-cols-[130px,1fr,auto]">
                        <div class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <i class="bx {{ $meta['icon'] }} text-lg"></i> {{ $meta['label'] }}
                        </div>
                        <input type="hidden" name="socials[{{ $i }}][type]" value="{{ $social['type'] }}">
                        <input type="text" name="socials[{{ $i }}][url]" value="{{ $social['url'] }}" placeholder="{{ $meta['hint'] }}" class="{{ $inp }}">
                        <label class="inline-flex cursor-pointer items-center gap-2 text-xs font-semibold text-slate-600">
                            <input type="checkbox" name="socials[{{ $i }}][visible]" value="1" @checked($social['visible']) class="h-4 w-4 accent-primary">
                            Show
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end pb-8">
            <x-ui.button variant="primary" type="submit"><i class="fas fa-check"></i> Save Mobile Menu</x-ui.button>
        </div>
    </form>

@endsection

@push('js')
    <script>
        (function () {
            // Reorder primary links (swap DOM nodes; indices are renormalised on submit).
            document.getElementById('links-wrap').addEventListener('click', function (e) {
                var btn = e.target.closest('[data-move]');
                if (!btn) return;
                var row = btn.closest('[data-link-row]');
                if (btn.dataset.move === 'up' && row.previousElementSibling) {
                    row.parentNode.insertBefore(row, row.previousElementSibling);
                } else if (btn.dataset.move === 'down' && row.nextElementSibling) {
                    row.parentNode.insertBefore(row.nextElementSibling, row);
                }
            });

            // Renormalise link[] indices to DOM order before submit.
            document.getElementById('mm-form').addEventListener('submit', function () {
                document.querySelectorAll('#links-wrap [data-link-row]').forEach(function (row, idx) {
                    row.querySelectorAll('[data-name]').forEach(function (input) {
                        input.setAttribute('name', 'links[' + idx + '][' + input.getAttribute('data-name') + ']');
                    });
                });
            });
        })();
    </script>
@endpush
