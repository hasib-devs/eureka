@extends('layouts.admin.e-commerce.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Videos</h3>
        <a href="{{ route('admin.video.create') }}" class="btn btn-primary btn-sm float-right">Add Video</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th>Title</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            @foreach($videos as $video)
                <tr>
                    <td>{{ $video->title }}</td>
                    <td>{{ $video->status ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <a href="{{ route('admin.video.edit', $video->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.video.destroy', $video->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
</form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection