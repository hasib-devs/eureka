@extends('layouts.admin.e-commerce.app')
@section('title', 'Settings')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6 offse">
                <h1>Setting - <small>Credintial</small></h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{routeHelper('dashboard')}}">Home</a></li>
                    <li class="breadcrumb-item active">SMS | Mail | Login | Register Configureation</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>


{{-- COURIER Config --}}
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">COURIER Configuration</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-8 offset-md-2">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">STEEDFAST Courier configuration</h3>
                        </div>
                        <form id="sms_config" action="{{routeHelper('setting')}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group col-md-12">
                                    <input type="hidden" name="type" value="12">
                                    <ul>
                                        <li>
                                            <small style="color:red;">Get api from: <a href="https://www.steadfast.com.bd/" target="_blank" rel="noopener noreferrer">www.steadfast.com.bd</a></small>
                                        </li>
                                        <li>
                                            <label for="STEEDFAST_STATUS" class="text-capitalize">API V1 STATUS</label>
                                            <select name="STEEDFAST_STATUS" id="STEEDFAST_STATUS">
                                                @if (setting('STEEDFAST_STATUS') ?? 0 == 1)    
                                                    <option value="1">ON</option>
                                                    <option value="0">OFF</option>
                                                @else
                                                <option value="0">OFF</option>
                                                <option value="1">ON</option>
                                                @endif
                                            </select>
                                        </li>
                                        <li>
                                            <label for="STEEDFAST_API_KEY" class="text-capitalize">API KEY</label>
                                            <input type="text" name="STEEDFAST_API_KEY" id="STEEDFAST_API_KEY" value="{{ setting('STEEDFAST_API_KEY') ?? "" }}">
                                        </li>
                                        <li>
                                            <label for="STEEDFAST_API_SECRET_KEY" class="text-capitalize">API SECRET</label>
                                            <input type="text" name="STEEDFAST_API_SECRET_KEY" id="STEEDFAST_API_SECRET_KEY" value="{{ setting('STEEDFAST_API_SECRET_KEY') ?? "" }}">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-8 offset-md-2 mt-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">BD Courier Configuration (Fraud Checker)</h3>
                        </div>
                        <form id="bdcourier_config" action="{{routeHelper('setting')}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="form-group col-md-12">
                                    <input type="hidden" name="type" value="12">
                                    <ul>
                                        <li>
                                            <small style="color:red;">Get api from: <a href="https://bdcourier.com/" target="_blank" rel="noopener noreferrer">bdcourier.com</a></small>
                                        </li>
                                        <li>
                                            <label for="BDCOURIER_STATUS" class="text-capitalize">STATUS</label>
                                            <select name="BDCOURIER_STATUS" id="BDCOURIER_STATUS">
                                                @if (setting('BDCOURIER_STATUS') ?? 0 == 1)    
                                                    <option value="1">ON</option>
                                                    <option value="0">OFF</option>
                                                @else
                                                <option value="0">OFF</option>
                                                <option value="1">ON</option>
                                                @endif
                                            </select>
                                        </li>
                                        <li>
                                            <label for="BDCOURIER_API_KEY" class="text-capitalize">API KEY (Bearer Token)</label>
                                            <input type="text" name="BDCOURIER_API_KEY" id="BDCOURIER_API_KEY" value="{{ setting('BDCOURIER_API_KEY') ?? 'ZkEEfBAEBRxVkgcLpR3Z5e3sPHQ6dy0XViGTqYyg4clRjj06rRKmAs41Smp2' }}" style="width: 100%;">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-arrow-circle-up"></i>
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('css')
    <style>
        form ul li input[type="text"],
        form ul li input[type="email"],
        form ul li input[type="password"]{
            border: 1px solid #ccc;
            margin-top: .5rem;
            margin-right: .5rem;
            padding: .4rem;
        }

        form select{
            margin: .5rem;
        }
    </style>
@endpush