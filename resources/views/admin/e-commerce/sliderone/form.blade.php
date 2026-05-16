@extends('layouts.admin.e-commerce.app1')

@section('title')
    @isset($new_sliders_one)
        Edit Slider One
    @else
        Add Slider One
    @endisset
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css"
        integrity="sha512-EZSUkJWTjzDlspOoPSpUFR0o0Xy7jdzW//6qhUkoZ9c4StFkVsp9fbbd0O06p9ELS3H486m4wmrCELjza4JEog=="
        crossorigin="anonymous" />
    <style>
        .dropify-wrapper .dropify-message p {
            font-size: initial;
        }
    </style>
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        @isset($new_sliders_one)
                            Edit Slider One
                        @else
                            Add Slider One
                        @endisset
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ routeHelper('dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">
                            @isset($new_sliders_one)
                                Edit Slider One
                            @else
                                Add Slider One
                            @endisset
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /. -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">

                        <div class="row">
                            <div class="col-sm-6">
                                <h3 class="card-title">
                                    @isset($new_sliders_one)
                                        Edit Slider One
                                    @else
                                        Add New Slider One
                                    @endisset
                                </h3>
                            </div>
                            <div class="col-sm-6 text-right">
                                <a href="{{ routeHelper('sliderone') }}" class="btn btn-danger">

                                    <i class="fas fa-long-arrow-alt-left"></i>
                                    Back to List One
                                </a>
                            </div>
                        </div>
                    </div>
                    <form
                        action="{{ isset($new_sliders_one) ? routeHelper('sliderone/' . $new_sliders_one->nsid) : routeHelper('sliderone') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($new_sliders_one)
                            @method('PUT')
                        @endisset
                        <div class="card-body">
                            <div class="form-group">
                                <label for="image">Slider Image:</label>
                                <input type="file" name="image" id="image"
                                    class="form-control @error('image') is-invalid @enderror"
                                    @isset($new_sliders_one) data-default-file="{{ '/uploads/sliderOne/' . $slider->image }}" @endisset>
                                @error('image')
                                    <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="name">Slider Url:</label>
                                <input type="text" name="url" id="url" placeholder="Write slider url"
                                    class="form-control @error('url') is-invalid @enderror"
                                    value="{{ $new_sliders_one->url ?? old('url') }}" required autocomplete="off">
                                @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="status" id="status"
                                        @isset($new_sliders_one) {{ $new_sliders_one->status ? 'checked' : '' }} @else checked @endisset>
                                    <label class="custom-control-label" for="status">Status</label>
                                </div>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="is_feature" id="is_feature"
                                        @isset($new_sliders_one) {{ $new_sliders_one->is_feature ? 'checked' : '' }} @else checked @endisset>
                                    <label class="custom-control-label" for="is_feature">is_features</label>
                                </div>
                                @error('is_feature')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="is_pop" id="is_pop"
                                        @isset($new_sliders_one) {{ $new_sliders_one->is_pop ? 'checked' : '' }} @else checked @endisset>
                                    <label class="custom-control-label" for="is_pop">is_popup</label>
                                </div>
                                @error('is_pop')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" name="is_sub" id="is_sub"
                                        @isset($new_sliders_one) {{ $new_sliders_one->is_sub ? 'checked' : '' }} @else checked @endisset>
                                    <label class="custom-control-label" for="is_sub">Sub Slider</label>
                                </div>
                                @error('is_sub')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="form-group">
                                <button class="mt-1 btn btn-primary">
                                    @isset($new_sliders_one)
                                        <i class="fas fa-arrow-circle-up"></i>
                                        Update
                                    @else
                                        <i class="fas fa-plus-circle"></i>
                                        Submit
                                    @endisset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card -->
            </div>
        </div>


    </section>
    <!-- /.content -->
@endsection

@push('js')
    <script src="{{ asset('/assets/plugins/dropify/dropify.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#image').dropify();
        });
    </script>
@endpush
