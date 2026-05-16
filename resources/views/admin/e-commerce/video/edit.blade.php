@extends('layouts.admin.e-commerce.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Edit Video</h3>
        <a href="{{ route('admin.video.index') }}" class="btn btn-secondary btn-sm float-right">Back</a>
    </div>

    <form action="{{ route('admin.video.update', $video->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" value="{{ $video->title }}" class="form-control">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="3">{{ $video->description }}</textarea>
            </div>

            <div class="form-group">
                <label>Button Text</label>
                <input type="text" name="button_text" value="{{ $video->button_text }}" class="form-control">
            </div>

            <div class="form-group">
                <label>Button URL</label>
                <input type="text" name="button_url" value="{{ $video->button_url }}" class="form-control">
            </div>

            <div class="form-group">
                <label>Change Video</label>
                <input type="file" name="video" class="form-control" accept="video/mp4,video/webm,video/quicktime">

                @if($video->video)
                    <video width="220" controls class="mt-2">
                        <source src="{{ asset('storage/'.$video->video) }}">
                    </video>
                @endif
            </div>

            <div class="form-group">
                <label>Change Thumbnail</label>
                <input type="file" name="thumbnail" class="form-control" accept="image/*">

                @if($video->thumbnail)
                    <img src="{{ asset('storage/'.$video->thumbnail) }}" width="180" class="mt-2">
                @endif
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="status" {{ $video->status ? 'checked' : '' }}> Active
                </label>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Update Video</button>
        </div>
    </form>
</div>
@endsection
