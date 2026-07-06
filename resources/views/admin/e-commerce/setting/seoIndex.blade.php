@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">SEO Settings</h1>
                <p class="mt-1 text-sm text-slate-500">Default title and meta tags for search engines and social sharing</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.setting') }}" class="transition-colors hover:text-primary">Settings</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">SEO</li>
                </ol>
            </div>
        </div>
    </section>

    @php
        $control = 'block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
    @endphp

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-search"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Meta Data</h2>
                        <p class="text-xs text-slate-500">Used when no page-specific meta data is available</p>
                    </div>
                </div>

                <form action="{{ routeHelper('setting') }}" method="POST">
                    @csrf
                    <input type="hidden" name="type" value="13">
                    @method('PUT')

                    <div class="space-y-4 p-5">
                        <div>
                            <label for="site_title" class="mb-1 block text-sm font-medium text-slate-700">
                                Site Default Title <span class="text-danger">*</span>
                            </label>
                            <input
                                name="site_title"
                                id="site_title"
                                type="text"
                                class="{{ $control }}"
                                placeholder="Lems - Multivendor E-Commerce Solution"
                                value="{{ setting('site_title') }}"
                                required
                            />
                        </div>

                        <div>
                            <label for="meta_description" class="mb-1 block text-sm font-medium text-slate-700">
                                Meta Description <span class="text-danger">*</span>
                            </label>
                            <textarea
                                name="meta_description"
                                id="meta_description"
                                rows="4"
                                class="{{ $control }}"
                                placeholder="Bangladeshi Best Ecommerce Software Lems, Optimized and super valuable extendable multivendor E-commerce solution"
                                required
                            >{{ setting('meta_description') }}</textarea>
                        </div>

                        <div>
                            <label for="meta_keywords" class="mb-1 block text-sm font-medium text-slate-700">
                                Meta Keywords <span class="text-danger">*</span>
                            </label>
                            <textarea
                                name="meta_keywords"
                                id="meta_keywords"
                                rows="4"
                                class="{{ $control }}"
                                placeholder="Quality Cloths, Backpack bangladesh, BD price Ba"
                                required
                            >{{ setting('meta_keywords') }}</textarea>
                        </div>

                        <a href="{{ route('admin.setting') }}#meta_img_wrap" class="inline-flex items-center gap-1 text-sm font-medium text-primary hover:underline">
                            Meta Image <i class="fas fa-caret-right"></i>
                        </a>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-5 py-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-check text-xs"></i>
                            Save Changes
                        </x-ui.button>
                    </div>
                </form>
            </div>
        </div>
    </section>

@endsection
