@extends('layouts.admin.app')

@section('title')
    @isset($banner)
        Edit Banner
    @else
        Add Banner
    @endisset
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
@endpush

@section('content')
    <section class="content-header">
        <div class="">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        @isset($banner)
                            Edit Banner
                        @else
                            Add Banner
                        @endisset
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            @isset($banner)
                                Edit Banner
                            @else
                                Add Banner
                            @endisset
                        </h3>
                    </div>
                    <form action="{{ isset($banner) ? routeHelper('banner/' . $banner->id) : routeHelper('banner') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($banner)
                            @method('PUT')
                        @endisset
                        <div class="card-body">
                            <div class="form-group">
                                <label for="image">Banner Image:</label>
                                <input type="file" name="image" id="image"
                                    class="form-control @error('image') is-invalid @enderror"
                                    @isset($banner) data-default-file="{{ asset('uploads/banner/' . $banner->image) }}" @endisset>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="url">Banner URL:</label>
                                <input type="text" name="url" id="url"
                                    class="form-control @error('url') is-invalid @enderror"
                                    value="{{ $banner->url ?? old('url') }}">
                                @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="status" id="status"
                                        @isset($banner) {{ $banner->status ? 'checked' : '' }} @else checked @endisset>
                                    <label class="custom-control-label" for="status">Status</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="is_feature" id="is_feature"
                                        @isset($banner) {{ $banner->is_feature ? 'checked' : '' }} @else checked @endisset>
                                    <label class="custom-control-label" for="is_feature">Feature</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                @isset($banner)
                                    Update
                                @else
                                    Submit
                                @endisset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#image').dropify();
        });
    </script>
@endpush
