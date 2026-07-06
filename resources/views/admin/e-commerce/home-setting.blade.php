@extends('layouts.admin.app')

@section('title', 'Settings')

@push('css')
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            display: none !important
        }
    </style>
@endpush

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Home Categories</h1>
                <p class="mt-1 text-sm text-slate-500">Choose which categories appear on the homepage sections</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.setting') }}" class="transition-colors hover:text-primary">Settings</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Home Categories</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="mx-auto w-full max-w-3xl">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-5 py-4">
                    <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Homepage Category Sections</h2>
                        <p class="text-xs text-slate-500">Select one or more categories per section, or "Hide" to remove it</p>
                    </div>
                </div>

                <form action="{{ routeHelper('setting') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="type" value="3">

                    <div class="space-y-4 p-5">
                        <div>
                            <label for="mega" class="mb-1 block text-sm font-medium text-slate-700">Mega Category</label>
                            <select name="mega[]" id="mega" class="select2 form-control w-full" multiple required>
                                <option value="">Hide</option>
                                @foreach (\App\Models\Category::where('status', true)->get() as $category)
                                    <option value="{{ $category->id }}"
                                        @if (!empty($mega_cat->value)) @foreach (json_decode($mega_cat->value) as $c) @if ($category->id == $c)Selected @endif
                                        @endforeach
                                @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="sub" class="mb-1 block text-sm font-medium text-slate-700">Sub Category</label>
                            <select name="sub[]" id="sub" class="select2 form-control w-full" multiple required>
                                <option value="">Hide</option>
                                @foreach (\App\Models\SubCategory::where('status', true)->get() as $category)
                                    <option value="{{ $category->id }}"
                                        @if (!empty($sub_cat->value)) @foreach (json_decode($sub_cat->value) as $c) @if ($category->id == $c)Selected @endif
                                        @endforeach
                                @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="mini" class="mb-1 block text-sm font-medium text-slate-700">Mini Category</label>
                            <select name="mini[]" id="mini" class="select2 form-control w-full" multiple required>
                                <option value="">Hide</option>
                                @foreach (\App\Models\miniCategory::where('status', true)->get() as $category)
                                    <option value="{{ $category->id }}"
                                        @if (!empty($mini_cat->value)) @foreach (json_decode($mini_cat->value) as $c) @if ($category->id == $c)Selected @endif
                                        @endforeach
                                @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="extra" class="mb-1 block text-sm font-medium text-slate-700">Extra Mini Category</label>
                            <select name="extra[]" class="select2 form-control w-full" id="extra" multiple required>
                                <option value="">Hide</option>
                                @foreach (\App\Models\ExtraMiniCategory::where('status', true)->get() as $category)
                                    <option value="{{ $category->id }}"
                                        @if (!empty($extra_cat->value)) @foreach (json_decode($extra_cat->value) as $c) @if ($category->id == $c)Selected @endif
                                        @endforeach
                                @endif>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
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

@push('js')
    <script src="/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(function() {
            $('#cover_photo').dropify();
            $('.select2').select2();
        });
    </script>
@endpush
