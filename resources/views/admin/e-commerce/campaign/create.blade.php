@extends('layouts.admin.app')

@section('title')
    @isset($campaing)
        Edit campaing
    @else
        Add campaing
    @endisset
@endsection

@push('css')
    <link rel="stylesheet" href="/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
    <style>
        /* Dropify widget internals — cannot be expressed as Tailwind utilities */
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }

        /* select2 widget internal — targets third-party pseudo-element scope */
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            display: none !important
        }
    </style>
@endpush

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                    @isset($campaing)
                        Edit Campaign
                    @else
                        Add Campaign
                    @endisset
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    @isset($campaing)
                        Update this campaign and manage its products
                    @else
                        Create a new promotional campaign
                    @endisset
                </p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="outline" :href="route('admin.campaing.index')">
                    <i class="fas fa-long-arrow-alt-left text-xs"></i> Back to List
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li><a href="{{ route('admin.campaing.index') }}" class="transition-colors hover:text-primary">Campaigns</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">
                        @isset($campaing)
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
        <div class="mx-auto w-full max-w-4xl">
            <form action="{{ isset($campaing) ? route('admin.campaing.update') : route('admin.campaing.store') }}"
                method="POST" enctype="multipart/form-data" class="space-y-4">

                @csrf

                {{-- Campaign details --}}
                <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                        <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-slate-900">Campaign details</h3>
                            <p class="text-xs text-slate-500">Name, cover photo and flash sale settings</p>
                        </div>
                    </div>

                    <div class="space-y-5 p-5">
                        {{-- Name --}}
                        <div>
                            <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name</label>
                            <input type="text" name="name" id="name" placeholder="Write campaing name"
                                class="block w-full rounded-lg border px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 @error('name') border-danger @else border-slate-300 @enderror"
                                value="{{ $campaing->name ?? old('name') }}" required autocomplete="off">
                            <input type="hidden" name="camid" value="{{ $campaing->id ?? '' }}" id="camid">
                            @error('name')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Cover Photo (Dropify hooks #cover_photo — keep raw input) --}}
                        <div>
                            <label for="cover_photo" class="mb-1 block text-sm font-medium text-slate-700">Cover Photo</label>
                            <input type="file" name="cover_photo" id="cover_photo" accept="image/*"
                                class="block w-full @error('cover_photo') border border-danger rounded-lg @enderror"
                                data-default-file="@isset($campaing) {{ asset('/') }}/uploads/campaign/{{ $campaing->cover_photo }}@endisset">
                            @error('cover_photo')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Status toggle --}}
                        <div>
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="status" id="status" @checked($campaing->status ?? true)>
                                <label for="status"
                                    class="relative inline-block h-6 w-11 cursor-pointer rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5">
                                </label>
                                <span class="text-sm font-medium text-slate-700">Status</span>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Flash sell toggle --}}
                        <div>
                            <div class="flex items-center gap-3">
                                <input type="checkbox" class="peer sr-only" name="flash" id="flash" @checked($campaing->is_flash ?? false)>
                                <label for="flash"
                                    class="relative inline-block h-6 w-11 cursor-pointer rounded-full bg-slate-200 transition-colors after:absolute after:left-0.5 after:top-0.5 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-transform peer-checked:bg-primary peer-checked:after:translate-x-5">
                                </label>
                                <span class="text-sm font-medium text-slate-700">Flash Sell</span>
                            </div>
                            @error('flash')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Flash end date (shown/hidden by JS via #flas_end innerHTML) --}}
                        <div id="flas_end">
                            @isset($campaing)
                                @if ($campaing->is_flash == 1)
                                    <label for="flash_end" class="mb-1 block text-sm font-medium text-slate-700">Flash end</label>
                                    <input type="datetime-local" name="flash_end" id="flash_end"
                                        class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                        value="{{ $campaing->end }}">
                                @endif
                            @endisset
                            @error('end')
                                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                @isset($campaing)
                    {{-- Campaign products --}}
                    <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex items-center gap-3 border-b border-slate-200 px-4 py-4">
                            <div class="grid h-9 w-9 place-items-center rounded-lg bg-primary/10 text-primary">
                                <i class="fas fa-box-open"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-semibold text-slate-900">Campaign products</h3>
                                <p class="text-xs text-slate-500">Pick products and set their campaign prices</p>
                            </div>
                        </div>

                        <div class="space-y-4 p-5">
                            {{-- Product select (select2 hooks .select2 / #product) --}}
                            <div>
                                <label for="product" class="mb-1 block text-sm font-medium text-slate-700">Select Product</label>
                                <select name="products[]" id="product" multiple data-placeholder="Select Product"
                                    class="select2 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                                    {{ isset($campaing) ? '' : 'required' }} data-selected-text-format="count">
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option
                                            @foreach ($campaing->campaing_products as $cam_product) {{ $product->id == $cam_product->product_id ? 'selected' : '' }} @endforeach
                                            value="{{ $product->id }}"> {{ $product->title }} </option>
                                    @endforeach
                                </select>
                                @error('categories')
                                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Selected products table (rows appended into #discount_table by AJAX) --}}
                            <div class="overflow-x-auto rounded-lg border border-slate-200">
                                <table class="w-full min-w-[640px] text-left text-sm">
                                    <thead>
                                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                            <th class="px-4 py-3.5">Cover Photo</th>
                                            <th class="px-4 py-3.5">Name</th>
                                            <th class="px-4 py-3.5">Regular Price</th>
                                            <th class="px-4 py-3.5">Campaing Price</th>
                                        </tr>
                                    </thead>
                                    <tbody id="discount_table" class="divide-y divide-slate-100">
                                        @foreach ($campaing->campaing_products as $cam_product)
                                            <tr class="group transition-colors hover:bg-slate-50/80">
                                                <td class="px-4 py-4">
                                                    <input type="hidden" name="adds[]" value="{{ $cam_product->cam_products->id }}">
                                                    <img src="{{ asset('uploads/product/' . $cam_product->cam_products->image) }}"
                                                        alt="Product Image" class="h-16 w-16 rounded-lg object-cover ring-1 ring-slate-200">
                                                </td>
                                                <td class="px-4 py-4 font-semibold text-slate-800">{{ $cam_product->cam_products->title }}</td>
                                                <td class="px-4 py-4 tabular-nums text-slate-500">{{ $cam_product->cam_products->regular_price }}</td>
                                                <td class="px-4 py-4">
                                                    <div class="flex items-center gap-2">
                                                        <input type="number" name="prices[]" value="{{ $cam_product->price }}"
                                                            class="block w-28 rounded-lg border border-slate-300 px-2 py-1 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">
                                                        <a href="{{ route('admin.campaing.remove', ['id' => $cam_product->id]) }}"
                                                            class="text-sm font-medium text-danger hover:underline">Remove</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endisset

                <div class="flex items-center justify-end gap-2 rounded-xl border border-slate-200 bg-white px-4 py-4 shadow-sm">
                    <x-ui.button variant="outline" :href="route('admin.campaing.index')">
                        Cancel
                    </x-ui.button>
                    <x-ui.button type="submit" variant="primary">
                        @isset($campaing)
                            <i class="fas fa-arrow-circle-up"></i>
                            Update
                        @else
                            <i class="fas fa-plus-circle"></i>
                            Submit
                        @endisset
                    </x-ui.button>
                </div>

            </form>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#product').on('change', function() {
                var product_ids = $('#product').val();
                var campaign_id = $('#camid').val();
                if (product_ids.length > 0) {
                    $.post('{{ route('admin.campaing.getData') }}', {
                        _token: '{{ csrf_token() }}',
                        product_ids: product_ids,
                        campaign_id: campaign_id
                    }, function(response) {
                        $('#discount_table').append(response);
                    });
                } else {
                    $('#discount_table').append(null);
                }
            });
        });
        $('#flash').on('click', function() {
            var flas = document.getElementById('flash');
            var flas_end = document.getElementById('flas_end');
            if (flas.checked == true) {
                flas_end.innerHTML =
                    '<label for="flash_end" class="mb-1 block text-sm font-medium text-slate-700">Flash end</label><input type="datetime-local" name="flash_end" id="flash_end" class="block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20">';
            } else {
                flas_end.innerHTML = "";
            }
        })
    </script>
@endpush
