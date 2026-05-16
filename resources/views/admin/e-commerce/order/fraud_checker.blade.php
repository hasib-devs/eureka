<div class="modal-header bg-primary text-white">
    <h5 class="modal-title">
        <i class="fas fa-shield-alt"></i> Fraud Checker Report
    </h5>
    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<div class="modal-body">
    <!-- Customer Info -->
    <div class="card mb-3">
        <div class="card-header bg-light">
            <h6 class="mb-0"><i class="fas fa-user"></i> Customer Information</h6>
        </div>
        <div class="card-body">
            @if(isset($api_error_message) && $api_error_message)
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> 
                    <strong>API Error:</strong> {{ $api_error_message }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> {{ $name }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Phone:</strong> {{ $phone }}</p>
                    <p><strong>IP:</strong> {{ $ip ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p>
                        <strong>Status:</strong> 
                        @if($status == 'success')
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle"></i> Verified Customer
                            </span>
                        @elseif($status == 'warning')
                            <span class="badge badge-warning">
                                <i class="fas fa-exclamation-triangle"></i> Caution Required
                            </span>
                        @elseif($status == 'danger')
                            <span class="badge badge-danger">
                                <i class="fas fa-times-circle"></i> High Risk Customer
                            </span>
                        @else
                            <span class="badge badge-secondary">
                                <i class="fas fa-info-circle"></i> {{ $status }}
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Overall Summary -->
    <div class="card mb-3">
        <div class="card-header bg-info text-white">
            <h6 class="mb-0"><i class="fas fa-chart-pie"></i> Overall Summary</h6>
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text">Total Parcels</span>
                            <span class="info-box-number">{{ $total_parcel }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-success">
                        <div class="info-box-content">
                            <span class="info-box-text text-white">Success</span>
                            <span class="info-box-number text-white">{{ $total_success }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-danger">
                        <div class="info-box-content">
                            <span class="info-box-text text-white">Cancelled</span>
                            <span class="info-box-number text-white">{{ $total_cancel }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Success Ratio Progress Bar -->
            @php
                $success_ratio = $total_parcel > 0 ? round(($total_success / $total_parcel) * 100, 2) : 0;
                $cancel_ratio = $total_parcel > 0 ? round(($total_cancel / $total_parcel) * 100, 2) : 0;
            @endphp
            
            <div class="mt-3">
                <h6>Success Ratio: {{ $success_ratio }}%</h6>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $success_ratio }}%;" 
                         aria-valuenow="{{ $success_ratio }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $success_ratio }}% Success
                    </div>
                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ $cancel_ratio }}%;" 
                         aria-valuenow="{{ $cancel_ratio }}" aria-valuemin="0" aria-valuemax="100">
                        {{ $cancel_ratio }}% Cancel
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courier Wise Details -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h6 class="mb-0"><i class="fas fa-shipping-fast"></i> Courier-wise Performance</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Courier</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Success</th>
                            <th class="text-center">Cancelled</th>
                            <th class="text-center">Success Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Local Shop -->
                        <tr>
                            <td>
                                <i class="fas fa-store text-primary"></i> 
                                <strong>Our Shop (Local)</strong>
                            </td>
                            <td class="text-center">{{ $local_shop_total ?? 0 }}</td>
                            <td class="text-center text-success">
                                <strong>{{ $local_shop_success ?? 0 }}</strong>
                            </td>
                            <td class="text-center text-danger">
                                <strong>{{ $local_shop_cancel ?? 0 }}</strong>
                            </td>
                            <td class="text-center">
                                @php
                                    $local_total = $local_shop_total ?? 0;
                                    $local_success = $local_shop_success ?? 0;
                                    $local_rate = $local_total > 0 ? round(($local_success / $local_total) * 100, 1) : 0;
                                @endphp
                                <span class="badge badge-{{ $local_rate >= 70 ? 'success' : ($local_rate >= 50 ? 'warning' : 'danger') }}">
                                    {{ $local_rate }}%
                                </span>
                            </td>
                        </tr>

                        <!-- Steadfast -->
                        <tr>
                            <td>
                                <i class="fas fa-truck text-primary"></i> 
                                <strong>Steadfast</strong>
                            </td>
                            <td class="text-center">{{ $steadfast_total }}</td>
                            <td class="text-center text-success">
                                <strong>{{ $steadfast_success }}</strong>
                            </td>
                            <td class="text-center text-danger">
                                <strong>{{ $steadfast_cancel }}</strong>
                            </td>
                            <td class="text-center">
                                @php
                                    $steadfast_rate = $steadfast_total > 0 ? round(($steadfast_success / $steadfast_total) * 100, 1) : 0;
                                @endphp
                                <span class="badge badge-{{ $steadfast_rate >= 70 ? 'success' : ($steadfast_rate >= 50 ? 'warning' : 'danger') }}">
                                    {{ $steadfast_rate }}%
                                </span>
                            </td>
                        </tr>

                        <!-- Pathao -->
                        <tr>
                            <td>
                                <i class="fas fa-truck text-info"></i> 
                                <strong>Pathao</strong>
                            </td>
                            <td class="text-center">{{ $pathao_total }}</td>
                            <td class="text-center text-success">
                                <strong>{{ $pathao_success }}</strong>
                            </td>
                            <td class="text-center text-danger">
                                <strong>{{ $pathao_cancel }}</strong>
                            </td>
                            <td class="text-center">
                                @php
                                    $pathao_rate = $pathao_total > 0 ? round(($pathao_success / $pathao_total) * 100, 1) : 0;
                                @endphp
                                <span class="badge badge-{{ $pathao_rate >= 70 ? 'success' : ($pathao_rate >= 50 ? 'warning' : 'danger') }}">
                                    {{ $pathao_rate }}%
                                </span>
                            </td>
                        </tr>

                        <!-- RedX -->
                        <tr>
                            <td>
                                <i class="fas fa-truck text-danger"></i> 
                                <strong>RedX</strong>
                            </td>
                            <td class="text-center">{{ $redx_total }}</td>
                            <td class="text-center text-success">
                                <strong>{{ $redx_success }}</strong>
                            </td>
                            <td class="text-center text-danger">
                                <strong>{{ $redx_cancel }}</strong>
                            </td>
                            <td class="text-center">
                                @php
                                    $redx_rate = $redx_total > 0 ? round(($redx_success / $redx_total) * 100, 1) : 0;
                                @endphp
                                <span class="badge badge-{{ $redx_rate >= 70 ? 'success' : ($redx_rate >= 50 ? 'warning' : 'danger') }}">
                                    {{ $redx_rate }}%
                                </span>
                            </td>
                        </tr>

                        <!-- Paperfly -->
                        <tr>
                            <td>
                                <i class="fas fa-truck text-success"></i> 
                                <strong>Paperfly</strong>
                            </td>
                            <td class="text-center">{{ $paperfly_total }}</td>
                            <td class="text-center text-success">
                                <strong>{{ $paperfly_success }}</strong>
                            </td>
                            <td class="text-center text-danger">
                                <strong>{{ $paperfly_cancel }}</strong>
                            </td>
                            <td class="text-center">
                                @php
                                    $paperfly_rate = $paperfly_total > 0 ? round(($paperfly_success / $paperfly_total) * 100, 1) : 0;
                                @endphp
                                <span class="badge badge-{{ $paperfly_rate >= 70 ? 'success' : ($paperfly_rate >= 50 ? 'warning' : 'danger') }}">
                                    {{ $paperfly_rate }}%
                                </span>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-light">
                        <tr>
                            <th>Total</th>
                            <th class="text-center">{{ $total_parcel }}</th>
                            <th class="text-center text-success">{{ $total_success }}</th>
                            <th class="text-center text-danger">{{ $total_cancel }}</th>
                            <th class="text-center">
                                <span class="badge badge-{{ $success_ratio >= 70 ? 'success' : ($success_ratio >= 50 ? 'warning' : 'danger') }}">
                                    {{ $success_ratio }}%
                                </span>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Risk Assessment -->
    <div class="card mt-3">
        <div class="card-header 
            @if($success_ratio >= 70) bg-success 
            @elseif($success_ratio >= 50) bg-warning 
            @else bg-danger 
            @endif text-white">
            <h6 class="mb-0"><i class="fas fa-exclamation-triangle"></i> Risk Assessment</h6>
        </div>
        <div class="card-body">
            @if($total_parcel == 0)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    <strong>New Customer:</strong> No previous delivery history found.
                </div>
            @elseif($success_ratio >= 70)
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> 
                    <strong>Low Risk:</strong> This customer has a good delivery success rate ({{ $success_ratio }}%). Safe to proceed with the order.
                </div>
            @elseif($success_ratio >= 50)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Medium Risk:</strong> This customer has a moderate success rate ({{ $success_ratio }}%). Consider confirming the order before shipping.
                </div>
            @else
                <div class="alert alert-danger">
                    <i class="fas fa-times-circle"></i> 
                    <strong>High Risk:</strong> This customer has a low success rate ({{ $success_ratio }}%). Strongly recommend order confirmation before shipping.
                </div>
            @endif

            @if($total_cancel >= 3)
                <div class="alert alert-warning mt-2">
                    <i class="fas fa-ban"></i> 
                    <strong>Warning:</strong> Customer has {{ $total_cancel }} cancelled orders. Extra verification recommended.
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">
        <i class="fas fa-times"></i> Close
    </button>
    <button type="button" class="btn btn-primary" onclick="window.print()">
        <i class="fas fa-print"></i> Print Report
    </button>
</div>

<style>
    .info-box {
        min-height: 80px;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
    .info-box-text {
        display: block;
        font-size: 14px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .info-box-number {
        display: block;
        font-weight: bold;
        font-size: 24px;
    }
    @media print {
        .modal-footer {
            display: none;
        }
    }
</style>