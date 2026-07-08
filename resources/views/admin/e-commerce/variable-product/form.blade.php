@extends('layouts.admin.app')

@section('title')
    @isset($product) Edit Variable Product @else Add Variable Product @endisset
@endsection

@push('css')
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/summernote/summernote-bs4.min.css">
    <style>
        .note-editor { box-shadow: none !important; }
    </style>
@endpush

@php
    $control = 'block w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20';
    $label = 'mb-1 block text-sm font-medium text-slate-700';
    $existingSpecs = isset($product) ? $product->specs : [];
    $existingLifestyle = isset($product) ? $product->images->where('section', 'lifestyle')->values() : collect();
@endphp

@section('content')
    {{-- Page header --}}
    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($product) Edit Variable Product @else Add Variable Product @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Each colour is a full variation — its own image, price and stock. Selecting a colour on the product page swaps all three.
                </p>
            </div>
            <div class="flex items-center gap-3">
                @isset($product)
                    <a href="{{ url('product/' . $product->slug) }}" target="_blank"
                        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-600 shadow-sm transition-all hover:border-primary hover:text-primary hover:shadow">
                        <i class="fas fa-eye text-xs"></i> Show
                    </a>
                @endisset
                <a href="{{ routeHelper('variable-products') }}"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 text-sm font-medium text-slate-600 shadow-sm transition-all hover:border-primary hover:text-primary hover:shadow">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </a>
            </div>
        </div>
    </section>

    <section class="mb-6">
        @if ($errors->any())
            <div class="mb-4">
                {!! implode('', $errors->all('<div class="mb-2 rounded-lg border border-danger/30 bg-danger/5 px-4 py-2 text-sm text-danger">:message</div>')) !!}
            </div>
        @endif

        <form class="space-y-4"
            action="{{ isset($product) ? routeHelper('variable-products/' . $product->id) : routeHelper('variable-products') }}"
            method="POST" enctype="multipart/form-data">
            @csrf
            @isset($product)
                @method('PUT')
            @endisset

            {{-- 1. Basic Information --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-info-circle"></i></span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Basic Information</h3>
                        <p class="text-xs text-slate-500">Name, code, category and visibility</p>
                    </div>
                </div>
                <div class="space-y-4 p-5">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="title" class="{{ $label }}">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" placeholder="Write product title"
                                class="{{ $control }} border-slate-300" value="{{ old('title', $product->title ?? '') }}">
                        </div>
                        <div>
                            <label for="sku" class="{{ $label }}">Product Code (SKU)</label>
                            <input type="text" name="sku" id="sku" placeholder="Product Code/SKU"
                                class="{{ $control }} border-slate-300" value="{{ old('sku', $product->sku ?? '') }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="category" class="{{ $label }}">Category <span class="text-danger">*</span></label>
                            <select name="categories[]" id="category" multiple class="{{ $control }} border-slate-300">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ isset($product) && $product->categories->contains('id', $category->id) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="sub_category" class="{{ $label }}">Sub Category</label>
                            <select name="sub_categories[]" id="sub_category" multiple class="{{ $control }} border-slate-300">
                                @if (isset($product))
                                    @foreach ($product->sub_categories as $sub)
                                        <option value="{{ $sub->id }}" selected>{{ $sub->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-6 pt-1">
                        <label class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-700">
                            <input type="checkbox" name="status" value="1"
                                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30"
                                {{ old('status', $product->status ?? true) ? 'checked' : '' }}>
                            Active (visible in the store)
                        </label>
                        <label class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-slate-700">
                            <input type="checkbox" name="is_shown_on_homepage" value="1"
                                class="h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary/30"
                                {{ old('is_shown_on_homepage', $product->is_shown_on_homepage ?? false) ? 'checked' : '' }}>
                            Show on homepage
                        </label>
                    </div>
                </div>
            </div>

            {{-- 2. Content --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-align-left"></i></span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Content</h3>
                        <p class="text-xs text-slate-500">Shown inside the product page accordion</p>
                    </div>
                </div>
                <div class="space-y-4 p-5">
                    <div>
                        <label for="short_description" class="{{ $label }}">The Design Story</label>
                        <textarea name="short_description" id="short_description" rows="3"
                            class="{{ $control }} border-slate-300 summernote">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="full_description" class="{{ $label }}">Technical Details</label>
                        <textarea name="full_description" id="full_description" rows="5"
                            class="{{ $control }} border-slate-300 summernote">{{ old('full_description', $product->full_description ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="shipping_concierge" class="{{ $label }}">Shipping &amp; Concierge</label>
                        <textarea name="shipping_concierge" id="shipping_concierge" rows="4"
                            class="{{ $control }} border-slate-300 summernote">{{ old('shipping_concierge', $product->shipping_concierge ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="warranty_returns" class="{{ $label }}">Warranty &amp; Returns</label>
                        <textarea name="warranty_returns" id="warranty_returns" rows="4"
                            class="{{ $control }} border-slate-300 summernote">{{ old('warranty_returns', $product->warranty_returns ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- 3. Specifications --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-4 py-4">
                    <div class="flex items-center gap-3">
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-list-ul"></i></span>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Specifications</h3>
                            <p class="text-xs text-slate-500">Label/value pairs shown in the specs grid</p>
                        </div>
                    </div>
                    <button type="button" id="addSpecRow"
                        class="inline-flex h-9 items-center gap-2 rounded-lg border border-primary/40 bg-primary/5 px-3 text-sm font-medium text-primary transition-colors hover:bg-primary/10">
                        <i class="fas fa-plus text-xs"></i> Add Row
                    </button>
                </div>
                <div class="space-y-3 p-5" id="specRows">
                    @forelse ($existingSpecs as $spec)
                        <div class="spec-row grid grid-cols-1 gap-3 md:grid-cols-[1fr_1fr_auto]">
                            <input type="text" name="spec_labels[]" placeholder="Label" class="{{ $control }} border-slate-300" value="{{ $spec['label'] }}">
                            <input type="text" name="spec_values[]" placeholder="Value" class="{{ $control }} border-slate-300" value="{{ $spec['value'] }}">
                            <button type="button" class="remove-spec-row inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">Remove</button>
                        </div>
                    @empty
                        <div class="spec-row grid grid-cols-1 gap-3 md:grid-cols-[1fr_1fr_auto]">
                            <input type="text" name="spec_labels[]" placeholder="Label (e.g. Light Source)" class="{{ $control }} border-slate-300">
                            <input type="text" name="spec_values[]" placeholder="Value (e.g. ST64 Vintage LED)" class="{{ $control }} border-slate-300">
                            <button type="button" class="remove-spec-row inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">Remove</button>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- 4. Variations (Colours) --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-palette"></i></span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Variations (Colours)</h3>
                        <p class="text-xs text-slate-500">
                            Each colour needs its own image, price and stock. The first row is the default shown on the page.
                            Missing a colour? Create it first in
                            <a href="{{ routeHelper('color') }}" target="_blank" class="font-medium text-primary hover:underline">Colors</a>.
                        </p>
                    </div>
                </div>
                <div class="space-y-4 p-5">
                    <div>
                        <label for="select_color" class="{{ $label }}">Add a Colour</label>
                        <select id="select_color" class="{{ $control }} border-slate-300">
                            <option value="">Select colour to add</option>
                            @foreach ($colors as $color)
                                <option value="{{ $color->id }}" data-name="{{ $color->name }}" data-code="{{ $color->code ?? '' }}">
                                    {{ $color->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-3" id="colorRows">
                        @isset($colors_product)
                            @foreach ($colors_product as $i => $cp)
                                <div class="color-row rounded-lg border border-slate-200 p-3">
                                    <div class="grid grid-cols-1 items-center gap-3 md:grid-cols-[auto_1fr_1fr_1fr_auto]">
                                        <span class="inline-block h-8 w-8 rounded-full border border-slate-200" style="background: {{ $cp->code ?? '#ddd' }}"></span>
                                        <input type="hidden" class="color-id-input" name="colors[{{ $i }}]" value="{{ $cp->color_id }}">
                                        <input type="text" readonly value="{{ $cp->name }}" class="{{ $control }} border-slate-300 bg-slate-50">
                                        <input type="number" step="0.01" name="color_prices[{{ $i }}]" placeholder="Price (৳)" value="{{ $cp->price ?? 0 }}" class="{{ $control }} border-slate-300">
                                        <input type="number" name="color_quantits[{{ $i }}]" placeholder="Stock" value="{{ $cp->qnty ?? 0 }}" class="{{ $control }} border-slate-300">
                                        <button type="button" class="remove-color-row inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">Remove</button>
                                    </div>
                                    <div class="mt-3 flex items-center gap-3">
                                        @if ($cp->image)
                                            <img src="{{ asset('uploads/product/' . $cp->image) }}" alt="" class="h-16 w-16 rounded-md border border-slate-200 object-cover">
                                        @endif
                                        <input type="hidden" name="existing_color_images[{{ $i }}]" value="{{ $cp->image }}">
                                        <div class="flex-1">
                                            <label class="mb-1 block text-xs text-slate-400">Colour image — leave empty to keep the current one</label>
                                            <input type="file" name="color_images[{{ $i }}]" accept="image/*" class="{{ $control }} border-slate-300">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endisset
                    </div>
                </div>
            </div>

            {{-- 5. Media --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                    <span class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary"><i class="fas fa-photo-video"></i></span>
                    <div>
                        <h3 class="text-base font-semibold text-slate-900">Media</h3>
                        <p class="text-xs text-slate-500">Lifestyle carousel and product video. The main gallery comes from the colour images above.</p>
                    </div>
                </div>
                <div class="space-y-6 p-5">
                    {{-- Lifestyle images --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <div>
                                <label class="{{ $label }}">Lifestyle Images ("Styled By Light" carousel)</label>
                                <p class="text-xs text-slate-400">Each tile gets a small Tag and a Caption.</p>
                            </div>
                            <button type="button" id="addLifestyleRow"
                                class="inline-flex h-9 items-center gap-2 rounded-lg border border-primary/40 bg-primary/5 px-3 text-sm font-medium text-primary transition-colors hover:bg-primary/10">
                                <i class="fas fa-plus text-xs"></i> Add Image
                            </button>
                        </div>
                        <div class="space-y-3" id="lifestyleRows"></div>
                        @if ($existingLifestyle->isNotEmpty())
                            <div class="mt-4 space-y-3">
                                @foreach ($existingLifestyle as $img)
                                    <div class="grid grid-cols-1 items-center gap-3 md:grid-cols-[auto_1fr_1fr_auto]">
                                        <img src="{{ asset('uploads/product/' . $img->name) }}" alt="" class="h-24 w-[72px] rounded-lg border border-slate-200 object-cover">
                                        <input type="text" name="existing_lifestyle[{{ $img->id }}][tag]" placeholder="Tag" class="{{ $control }} border-slate-300" value="{{ $img->tag }}">
                                        <input type="text" name="existing_lifestyle[{{ $img->id }}][caption]" placeholder="Caption" class="{{ $control }} border-slate-300" value="{{ $img->caption }}">
                                        <a href="{{ routeHelper('idelte/' . $img->id) }}" onclick="return confirm('Delete this lifestyle image?')"
                                            class="inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">Delete</a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Video --}}
                    <div class="border-t border-slate-100 pt-5">
                        <label class="{{ $label }}">Product Video</label>
                        <p class="mb-2 text-xs text-slate-400">First tile of the carousel — plays automatically (muted). MP4, 3:4 portrait.</p>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <input type="file" name="video" accept="video/*" class="{{ $control }} border-slate-300">
                                @if (isset($product) && $product->video)
                                    <label class="mt-2 inline-flex cursor-pointer items-center gap-2 text-sm text-slate-600">
                                        <input type="checkbox" name="remove_video" value="1" class="h-4 w-4 rounded border-slate-300 text-danger focus:ring-danger/30">
                                        Remove current video ({{ $product->video }})
                                    </label>
                                @endif
                            </div>
                            <div>
                                <input type="file" name="video_thumb" accept="image/*" class="{{ $control }} border-slate-300">
                                <p class="mt-1 text-xs text-slate-400">Poster image shown before the video plays</p>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="yvideo" class="{{ $label }}">YouTube URL (optional)</label>
                            <input type="text" name="yvideo" id="yvideo" placeholder="https://www.youtube.com/watch?v=..."
                                class="{{ $control }} border-slate-300" value="{{ old('yvideo', $product->yvideo ?? '') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-primary px-8 text-sm font-semibold text-white shadow-sm transition-all hover:opacity-90 hover:shadow">
                    <i class="fas fa-save text-xs"></i>
                    @isset($product) Update Variable Product @else Save Variable Product @endisset
                </button>
            </div>
        </form>
    </section>
@endsection

@push('js')
    <script src="/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="/assets/plugins/summernote/summernote-bs4.min.js"></script>
    <script>
        $(function () {
            const control = "{{ $control }}";

            // ── Specifications repeater ──────────────────────────────────
            $('#addSpecRow').on('click', function () {
                $('#specRows').append(`
                    <div class="spec-row grid grid-cols-1 gap-3 md:grid-cols-[1fr_1fr_auto]">
                        <input type="text" name="spec_labels[]" placeholder="Label" class="${control} border-slate-300">
                        <input type="text" name="spec_values[]" placeholder="Value" class="${control} border-slate-300">
                        <button type="button" class="remove-spec-row inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">Remove</button>
                    </div>`);
            });
            $(document).on('click', '.remove-spec-row', function () { $(this).closest('.spec-row').remove(); });

            // ── Colour variation rows ────────────────────────────────────
            let colorIdx = {{ isset($colors_product) ? $colors_product->count() : 0 }};
            $('#select_color').on('change', function () {
                const opt = this.selectedOptions[0];
                if (!opt || !opt.value) return;
                // no duplicate colours
                let dup = false;
                $('#colorRows .color-id-input').each(function () { if (this.value === opt.value) dup = true; });
                if (dup) { this.value = ''; return; }

                const idx = colorIdx++;
                $('#colorRows').append(`
                    <div class="color-row rounded-lg border border-slate-200 p-3">
                        <div class="grid grid-cols-1 items-center gap-3 md:grid-cols-[auto_1fr_1fr_1fr_auto]">
                            <span class="inline-block h-8 w-8 rounded-full border border-slate-200" style="background: ${opt.dataset.code || '#ddd'}"></span>
                            <input type="hidden" class="color-id-input" name="colors[${idx}]" value="${opt.value}">
                            <input type="text" readonly value="${opt.dataset.name}" class="${control} border-slate-300 bg-slate-50">
                            <input type="number" step="0.01" name="color_prices[${idx}]" placeholder="Price (৳)" value="0" class="${control} border-slate-300">
                            <input type="number" name="color_quantits[${idx}]" placeholder="Stock" value="0" class="${control} border-slate-300">
                            <button type="button" class="remove-color-row inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">Remove</button>
                        </div>
                        <div class="mt-3 flex items-center gap-3">
                            <div class="flex-1">
                                <label class="mb-1 block text-xs text-slate-400">Colour image</label>
                                <input type="file" name="color_images[${idx}]" accept="image/*" class="${control} border-slate-300">
                            </div>
                        </div>
                    </div>`);
                this.value = '';
            });
            $(document).on('click', '.remove-color-row', function () { $(this).closest('.color-row').remove(); });

            // ── Lifestyle image repeater ─────────────────────────────────
            $('#addLifestyleRow').on('click', function () {
                $('#lifestyleRows').append(`
                    <div class="lifestyle-row grid grid-cols-1 items-center gap-3 md:grid-cols-[1fr_1fr_1fr_auto]">
                        <input type="file" name="lifestyle_images[]" accept="image/*" class="${control} border-slate-300">
                        <input type="text" name="lifestyle_tags[]" placeholder="Tag" class="${control} border-slate-300">
                        <input type="text" name="lifestyle_captions[]" placeholder="Caption" class="${control} border-slate-300">
                        <button type="button" class="remove-lifestyle-row inline-flex h-9 items-center rounded-lg border border-slate-300 px-3 text-sm text-slate-500 transition-colors hover:border-danger hover:text-danger">Remove</button>
                    </div>`);
            });
            $(document).on('click', '.remove-lifestyle-row', function () { $(this).closest('.lifestyle-row').remove(); });

            // ── Sub-categories load on category change ──────────────────
            $(document).on('change', '#category', function () {
                const values = Array.from(document.getElementById('category').selectedOptions).map(o => o.value);
                $.ajax({
                    type: 'POST',
                    url: '/admin/get/sub-categories',
                    data: { ids: values, _token: $('meta[name="csrf-token"]').attr('content') },
                    dataType: 'JSON',
                    success: function (response) {
                        let data = '';
                        $.each(response, function (key, val) { data += '<option value="' + val.id + '">' + val.name + '</option>'; });
                        $('#sub_category').html(data);
                        try { $('#sub_category').select2(); } catch (e) { console.warn('select2 unavailable', e); }
                    }
                });
            });

            // ── UI enhancements — each isolated ──────────────────────────
            try { $('#category, #sub_category').select2(); } catch (e) { console.warn('select2 unavailable', e); }
            try {
                $('.summernote').summernote({
                    height: 160,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link']],
                        ['view', ['codeview']],
                    ],
                });
            } catch (e) { console.warn('summernote unavailable', e); }
        });
    </script>
@endpush
