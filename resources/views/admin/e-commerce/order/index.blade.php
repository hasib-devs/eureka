@extends('layouts.admin.e-commerce.app')

@section('title', 'All Order List')

@push('css')
  <!-- <link rel="stylesheet" href="/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css"> -->
@endpush

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>All Order List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ routeHelper('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">All Order List</li>
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
                    <h3 class="card-title">All Order List</h3>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.order.index') }}" method="GET" class="row mb-3">
                <div class="form-group col-md-4">
                    <label>Search by Invoice or Phone</label>
                    <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="Invoice or Phone">
                </div>

                <div class="form-group col-md-3">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option value="" selected>All</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Processing</option>
                        <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Canceled</option>
                        <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Delivered</option>
                        <option value="4" {{ request('status') === '4' ? 'selected' : '' }}>Shipping</option>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <label>Pre Order</label>
                    <select class="form-control" name="is_pre">
                        <option value="" selected>All</option>
                        <option value="0" {{ request('is_pre') === '0' ? 'selected' : '' }}>Pre Order</option>
                        <option value="1" {{ request('is_pre') === '1' ? 'selected' : '' }}>Not Pre Order</option>
                    </select>
                </div>

                <div class="form-group col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="{{ route('admin.order.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>

            <h3 class="text-right">Total {{ $orders->total() }} results</h3>

            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Invoice</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Payment</th>
                        <th>Subtotal</th>
                        <th>Discount</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key => $data)
                        <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$data->invoice}}</td>
                            <td>{{$data->first_name}}</td>
                            <td>
                                {{$data->phone}}
                                   
                                    <button class="fraud_checker btn btn-xs rounded-pill btn-primary"
    data-id="{{ $data->id }}"
    data-toggle="modal"
    data-target="#fraud_checker_modal">
    Check Fraud
</button>

                            </td>
                            <td>{{$data->payment_method}}</td>
                            <td>{{$data->subtotal}}</td>
                            <td>{{$data->discount}}</td>
                            <td>{{$data->total}}</td>
                            <td>{{date('d M Y', strtotime($data->created_at))}}</td>
                            <td>
                                @if ($data->status == 0)
                                    <span class="badge badge-warning">Pending</span>
                                @elseif ($data->status == 1)
                                    <span class="badge badge-primary">order confirm</span>
                                @elseif ($data->status == 2)
                                    <span class="badge badge-danger">Canceled</span>
                                @elseif ($data->status == 5)
                                    <span class="badge badge-danger">refund</span>
                                @elseif ($data->status == 4)
                                    <span class="badge" style="background: #7db1b1;">Shipping</span>
                                @elseif ($data->status == 6)
                                    <span class="badge badge-warning"><small>Return<br>Requested</small></span>
                                @elseif ($data->status == 7)
                                    <span class="badge badge-warning"><small>Returning by Customer</small></span>
                                @elseif ($data->status == 8)
                                    <span class="badge badge-danger">Returned</span>
                                @elseif ($data->status == 9)
                                    <span class="badge badge-danger"><small>Sended to Courier</small></span>
                                @elseif ($data->status == 3)
                                    <span class="badge badge-success">Delivered</span>
                                @endif  
                            </td>
                            <td>
                                <div class="btn btn-group">
                                    <a title="Invoice" href="{{route('admin.order.invoice', $data->id)}}" class="btn btn-warning btn-sm" target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <a title="Show Information" href="{{routeHelper('order/'. $data->id)}}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <!-- @if ($data->status == 0)
                                    <a title="Done" href="{{routeHelper('order/status/processing/'. $data->id)}}" id="btnStatus" onclick="return confirm('Are you sure change this order status?')" class="btn btn-primary btn-sm">
                                        <i class="fas fa-check"></i>
                                    </a>
                                   
                                    <a title="Shipping" href="{{routeHelper('order/status/shipping/'. $data->id)}}" id="btnShipping" onclick="return confirm('Are you sure Shipping this order?')" class="btn btn-success btn-sm">
                                        <i class="fas fa-plane"></i>
                                    </a>
                                    <a title="Delivered" href="{{routeHelper('order/status/delivered/'. $data->id)}}" id="btnDelivered" onclick="return confirm('Are you sure delivered this order?')" class="btn btn-success btn-sm">
                                        <i class="fas fa-thumbs-up"></i>
                                    </a>
                                    @elseif ($data->status == 1)
                                        
                                        <a title="Shipping" href="{{routeHelper('order/status/shipping/'. $data->id)}}" id="btnShipping" onclick="return confirm('Are you sure Shipping this order?')" class="btn btn-success btn-sm">
                                        <i class="fas fa-plane"></i>
                                        </a>
                                        <a title="Delivered" href="{{routeHelper('order/status/delivered/'. $data->id)}}" id="btnDelivered" onclick="return confirm('Are you sure delivered this order?')" class="btn btn-success btn-sm">
                                            <i class="fas fa-thumbs-up"></i>
                                        </a>
                                    @elseif ($data->status == 4)
                                       
                                        <a title="Delivered" href="{{routeHelper('order/status/delivered/'. $data->id)}}" id="btnDelivered" onclick="return confirm('Are you sure delivered this order?')" class="btn btn-success btn-sm">
                                            <i class="fas fa-thumbs-up"></i>
                                        </a>
                                    
                                    @endif
                                    @if ($data->status != 2 && $data->status != 3)
                                     <a title="Cancel" href="{{routeHelper('order/status/cancel/'. $data->id)}}" id="btnCancel" onclick="return confirm('Are you sure cancel this order?')" class="btn btn-danger btn-sm">
                                            <i class="fas fa-window-close"></i>
                                        </a>
                                        @endif -->
                                </div>
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
  <!-- quckview modal modal -->
    <form>
       <div class="modal fade" id="fraud_checker_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="fraud_checker_details">
            <!-- ajax content -->
        </div>
    </div>
</div>

        <!-- task all modal end -->
    </form>
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
        $(function () { 
            $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            

        })
    </script> -->
    
    
    <script>
$(document).on("click", ".fraud_checker", function () {
    let id = $(this).data('id');

    $("#fraud_checker_details").html(
        '<div class="text-center p-4">Loading...</div>'
    );

    $.ajax({
        type: "POST",
        url: "{{ route('admin.order.fraud_checker') }}",
        data: {
            _token: "{{ csrf_token() }}",
            id: id
        },
        success: function (response) {
            $("#fraud_checker_details").html(response);
        },
        error: function () {
            $("#fraud_checker_details").html(
                '<div class="text-danger p-3">Something went wrong</div>'
            );
        }
    });
});
</script>

@endpush