@extends('layouts.admin.app')

@section('title')
    @isset($size)
        Edit Size
    @else
        Add Size
    @endisset
@endsection

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($size)
                        Edit Size
                    @else
                        Add Size
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($size)
                        Update the details of this size
                    @else
                        Create a new size option for products
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('size')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('size') }}" class="transition-colors hover:text-primary">Sizes</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($size)
                            Edit
                        @else
                            Add
                        @endisset
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <form action="{{ isset($size) ? routeHelper('size/' . $size->id) : routeHelper('size') }}"
                method="POST">
                @csrf
                @isset($size)
                    @method('PUT')
                @endisset

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-ruler-combined"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Size details</h3>
                            <p class="text-xs text-slate-500">Name, description and status</p>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        <x-ui.input
                            name="name"
                            label="Size Name"
                            placeholder="Write size name"
                            :value="$size->name ?? old('name')"
                            required
                            autocomplete="off"
                        />

                        <x-ui.textarea name="description" label="Description" :rows="5" placeholder="Write size description">{{ $size->description ?? old('description') }}</x-ui.textarea>

                        {{-- Status toggle --}}
                        <div>
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="status" id="status" @checked($size->status ?? true)>
                                <label for="status"
                                    class="relative inline-block h-6 w-11 cursor-pointer rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5">
                                </label>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="routeHelper('size')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            @isset($size)
                                <i class="fas fa-arrow-circle-up"></i>
                                Update
                            @else
                                <i class="fas fa-plus-circle"></i>
                                Submit
                            @endisset
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
