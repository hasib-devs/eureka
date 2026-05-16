@extends('layouts.admin.app')

@section('title', 'Incomplete Leads List')

@push('css')
    <style>
        .cart-items-preview {
            max-width: 300px;
        }

        .cart-item-mini {
            font-size: 0.85rem;
            padding: 5px;
            border-bottom: 1px solid #eee;
        }

        .cart-item-mini:last-child {
            border-bottom: none;
        }

        .badge-new {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .table td {
            vertical-align: middle;
        }
    </style>
@endpush

@section('content')

    <section class="content-header">
        <div class="">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Incomplete Leads List</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ routeHelper('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Incomplete Leads</li>
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
                        <h3 class="card-title">Incomplete Lead Tracking</h3>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('admin.incomplete.leads.export') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form action="{{ route('admin.incomplete.leads.index') }}" method="GET" class="row mb-3">
                    <div class="form-group col-md-3">
                        <label>Search by Name or Phone</label>
                        <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}"
                            placeholder="Name or Phone">
                    </div>

                    <div class="form-group col-md-2">
                        <label>Status</label>
                        <select class="form-control" name="converted">
                            <option value="" selected>All</option>
                            <option value="0" {{ request('converted') === '0' ? 'selected' : '' }}>Active (Not
                                Converted)</option>
                            <option value="1" {{ request('converted') === '1' ? 'selected' : '' }}>Converted</option>
                        </select>
                    </div>

                    <div class="form-group col-md-2">
                        <label>From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>

                    <div class="form-group col-md-2">
                        <label>To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>

                    <div class="form-group col-md-3 align-self-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="{{ route('admin.incomplete.leads.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </form>

                <!-- Stats Cards -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $stats['total'] ?? 0 }}</h3>
                                <p>Total Leads</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $stats['active'] ?? 0 }}</h3>
                                <p>Active (Not Converted)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-clock"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $stats['converted'] ?? 0 }}</h3>
                                <p>Converted to Orders</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ number_format($stats['conversion_rate'] ?? 0, 1) }}%</h3>
                                <p>Conversion Rate</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-percentage"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <h3 class="text-right mb-3">Total {{ $leads->total() }} results</h3>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="bg-light">
                            <tr>
                                <th width="50">SL</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Cart Items</th>
                                <th>Subtotal</th>
                                <th>Last Activity</th>
                                <th>Status</th>
                                <th width="150">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leads as $key => $lead)
                                <tr
                                    class="{{ !$lead->converted && $lead->last_activity > now()->subHours(1) ? 'table-warning' : '' }}">
                                    <td>{{ $leads->firstItem() + $key }}</td>
                                    <td>
                                        <strong>{{ $lead->name ?? 'N/A' }}</strong>
                                        @if (!$lead->converted && $lead->last_activity > now()->subMinutes(30))
                                            <span class="badge badge-danger badge-new ml-1">Active Now</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="tel:{{ $lead->phone }}">{{ $lead->phone ?? 'N/A' }}</a>
                                        @if ($lead->phone)
                                            <a href="tel:{{ $lead->phone }}" class="btn btn-success btn-xs ml-1"
                                                title="Call Now">
                                                <i class="fas fa-phone"></i>
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $lead->email ?? 'N/A' }}</td>
                                    <td>
                                        @if ($lead->cart_data && count($lead->cart_data) > 0)
                                            <div class="cart-items-preview">
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                    data-target="#cartModal{{ $lead->id }}">
                                                    <i class="fas fa-shopping-cart"></i>
                                                    {{ $lead->total_items }} Item(s)
                                                </button>

                                                <!-- Cart Modal -->
                                                <div class="modal fade" id="cartModal{{ $lead->id }}" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Cart Items - {{ $lead->name }}
                                                                </h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Product</th>
                                                                            <th>Qty</th>
                                                                            <th>Price</th>
                                                                            <th>Total</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($lead->cart_data as $item)
                                                                            <tr>
                                                                                <td>
                                                                                    @if (!empty($item['image']))
                                                                                        <img src="{{ asset($item['image']) }}"
                                                                                            alt="" width="40"
                                                                                            class="mr-2">
                                                                                    @endif
                                                                                    <strong>{{ $item['product_name'] ?? 'N/A' }}</strong>
                                                                                </td>
                                                                                <td>{{ $item['qty'] ?? 1 }}</td>
                                                                                <td>{{ number_format($item['price'] ?? 0, 2) }}
                                                                                    TK</td>
                                                                                <td>{{ number_format($item['total'] ?? 0, 2) }}
                                                                                    TK</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <th colspan="3" class="text-right">
                                                                                Subtotal:</th>
                                                                            <th>{{ number_format($lead->subtotal, 2) }} TK
                                                                            </th>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">No items</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ number_format($lead->subtotal, 2) }} TK</strong>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $lead->last_activity ? $lead->last_activity->format('d M Y') : 'N/A' }}<br>
                                            <span
                                                class="text-muted">{{ $lead->last_activity ? $lead->last_activity->format('h:i A') : '' }}</span>
                                            @if ($lead->last_activity)
                                                <br><span
                                                    class="badge badge-secondary">{{ $lead->last_activity->diffForHumans() }}</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if ($lead->converted)
                                            <span class="badge badge-success">
                                                <i class="fas fa-check-circle"></i> Converted
                                            </span>
                                        @else
                                            <span class="badge badge-warning">
                                                <i class="fas fa-clock"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#detailModal{{ $lead->id }}" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            @if (!$lead->converted)
                                                <a href="tel:{{ $lead->phone }}" class="btn btn-success btn-sm"
                                                    title="Call Customer">
                                                    <i class="fas fa-phone"></i>
                                                </a>

                                                <form action="{{ route('admin.incomplete.leads.delete', $lead->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this lead?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <!-- Detail Modal -->
                                        <div class="modal fade" id="detailModal{{ $lead->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Lead Details - {{ $lead->name }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <h6>Customer Information</h6>
                                                                <table class="table table-sm table-borderless">
                                                                    <tr>
                                                                        <th width="120">Name:</th>
                                                                        <td>{{ $lead->name ?? 'N/A' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Phone:</th>
                                                                        <td>{{ $lead->phone ?? 'N/A' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Email:</th>
                                                                        <td>{{ $lead->email ?? 'N/A' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Address:</th>
                                                                        <td>{{ $lead->address ?? 'N/A' }}</td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <h6>Lead Information</h6>
                                                                <table class="table table-sm table-borderless">
                                                                    <tr>
                                                                        <th width="140">IP Address:</th>
                                                                        <td>{{ $lead->ip_address ?? 'N/A' }}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Page URL:</th>
                                                                        <td><small>{{ $lead->page_url ?? 'N/A' }}</small>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Session ID:</th>
                                                                        <td><small>{{ $lead->session_id }}</small></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Created At:</th>
                                                                        <td>{{ $lead->created_at->format('d M Y h:i A') }}
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Last Activity:</th>
                                                                        <td>{{ $lead->last_activity ? $lead->last_activity->format('d M Y h:i A') : 'N/A' }}
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        @if ($lead->cart_data && count($lead->cart_data) > 0)
                                                            <hr>
                                                            <h6>Cart Items</h6>
                                                            <table class="table table-sm table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Product</th>
                                                                        <th width="80">Qty</th>
                                                                        <th width="100">Price</th>
                                                                        <th width="100">Total</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($lead->cart_data as $item)
                                                                        <tr>
                                                                            <td>{{ $item['product_name'] ?? 'N/A' }}</td>
                                                                            <td>{{ $item['qty'] ?? 1 }}</td>
                                                                            <td>{{ number_format($item['price'] ?? 0, 2) }}
                                                                                TK</td>
                                                                            <td>{{ number_format($item['total'] ?? 0, 2) }}
                                                                                TK</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="3" class="text-right">Subtotal:
                                                                        </th>
                                                                        <th>{{ number_format($lead->subtotal, 2) }} TK</th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        @if (!$lead->converted && $lead->phone)
                                                            <a href="tel:{{ $lead->phone }}" class="btn btn-success">
                                                                <i class="fas fa-phone"></i> Call Now
                                                            </a>
                                                        @endif
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <p class="text-muted py-4">
                                            <i class="fas fa-inbox fa-3x mb-3"></i><br>
                                            No incomplete leads found
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $leads->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </section>

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Auto refresh every 2 minutes for active leads
            @if (request('converted') === '0' || !request('converted'))
                setInterval(function() {
                    location.reload();
                }, 120000); // 2 minutes
            @endif

            // Highlight new leads
            $('.badge-new').each(function() {
                $(this).closest('tr').addClass('table-warning');
            });
        });
    </script>
@endpush
