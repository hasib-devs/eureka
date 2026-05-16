@extends('layouts.admin.app')

@section('title', 'Slider One List')

@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Slider One List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ routeHelper('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Slider One List</li>
                    </ol>
                </div>
            </div>
        </div><!-- /. -->
    </section>

    <!-- Main content -->
    <section class="content">

        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 class="card-title">Slider One List</h3>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ routeHelper('sliderone/create') }}" class="btn btn-success">
                            <i class="fas fa-plus-circle"></i>
                            Add Slider One
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Image</th>
                            <th>Url</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($new_sliders_one as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <img src="/uploads/sliderOne/{{ $data->image }}" alt="Slider" width="200px">
                                </td>
                                <td>{{ $data->url }}</td>
                                <td>
                                    @if ($data->status)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Disable</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($data->status)
                                        <a title="Disable" href="{{ routeHelper('sliderone/' . $data->nsid) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="fas fa-thumbs-up"></i>
                                        </a>
                                    @else
                                        <a title="Active" href="{{ routeHelper('sliderone/' . $data->nsid) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="fas fa-thumbs-down"></i>
                                        </a>
                                    @endif
                                    <a href="{{ routeHelper('sliderone/' . $data->nsid . '/edit') }}"
                                        class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="javascript:void(0)" data-id="{{ $data->nsid }}" id="deleteData"
                                        class="btn btn-danger btn-sm"">
                                        <i class="nav-icon fas fa-trash-alt"></i>
                                    </a>
                                    <form id="delete-data-form-{{ $data->nsid }}"
                                        action="{{ routeHelper('sliderone/' . $data->nsid) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->

@endsection

@push('js')
    <!-- DataTables  & Plugins -->
    <script src="/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script>
        $(function() {
            $("#example1").DataTable();
        })
    </script>
@endpush
