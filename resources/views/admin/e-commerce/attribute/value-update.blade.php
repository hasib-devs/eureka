@extends('layouts.admin.app')

@section('title')
    Update attribute Value
@endsection

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Update Attribute Value</h1>
                <p class="mt-1 text-sm text-slate-500">Rename this attribute value</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('attribute/value/' . $value->attributes_id)">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to Values
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('attribute/list') }}" class="transition-colors hover:text-primary">Attributes</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Update value</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <form action="{{ route('admin.attribute.value.update') }}" method="POST">
                @csrf
                <input type="hidden" value="{{ $value->id }}" name="att">

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Value details</h3>
                            <p class="text-xs text-slate-500">Update the attribute value name</p>
                        </div>
                    </div>

                    <div class="p-5">
                        <x-ui.input
                            name="name"
                            label="Value Name"
                            type="text"
                            :value="$value->name ?? old('name')"
                            placeholder="Write attribute value name"
                            required
                            autocomplete="off"
                        />
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="routeHelper('attribute/value/' . $value->attributes_id)">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            <i class="fas fa-arrow-circle-up"></i>
                            Update
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
