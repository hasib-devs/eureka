@extends('layouts.admin.app')

@section('title', 'All Product List')

@push('css')
    <!-- <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
            <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
            <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css"> -->
@endpush

@section('content')

    <section class="content-header">
        <div class="">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>All Product List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ routeHelper('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">All Product List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="card-title">All Product List</h3>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ routeHelper('product/create') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i>
                            Add Product
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <form action="{{ route('admin.product.index') }}" method="GET" class="row">
                    <div class="form-group col-md-2">
                        <label>Search Title</label>
                        <input type="text" name="title" value="{{ request('title') }}" class="form-control"
                            placeholder="Enter product title">
                    </div>

                    <div class="form-group col-md-2">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="" {{ request('status') === null ? 'selected' : '' }}>All</option>
                            @foreach ($statuses as $key => $value)
                                <option value="{{ $key }}"
                                    {{ (string) request('status') === (string) $key ? 'selected' : '' }}>{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Filter Type</label>
                        <select class="form-control" name="filter">
                            <option value="" {{ request('filter') === null ? 'selected' : '' }}>All</option>
                            <option value="inhouse" {{ request('filter') == 'inhouse' ? 'selected' : '' }}>Inhouse</option>
                            <option value="low_stock" {{ request('filter') == 'low_stock' ? 'selected' : '' }}>Low Stock
                            </option>
                            <option value="reached" {{ request('filter') == 'reached' ? 'selected' : '' }}>Most Reached
                            </option>
                            <option value="unapproved" {{ request('filter') == 'unapproved' ? 'selected' : '' }}>Unapproved
                            </option>
                            <option value="approved" {{ request('filter') == 'approved' ? 'selected' : '' }}>Approved
                            </option>
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Brand</label>
                        <select class="form-control select2" name="brand">
                            <option value="" selected>All</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label>Category</label>
                        <select class="form-control select2" name="category">
                            <option value="" selected>All</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-2 align-self-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.product.index', array_merge(request()->all(), ['export' => 'csv'])) }}"
                            class="btn btn-success">
                            Download CSV
                        </a>
                    </div>
                </form>

                <h3 class="text-right">Total {{ $products->total() }} results</h3>

                <table class="tablen text-center table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width:5%;">SL</th>
                            <th style="width:10%;">Image</th>
                            <th style="width:19%;">Title</th>
                            <th style="width:9%;" title="Regular Price">Regular Price</th>
                            <th style="width:9%;" title="Discount Price">Discount Price</th>
                            <th style="width:9%;">Stock</th>
                            <th style="width:9%;">Categories</th>
                            <th style="width:10%;">Brand</th>
                            <th style="width:10%;">Status</th>
                            <th style="width:10%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $products->firstItem() + $loop->index }}</td>
                                <td>
                                    <img src="{{ asset('uploads/product/' . $product->image) }}" alt="Product Image"
                                        width="80px">
                                </td>
                                <td>{{ $product->title ?? 'N/A' }}</td>
                                <td>{{ $product->regular_price ?? 'N/A' }}</td>
                                <td>{{ $product->discount_price ?? 'N/A' }}</td>
                                <td>
                                    {{ $product->quantity }}
                                    @if ($product->quantity > 0)
                                        <span class="badge badge-success">Available</span>
                                    @else
                                        <span class="badge badge-danger">Unavailable</span>
                                    @endif
                                </td>
                                <td>
                                    @foreach ($product->categories as $category)
                                        {{ $category->name ?? '' }}

                                        @if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $product->brand->name ?? '' }}</td>
                                <td>
                                    @if ($product->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Disable</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.product.order', $product->id) }}" title="Order Product"
                                            class="btn btn-primary btn-sm">
                                            <i class="fab fa-jedi-order"></i>
                                        </a>
                                        @if ($product->status)
                                            <a title="Disable" href="{{ routeHelper('product/status/' . $product->id) }}"
                                                class="btn btn-success btn-sm">
                                                <i class="fas fa-thumbs-up"></i>
                                            </a>
                                        @else
                                            <a title="Active" href="{{ routeHelper('product/status/' . $product->id) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-thumbs-down"></i>
                                            </a>
                                        @endif
                                        <a href="{{ routeHelper('product/' . $product->id) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ routeHelper('product/' . $product->id . '/edit') }}"
                                            class="btn btn-info btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if (auth()->user()->desig != 3)
                                            <a href="javascript:void(0)" data-id="{{ $product->id }}" id="deleteData"
                                                class="btn btn-danger btn-sm">
                                                <i class="nav-icon fas fa-trash-alt"></i>
                                            </a>
                                        @endif
                                        <form id="delete-data-form-{{ $product->id }}"
                                            action="{{ routeHelper('product/' . $product->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center mt-4">
                    {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>

    </section>

@endsection

@push('js')
    <!-- <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
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
                    $("#example1").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                })
            </script> -->
@endpush
