@extends('layouts.admin.app')

@section('title')
    Add attribute Value
@endsection

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Attribute Values</h1>
                <p class="mt-1 text-sm text-slate-500">Manage values for the "{{ $attribute->name }}" attribute</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('attribute/list')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('attribute/list') }}" class="transition-colors hover:text-primary">Attributes</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Values</li>
                </ol>
            </div>
        </div>
    </section>

    {{-- Add value form --}}
    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <form action="{{ route('admin.attribute.value.store') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $attribute->id }}" name="att">

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-plus"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Add new value</h3>
                            <p class="text-xs text-slate-500">Add a value to "{{ $attribute->name }}"</p>
                        </div>
                    </div>

                    <div class="p-5">
                        <x-ui.input
                            name="name"
                            label="Value Name"
                            type="text"
                            placeholder="Write attribute value name"
                            :value="old('name')"
                            required
                            autocomplete="off"
                        />
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-plus-circle"></i>
                            Submit
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    {{-- Attribute values list --}}
    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-5 py-4">
                    <h2 class="text-base font-semibold text-slate-900">All Values</h2>
                    <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                        {{ count($values) }} {{ Str::plural('value', count($values)) }}
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[480px] text-left text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                <th class="w-14 px-4 py-3.5">SL</th>
                                <th class="px-4 py-3.5">Name</th>
                                <th class="px-4 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($values as $key => $data)
                                <tr class="group transition-colors hover:bg-slate-50/80">
                                    <td class="px-4 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-4 font-semibold text-slate-800">{{ $data->name }}</td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <a href="{{ routeHelper('attribute/value/' . $data->id . '/edit') }}"
                                                title="Edit value"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="javascript:void(0)" id="deleteData" data-id="{{ $data->id }}"
                                                title="Delete value"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            <form id="delete-data-form-{{ $data->id }}"
                                                action="{{ routeHelper('attribute/value/delete/' . $data->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-5 py-20 text-center">
                                        <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                            <i class="fas fa-tags text-xl text-slate-300"></i>
                                        </div>
                                        <p class="font-semibold text-slate-700">No values found</p>
                                        <p class="mt-1 text-sm text-slate-500">Use the form above to add the first value.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
