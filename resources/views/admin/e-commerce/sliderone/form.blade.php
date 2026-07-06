@extends('layouts.admin.app')

{{-- NOTE: the controller passes the model as $slider for both edit (NewSliderController@edit)
     and never as $new_sliders_one — the old view checked $new_sliders_one, so edit mode never
     rendered (form always posted to the store route). Fixed to use $slider. --}}

@section('title')
    @isset($slider)
        Edit Slider One
    @else
        Add Slider One
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
                    @isset($slider)
                        Edit Slider One
                    @else
                        Add Slider One
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($slider)
                        Update the details of this slider
                    @else
                        Create a new secondary slider
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="routeHelper('sliderone')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ routeHelper('sliderone') }}" class="transition-colors hover:text-primary">Slider One</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($slider)
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
            <form action="{{ isset($slider) ? routeHelper('sliderone/' . $slider->nsid) : routeHelper('sliderone') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @isset($slider)
                    @method('PUT')
                @endisset

                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-images"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">
                                @isset($slider)
                                    Edit Slider One Details
                                @else
                                    Add New Slider One
                                @endisset
                            </h3>
                            <p class="text-xs text-slate-500">Image, target URL and placement options</p>
                        </div>
                    </div>

                    <div class="space-y-4 p-5">
                        {{-- Slider Image (Dropify — keep raw input, JS hooks preserved) --}}
                        <div>
                            <label for="image" class="mb-1 block text-sm font-medium text-slate-700">Slider Image</label>
                            <input type="file" name="image" id="image"
                                class="block w-full @error('image') border border-danger rounded-lg @enderror"
                                @isset($slider) data-default-file="{{ '/uploads/sliderOne/' . $slider->image }}" @endisset>
                            @error('image')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Slider URL --}}
                        <x-ui.input
                            name="url"
                            label="Slider Url"
                            type="text"
                            :value="$slider->url ?? old('url')"
                            placeholder="Write slider url"
                            required
                            autocomplete="off"
                        />

                        {{-- Status toggle --}}
                        <div>
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="status" id="status" @checked($slider->status ?? true)>
                                <div class="peer relative h-6 w-11 rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:shadow after:transition-all peer-checked:bg-primary peer-checked:after:translate-x-5 peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50"></div>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </label>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- is_feature toggle --}}
                        <div>
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="is_feature" id="is_feature" @checked($slider->is_feature ?? true)>
                                <div class="peer relative h-6 w-11 rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:shadow after:transition-all peer-checked:bg-primary peer-checked:after:translate-x-5 peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50"></div>
                                <span class="text-sm font-medium text-slate-700">Featured</span>
                            </label>
                            @error('is_feature')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- is_pop toggle --}}
                        <div>
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="is_pop" id="is_pop" @checked($slider->is_pop ?? true)>
                                <div class="peer relative h-6 w-11 rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:shadow after:transition-all peer-checked:bg-primary peer-checked:after:translate-x-5 peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50"></div>
                                <span class="text-sm font-medium text-slate-700">Popup</span>
                            </label>
                            @error('is_pop')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- is_sub toggle --}}
                        <div>
                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="is_sub" id="is_sub" @checked($slider->is_sub ?? true)>
                                <div class="peer relative h-6 w-11 rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:border after:border-slate-300 after:bg-white after:shadow after:transition-all peer-checked:bg-primary peer-checked:after:translate-x-5 peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/50"></div>
                                <span class="text-sm font-medium text-slate-700">Sub Slider</span>
                            </label>
                            @error('is_sub')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-slate-200 px-4 py-4">
                        <x-ui.button variant="outline" :href="routeHelper('sliderone')">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="primary">
                            @isset($slider)
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
        $(document).ready(function() {
            $('#image').dropify();
        });
    </script>
@endpush
