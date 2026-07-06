@extends('layouts.admin.app')

@section('title', 'Notice & Header Code')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Notice &amp; Header Code</h1>
                <p class="mt-1 text-sm text-slate-500">Manage the storefront notice bar and custom header HTML</p>
            </div>
            <div class="flex items-center gap-3">
                <a target="_blank" href="https://getbootstrap.com/docs/4.5/components/alerts/"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-700 shadow-sm transition-all hover:border-slate-400 hover:bg-slate-50 hover:shadow">
                    <i class="fas fa-question-circle text-xs"></i> HTML Snippet Help
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Notice</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <form action="{{ routeHelper('setting') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="type" value="11">

                <div class="space-y-4">

                    {{-- Below Slider custom HTML --}}
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                                <i class="fas fa-code"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Below Slider HTML Code</h3>
                                <p class="text-xs text-slate-500">Custom HTML rendered right below the homepage slider</p>
                            </div>
                        </div>

                        <div class="space-y-4 p-5">
                            <div>
                                <label for="BELOW_SLIDER_HTML_CODE_STATUS" class="mb-1 block text-sm font-medium text-slate-700">
                                    Below Slider Custom HTML Code Status
                                </label>
                                <select name="BELOW_SLIDER_HTML_CODE_STATUS" id="BELOW_SLIDER_HTML_CODE_STATUS"
                                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    <option value="1" @selected($BELOW_SLIDER_HTML_CODE_STATUS->value == 1)>ON</option>
                                    <option value="0" @selected($BELOW_SLIDER_HTML_CODE_STATUS->value != 1)>OFF</option>
                                </select>
                            </div>

                            <div>
                                <label for="BELOW_SLIDER_HTML_CODE" class="mb-1 block text-sm font-medium text-slate-700">
                                    Custom Html Code
                                </label>
                                <textarea name="BELOW_SLIDER_HTML_CODE" id="BELOW_SLIDER_HTML_CODE" rows="4"
                                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 font-mono text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ $BELOW_SLIDER_HTML_CODE->value }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Notice bar --}}
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Notice</h3>
                                <p class="text-xs text-slate-500">Announcement shown at the top of the storefront</p>
                            </div>
                        </div>

                        <div class="space-y-4 p-5">
                            <div>
                                <label for="NOTICE_STATUS" class="mb-1 block text-sm font-medium text-slate-700">
                                    Notice Status
                                </label>
                                <select name="NOTICE_STATUS" id="NOTICE_STATUS"
                                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                    <option value="1" @selected($NOTICE_STATUS->value == 1)>ON</option>
                                    <option value="0" @selected($NOTICE_STATUS->value != 1)>OFF</option>
                                </select>
                            </div>

                            <div>
                                <label for="CUSTOM_NOTICE" class="mb-1 block text-sm font-medium text-slate-700">
                                    Custom Notice
                                </label>
                                <p class="mb-1 text-xs text-slate-500">Rendered inside the storefront container</p>
                                <textarea name="CUSTOM_NOTICE" id="CUSTOM_NOTICE" rows="4"
                                    class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">{{ $CUSTOM_NOTICE->value }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                            <x-ui.button type="submit" variant="primary">
                                <i class="fas fa-arrow-circle-up"></i>
                                Update
                            </x-ui.button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </section>

@endsection
