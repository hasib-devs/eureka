@extends('layouts.admin.app')

@section('title', 'docs')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Documentation</h1>
                <p class="mt-1 text-sm text-slate-500">Recommended image dimensions for storefront assets</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Docs</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-2xl">
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-ruler-combined"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Image Size Guide</h2>
                        <p class="text-xs text-slate-500">Upload assets at these sizes for the sharpest results</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[420px] text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                <th class="px-4 py-3.5">Asset</th>
                                <th class="px-4 py-3.5 text-right">Recommended Size (px)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 font-semibold text-slate-800">Logo</td>
                                <td class="px-4 py-4 text-right tabular-nums text-slate-500">200 &times; 30</td>
                            </tr>
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 font-semibold text-slate-800">Banner</td>
                                <td class="px-4 py-4 text-right tabular-nums text-slate-500">950 &times; 480</td>
                            </tr>
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 font-semibold text-slate-800">Mini Banner</td>
                                <td class="px-4 py-4 text-right tabular-nums text-slate-500">650 &times; 180</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection
