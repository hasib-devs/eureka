@extends('layouts.admin.app')

@section('title', 'Disable Product List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
@endpush

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Disabled Products</h1>
                <p class="mt-1 text-sm text-slate-500">Products currently hidden from the storefront</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui.button variant="primary" :href="routeHelper('product/create')">
                    <i class="fas fa-plus text-xs"></i>
                    Add Product
                </x-ui.button>
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ routeHelper('dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Disabled Products</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200 px-4 py-4">
                <h2 class="text-base font-semibold text-slate-900">Disable Product List</h2>
                <span class="inline-flex items-center rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-inset ring-primary/20">
                    {{ $products->count() }} {{ Str::plural('product', $products->count()) }}
                </span>
            </div>

            <div class="overflow-x-auto p-5">
                <table id="example1" class="w-full min-w-[960px] text-left text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/80 text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                            <th class="w-14 px-4 py-3.5">SL</th>
                            <th class="px-4 py-3.5">Product</th>
                            <th class="px-4 py-3.5 text-right">Regular Price</th>
                            <th class="px-4 py-3.5 text-right">Discount Price</th>
                            <th class="px-4 py-3.5">Stock</th>
                            <th class="px-4 py-3.5">Brand</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-4 py-3.5 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($products as $key => $data)
                            <tr class="group transition-colors hover:bg-slate-50/80">
                                <td class="px-4 py-4 text-slate-500">{{ $loop->iteration }}</td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ asset('uploads/product/' . $data->image) }}" alt="Product Image"
                                            class="h-10 w-10 rounded-lg object-cover ring-1 ring-slate-200">
                                        <span class="font-semibold text-slate-800">{{ $data->title }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-right tabular-nums font-semibold text-slate-800">
                                    {{ $data->regular_price !== null ? number_format($data->regular_price, 2) : '' }}
                                </td>
                                <td class="px-4 py-4 text-right tabular-nums text-slate-500">
                                    {{ $data->discount_price !== null ? number_format($data->discount_price, 2) : '' }}
                                </td>
                                <td class="px-4 py-4">
                                    @if ($data->quantity > 0)
                                        <x-ui.badge variant="success" dot>Available</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="danger" dot>Unavailable</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-slate-500">{{ $data->brand->name ?? '' }}</td>
                                <td class="px-4 py-4">
                                    @if ($data->status)
                                        <x-ui.badge variant="success" dot>Active</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="danger" dot>Disable</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.product.order', $data->id) }}"
                                            title="Create order for this product"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-cart-plus"></i>
                                        </a>
                                        @if ($data->status)
                                            <a href="{{ routeHelper('product/status/' . $data->id) }}"
                                                title="Disable product"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        @else
                                            <a href="{{ routeHelper('product/status/' . $data->id) }}"
                                                title="Activate product"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        @endif
                                        <a href="{{ routeHelper('product/' . $data->id) }}"
                                            title="View product"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ routeHelper('product/' . $data->id . '/edit') }}"
                                            title="Edit product"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-primary hover:bg-primary-50 hover:text-primary hover:shadow">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if (auth()->user()->desig != 3)
                                            <a href="javascript:void(0)" data-id="{{ $data->id }}" id="deleteData"
                                                title="Delete product"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 shadow-sm transition-all hover:-translate-y-px hover:border-danger hover:bg-danger/10 hover:text-danger hover:shadow">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        @endif
                                        <form id="delete-data-form-{{ $data->id }}"
                                            action="{{ routeHelper('product/' . $data->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-20 text-center">
                                    <div class="mx-auto mb-4 grid h-14 w-14 place-items-center rounded-2xl bg-slate-50 ring-1 ring-slate-200">
                                        <i class="fas fa-box-open text-xl text-slate-300"></i>
                                    </div>
                                    <p class="font-semibold text-slate-700">No disabled products found</p>
                                    <p class="mt-1 text-sm text-slate-500">Products will appear here once they are disabled.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </section>

@endsection

@push('js')
    <!-- DataTables  & Plugins -->
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/assets/plugins/jszip/jszip.min.js"></script>
    <script src="/assets/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="/assets/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script>
        $(function() {
            @if ($products->count())
                $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            @endif
        })
    </script>
@endpush
