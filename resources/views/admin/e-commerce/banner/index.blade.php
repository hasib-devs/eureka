@extends('layouts.admin.e-commerce.app')

@section('title', 'Banner List')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Banner List</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Banner List</h3>
            <div class="text-right">
                <a href="{{routeHelper('banner/create')}}" class="btn btn-success">Add Banner</a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Image</th>
                        <th>URL</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($banners as $key => $banner)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td><img src="{{ asset('uploads/banner/'.$banner->image) }}" width="200"></td>
                            <td>{{ $banner->url }}</td>
                            <td>@if($banner->status) Active @else Disabled @endif</td>
                            <td>
                                <a href="{{ routeHelper('banner/'.$banner->id.'/edit') }}" class="btn btn-info">Edit</a>
                                <a href="{{ routeHelper('banner/'.$banner->id) }}" class="btn btn-warning">@if($banner->status) Disable @else Enable @endif</a>
                                <form action="{{ routeHelper('banner/'.$banner->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
