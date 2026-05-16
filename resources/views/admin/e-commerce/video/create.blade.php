@extends('layouts.admin.e-commerce.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Add Video</h3>
        <a href="{{ route('admin.video.index') }}" class="btn btn-secondary btn-sm float-right">Back</a>
    </div>

    <form action="{{ route('admin.video.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="card-body">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label>Button Text</label>
                <input type="text" name="button_text" class="form-control">
            </div>

            <div class="form-group">
                <label>Button URL</label>
                <input type="text" name="button_url" class="form-control">
            </div>

            <div class="form-group">
                <label>Video File</label>
                <input type="file" name="video" class="form-control" accept="video/mp4,video/webm,video/quicktime">
            </div>

            <div class="form-group">
                <label>Thumbnail Image</label>
                <input type="file" name="thumbnail" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="status" checked> Active
                </label>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Video</button>
        </div>
    </form>
</div>
@endsection
