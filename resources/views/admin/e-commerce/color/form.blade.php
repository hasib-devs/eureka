@extends('layouts.admin.app')

@section('title')
    @isset($color)
        Edit Color
    @else
        Add Color
    @endisset
@endsection

@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css"
        rel="stylesheet">
@endpush

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($color)
                        Edit Color
                    @else
                        Add Color
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($color)
                        Update the details of this color
                    @else
                        Create a new color option for products
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('color')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('color') }}" class="transition-colors hover:text-primary">Colors</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($color)
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
            <form action="{{ isset($color) ? routeHelper('color/' . $color->id) : routeHelper('color') }}"
                method="POST">
                @csrf
                @isset($color)
                    @method('PUT')
                @endisset

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-palette"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Color details</h3>
                            <p class="text-xs text-slate-500">Name, color code and description</p>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                            <input type="text" name="name" id="name" placeholder="Write name"
                                class="block w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 @error('name') border-danger @else border-slate-300 @enderror"
                                value="{{ $color->name ?? old('name') }}" required autocomplete="off">
                            @error('name')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Color picker (bootstrap-colorpicker hooks #color) --}}
                        <div>
                            <label for="color" class="mb-1 block text-sm font-medium text-slate-700">Choice Color</label>
                            <input type="text" name="color" id="color"
                                class="block w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 @error('color') border-danger @else border-slate-300 @enderror"
                                placeholder="Choice color to color picker" value="{{ $color->code ?? '' }}" required
                                autocomplete="off">
                            @error('color')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label for="description" class="mb-1 block text-sm font-medium text-slate-700">Description</label>
                            <textarea name="description" id="description" cols="5" rows="4"
                                placeholder="Write category description"
                                class="block w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 @error('description') border-danger @else border-slate-300 @enderror">{{ $color->description ?? old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status toggle --}}
                        <div>
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="status" id="status" @checked($color->status ?? true)>
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
                        <x-ui.button variant="outline" :href="routeHelper('color')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            @isset($color)
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

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js">
    </script>
    <script>
        $('#color').colorpicker();
    </script>
@endpush
