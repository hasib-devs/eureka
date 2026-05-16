@extends('layouts.admin.app')
@section('title', 'IP Block List')
@section('content')
    <section class="content-header">
        <div class="">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>IP Block Management</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">IP Block</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Block New IP</h3>
                        </div>
                        <form action="{{ route('admin.ip_block.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="ip_address">IP Address</label>
                                    <input type="text" class="form-control" name="ip_address"
                                        placeholder="Enter IP Address" required>
                                    @error('ip_address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="reason">Reason (Optional)</label>
                                    <textarea class="form-control" name="reason" placeholder="Reason for blocking"></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Block IP</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Blocked IP List</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>IP Address</th>
                                        <th>Reason</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ipBlocks as $block)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $block->ip_address }}</td>
                                            <td>{{ $block->reason }}</td>
                                            <td>{{ $block->created_at->format('d M Y, h:i A') }}</td>
                                            <td>
                                                <form action="{{ route('admin.ip_block.destroy', $block->id) }}"
                                                    method="POST" onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Unblock</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-3">
                                {{ $ipBlocks->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
