@extends('layouts.admin.app')

@section('title', 'Slider List')

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Sliders</h1>
                <p class="mt-1 text-sm text-slate-500">Manage the homepage slider images</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ routeHelper('slider/create') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-primary px-4 text-sm font-medium text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50">
                    <i class="fas fa-plus text-xs"></i> Add Slider
                </a>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Sliders</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-5 py-4">
                <h2 class="text-base font-semibold text-slate-900">All Sliders</h2>
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ count($sliders) }} {{ Str::plural('slider', count($sliders)) }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[860px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="w-14 px-4 py-3.5">SL</th>
                            <th class="px-4 py-3.5">Image</th>
                            <th class="px-4 py-3.5">URL</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($sliders as $key => $data)
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4">
                                    <img src="/uploads/slider/{{ $data->image }}" alt="Slider"
                                        class="h-10 w-20 rounded-lg object-cover ring-1 ring-slate-200">
                                </td>
                                <td class="px-4 py-4 text-slate-500">{{ $data->url }}</td>
                                <td class="px-4 py-4">
                                    @if ($data->status)
                                        <x-ui.badge variant="success" dot>Active</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="danger" dot>Disable</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ routeHelper('slider/' . $data->id) }}"
                                            title="{{ $data->status ? 'Disable slider' : 'Activate slider' }}"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            @if ($data->status)
                                                <i class="fas fa-ban"></i>
                                            @else
                                                <i class="fas fa-check"></i>
                                            @endif
                                        </a>
                                        <a href="{{ routeHelper('slider/' . $data->id . '/edit') }}"
                                            title="Edit slider"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" id="deleteData" data-id="{{ $data->id }}"
                                            title="Delete slider"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <form id="delete-data-form-{{ $data->id }}"
                                            action="{{ routeHelper('slider/' . $data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-20 text-center">
                                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                        <i class="fas fa-images text-xl text-slate-300"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">No sliders found</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Get started by
                                        <a href="{{ routeHelper('slider/create') }}" class="text-primary hover:underline">adding your first slider</a>.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection
