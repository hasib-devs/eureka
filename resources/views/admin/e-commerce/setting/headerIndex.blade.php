@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Header &amp; Custom Code</h1>
                <p class="mt-1 text-sm text-slate-500">Inject tracking scripts, custom CSS/JS and storefront snippets</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.setting') }}" class="transition-colors hover:text-primary">Settings</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Header</li>
                </ol>
            </div>
        </div>
    </section>

    @php
        $control = 'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
        $codeControl = $control . ' font-mono text-xs leading-relaxed';
    @endphp

    <section class="mb-6">
        <form action="{{ routeHelper('setting') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="type" value="2">

            <div class="mx-auto w-full max-w-3xl space-y-4">

                {{-- Tracking & head scripts --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-code"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Head &amp; Tracking Scripts</h2>
                            <p class="text-xs text-slate-500">Injected into the storefront <code class="text-[11px]">&lt;head&gt;</code> and <code class="text-[11px]">&lt;body&gt;</code></p>
                        </div>
                    </div>
                    <div class="space-y-4 p-5">
                        <div>
                            <label for="header_code" class="mb-1 block text-sm font-medium text-slate-700">Custom Header Code</label>
                            <textarea name="header_code" id="header_code" rows="4" class="{{ $codeControl }}">{{ $header_code->value }}</textarea>
                        </div>
                        <div>
                            <label for="fb_pixel" class="mb-1 block text-sm font-medium text-slate-700">Facebook Pixel Code</label>
                            <textarea name="fb_pixel" id="fb_pixel" rows="4" class="{{ $codeControl }}">{{ $fb_pixel->value }}</textarea>
                        </div>
                        <div>
                            <label for="body_code" class="mb-1 block text-sm font-medium text-slate-700">Body Code</label>
                            <textarea name="body_code" id="body_code" rows="4" class="{{ $codeControl }}">{{ setting('body_code') ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Global CSS / JS --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-paint-brush"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Global CSS &amp; JS</h2>
                            <p class="text-xs text-slate-500">Site-wide styles and scripts, plus late-loading overrides</p>
                        </div>
                    </div>
                    <div class="space-y-4 p-5">
                        <div>
                            <label for="global_css" class="mb-1 block text-sm font-medium text-slate-700">Global CSS</label>
                            <textarea name="global_css" id="global_css" rows="4" placeholder="body{color:red;}" class="{{ $codeControl }}">{{ setting('global_css') ?? '' }}</textarea>
                        </div>
                        <div>
                            <label for="global_js" class="mb-1 block text-sm font-medium text-slate-700">Global JS</label>
                            <textarea name="global_js" id="global_js" rows="4" placeholder="console.log('Hello Lems');" class="{{ $codeControl }}">{{ setting('global_js') ?? '' }}</textarea>
                        </div>
                        <div>
                            <label for="override_css" class="mb-1 block text-sm font-medium text-slate-700">Bottom CSS &mdash; Override CSS</label>
                            <textarea name="override_css" id="override_css" rows="4" placeholder="a{color:red !important;}" class="{{ $codeControl }}">{{ setting('override_css') ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Below slider block --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-window-maximize"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Below-Slider HTML Block</h2>
                            <p class="text-xs text-slate-500">Custom HTML shown right under the home slider</p>
                        </div>
                    </div>
                    <div class="space-y-4 p-5">
                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50/60 px-4 py-3">
                            <div>
                                <label for="BELOW_SLIDER_HTML_CODE_STATUS" class="block text-sm font-medium text-slate-700">Below Slider Custom HTML Code Status</label>
                                <p class="mt-0.5 text-xs text-slate-500">Show or hide the block on the homepage</p>
                            </div>
                            <select name="BELOW_SLIDER_HTML_CODE_STATUS" id="BELOW_SLIDER_HTML_CODE_STATUS"
                                class="h-10 w-28 cursor-pointer rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                @if (setting('BELOW_SLIDER_HTML_CODE_STATUS') == 1)
                                    <option value="1">ON</option>
                                    <option value="0">OFF</option>
                                @else
                                    <option value="0">OFF</option>
                                    <option value="1">ON</option>
                                @endif
                            </select>
                        </div>
                        <div>
                            <label for="BELOW_SLIDER_HTML_CODE" class="mb-1 block text-sm font-medium text-slate-700">Custom HTML Code</label>
                            <textarea name="BELOW_SLIDER_HTML_CODE" id="BELOW_SLIDER_HTML_CODE" rows="4" class="{{ $codeControl }}">{{ setting('BELOW_SLIDER_HTML_CODE') ?? '<b>Hello www.asifulmamun.info.bd</b>' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Notice bar --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Notice Bar</h2>
                            <p class="text-xs text-slate-500">Announcement banner shown at the top of the storefront</p>
                        </div>
                    </div>
                    <div class="space-y-4 p-5">
                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-slate-50/60 px-4 py-3">
                            <div>
                                <label for="NOTICE_STATUS" class="block text-sm font-medium text-slate-700">Notice Status</label>
                                <p class="mt-0.5 text-xs text-slate-500">Show or hide the notice bar</p>
                            </div>
                            <select name="NOTICE_STATUS" id="NOTICE_STATUS"
                                class="h-10 w-28 cursor-pointer rounded-lg border border-slate-300 bg-white px-3 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                @if (setting('NOTICE_STATUS') == 1)
                                    <option value="1">ON</option>
                                    <option value="0">OFF</option>
                                @else
                                    <option value="0">OFF</option>
                                    <option value="1">ON</option>
                                @endif
                            </select>
                        </div>
                        <div>
                            <label for="CUSTOM_NOTICE" class="mb-1 block text-sm font-medium text-slate-700">Custom Notice</label>
                            <p class="mb-1 text-xs text-amber-600"><i class="fas fa-info-circle"></i> Rendered inside the Bootstrap container of the storefront.</p>
                            <textarea name="CUSTOM_NOTICE" id="CUSTOM_NOTICE" rows="4" class="{{ $codeControl }}">{{ setting('CUSTOM_NOTICE') ?? 'Today is offer for 30%' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Footer & app --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-shoe-prints"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold text-slate-900">Footer &amp; App Links</h2>
                            <p class="text-xs text-slate-500">Footer column content and mobile app link</p>
                        </div>
                    </div>
                    <div class="space-y-4 p-5">
                        <div>
                            <label for="FOOTER_COL_4_HTML" class="mb-1 block text-sm font-medium text-slate-700">Footer Column 4</label>
                            <textarea name="FOOTER_COL_4_HTML" id="FOOTER_COL_4_HTML" rows="4"
                                placeholder="Payment Support: Bkash, Roacket, Nagad" class="{{ $codeControl }}">{{ setting('FOOTER_COL_4_HTML') ?? '' }}</textarea>
                            <p class="mt-1 text-xs text-slate-500">
                                <a target="_blank" href="https://getbootstrap.com/docs/4.5/components/alerts/"
                                    class="font-medium text-primary hover:underline">
                                    Help for HTML snippet from Bootstrap V5.4.3 <i class="fas fa-external-link-alt text-[9px]"></i>
                                </a>
                            </p>
                        </div>
                        <div>
                            <label for="android_app" class="mb-1 block text-sm font-medium text-slate-700">Android App</label>
                            <input class="{{ $control }}" type="text" name="android_app" id="android_app"
                                value="{{ setting('android_app') ?? '' }}" placeholder="https://play.googe.com/">
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-check text-xs"></i>
                            Save Changes
                        </x-ui.button>
                    </div>
                </div>

            </div>
        </form>
    </section>

@endsection
