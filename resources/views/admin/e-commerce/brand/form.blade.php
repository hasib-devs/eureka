@extends('layouts.admin.app')

@section('title')
    @isset($brand)
        Edit Brand
    @else
        Add Brand
    @endisset
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }
    </style>
@endpush

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($brand)
                        Edit Brand
                    @else
                        Add Brand
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($brand)
                        Update the details of this brand
                    @else
                        Create a new product brand
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                @isset($brand)
                    <x-ui.button variant="outline" :href="routeHelper('brand/' . $brand->id)">
                        <i class="fas fa-eye text-xs"></i> Show
                    </x-ui.button>
                @endisset
                <x-ui.button variant="outline" :href="routeHelper('brand')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('brand') }}" class="transition-colors hover:text-primary">Brands</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($brand)
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
            <form action="{{ isset($brand) ? routeHelper('brand/' . $brand->id) : routeHelper('brand') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @isset($brand)
                    @method('PUT')
                @endisset

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-copyright"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">
                                @isset($brand)
                                    Edit Brand Details
                                @else
                                    Add New Brand
                                @endisset
                            </h3>
                            <p class="text-xs text-slate-500">Name, description, cover photo and visibility</p>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        {{-- Name --}}
                        <x-ui.input
                            name="name"
                            label="Name"
                            type="text"
                            :value="$brand->name ?? null"
                            placeholder="Write brand name"
                            required
                            autocomplete="off"
                        />

                        {{-- Description --}}
                        <x-ui.textarea name="description" label="Description" :rows="5" placeholder="Write category description">{{ $brand->description ?? old('description') }}</x-ui.textarea>

                        {{-- Cover Photo (Dropify — keep raw input, JS hooks preserved) --}}
                        <div>
                            <label for="cover_photo" class="mb-1 block text-sm font-medium text-slate-700">Cover Photo</label>
                            <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                class="block w-full @error('cover_photo') border border-danger rounded-lg @enderror"
                                data-default-file="@isset($brand)/uploads/brand/{{ $brand->cover_photo }}@endisset">
                            @error('cover_photo')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status toggle --}}
                        <div>
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="status" id="status" @checked($brand->status ?? true)>
                                <div class="peer relative h-6 w-11 rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:shadow after:transition-all peer-checked:bg-primary peer-checked:after:translate-x-5 peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50"></div>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </label>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="routeHelper('brand')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            @isset($brand)
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
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(function() {
            $('#cover_photo').dropify();
        });
    </script>
@endpush
