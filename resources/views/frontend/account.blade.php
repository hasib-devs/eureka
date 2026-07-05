@extends('layouts.frontend.app')


@section('title', 'Account')

@section('content')

<style>
    .pi-uploader { display: flex; align-items: center; gap: 18px; }
    .pi-input { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0 0 0 0); border: 0; }
    .pi-drop {
        position: relative; width: 108px; height: 108px; border-radius: 18px; cursor: pointer;
        overflow: hidden; flex: 0 0 auto; margin: 0;
        background: linear-gradient(135deg, #faf6ea, #fff);
        border: 2px dashed #e2d6ad; display: grid; place-items: center;
        transition: border-color .15s ease, box-shadow .15s ease, transform .12s ease;
    }
    .pi-drop:hover { border-color: #c9a24b; box-shadow: 0 6px 16px rgba(201,162,75,.22); transform: translateY(-1px); }
    .pi-drop img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .pi-placeholder { display: grid; place-items: center; color: #c2ab6a; }
    .pi-placeholder i { font-size: 30px; }
    .pi-overlay {
        position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center;
        gap: 4px; background: rgba(20,18,14,.55); color: #fff; font-size: 11.5px; font-weight: 600;
        opacity: 0; transition: opacity .15s ease; text-align: center; padding: 6px;
    }
    .pi-overlay i { font-size: 17px; }
    .pi-drop:hover .pi-overlay, .pi-drop.pi-empty .pi-overlay { opacity: 1; }
    .pi-meta { display: flex; flex-direction: column; gap: 3px; }
    .pi-meta .pi-title { font-weight: 700; color: #1a1a1a; font-size: 15px; }
    .pi-meta .pi-hint { font-size: 12.5px; color: #8a8577; }
</style>

<div class="customar-dashboard">
    <div class="container">
        <div class="customar-access row">
            <div class="customar-menu col-md-3">
                  @include('layouts.frontend.partials.userside')
            </div>
            <div class="col-md-9">
                <div class="card p-5 mt-5">
                    <form action="{{route('account.update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form form2">
                            <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="avatar">Profile Image</label>
                                        @php $hasAvatar = auth()->user()->avatar && file_exists(public_path('uploads/member/'.auth()->user()->avatar)); @endphp
                                        <div class="pi-uploader">
                                            <input type="file" name="avatar" id="avatar" accept="image/*"
                                                   class="pi-input @error('avatar') is-invalid @enderror">
                                            <label for="avatar" class="pi-drop {{ $hasAvatar ? '' : 'pi-empty' }}" id="pi-drop">
                                                @if ($hasAvatar)
                                                    <img id="pi-preview" src="{{ '/uploads/member/'.auth()->user()->avatar }}" alt="Profile image">
                                                @else
                                                    <img id="pi-preview" src="" alt="Profile image" style="display:none">
                                                    <span class="pi-placeholder" id="pi-placeholder"><i class="fas fa-user"></i></span>
                                                @endif
                                                <span class="pi-overlay"><i class="fas fa-camera"></i> Click to {{ $hasAvatar ? 'change' : 'upload' }}</span>
                                            </label>
                                            <div class="pi-meta">
                                                <span class="pi-title">Profile photo</span>
                                                <span class="pi-hint">Click the image to choose.<br>JPG or PNG, square works best.</span>
                                            </div>
                                        </div>
                                        @error('avatar')
                                            <div class="invalid-feedback text-danger d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                <div class="form-group col-md-6">
                                    <label>Name <sup class="text-[red]">*</sup></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" autocomplete="off" required value="{{auth()->user()->name}}"/>
                                    @error('name')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Username <sup class="text-[red]">*</sup></label>
                                    <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" autocomplete="off" required value="{{auth()->user()->username}}" readonly />
                                    @error('username')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email <sup class="text-[red]">*</sup></label>
                                    <input readonly type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" autocomplete="off" required value="{{auth()->user()->email}}"/>
                                    @error('email')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Phone <sup class="text-[red]">*</sup></label>
                                    <input  type="number" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" autocomplete="off" placeholder="Enter Your Phone Number"  value="@if(auth()->user()->phone!='null'){{auth()->user()->phone}}@endif"/>
                                    @error('phone')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                
                                @isset(auth()->user()->customer_info)
                                <div class="form-group col-md-6">
                                    <label>Country <sup class="text-[red]">*</sup></label>
                                    <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" autocomplete="off" required value="{{ setting('COUNTRY_SERVE') ?? 'Bangladesh' }}" readonly/>
                                    @error('country')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="city">Division <sup class="text-[red]">*</sup></label>
                                    <select name="city" id="divisions"  class="form-control @error('city') is-invalid @enderror"  onchange="divisionsList();">
                                        <option disabled selected>Select Division</option>
                                        <option @if(auth()->user()->customer_info->city=='Barishal')selected @endif value="Barishal">Barishal</option>
                                        <option @if(auth()->user()->customer_info->city=='Chattogram')selected @endif value="Chattogram">Chattogram</option>
                                        <option @if(auth()->user()->customer_info->city=='Dhaka')selected @endif value="Dhaka">Dhaka</option>
                                        <option @if(auth()->user()->customer_info->city=='Khulna')selected @endif value="Khulna">Khulna</option>
                                        <option @if(auth()->user()->customer_info->city=='Mymensingh')selected @endif value="Mymensingh">Mymensingh</option>
                                        <option @if(auth()->user()->customer_info->city=='Rajshahi')selected @endif value="Rajshahi">Rajshahi</option>
                                        <option @if(auth()->user()->customer_info->city=='Rangpur')selected @endif value="Rangpur">Rangpur</option>
                                        <option @if(auth()->user()->customer_info->city=='Sylhet')selected @endif value="Sylhet">Sylhet</option>
                                    </select><!--/ Division Section-->
                                    
                                    @error('city')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="district">District <sup class="text-[red]">*</sup></label>
                                    <select name="distric"  class="form-control @error('district') is-invalid @enderror"  id="distr" onchange="thanaList();">
                                        <option @if(auth()->user()->customer_info->distric==Null) selected @endif disabled>Select District</option>
                                        <option @if(auth()->user()->customer_info->distric!=Null) selected value="{{auth()->user()->customer_info->distric}}" @endif> {{auth()->user()->customer_info->distric}}</option>
                                    </select><!--/ Districts Section-->
                                    @error('district')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="district">Thana <sup class="text-[red]">*</sup></label>
                                    <select name="thana"  class="form-control @error('district') is-invalid @enderror"  id="polic_sta">
                                        <option disabled @if(auth()->user()->customer_info->thana==Null) selected @endif>Select Thana</option>
                                        <option @if(auth()->user()->customer_info->thana!=Null) selected value="{{auth()->user()->customer_info->thana}}" @endif> {{auth()->user()->customer_info->thana}}</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Street <sup class="text-[red]">*</sup></label>
                                    <input type="text" placeholder="For Example: House# 123, Street# 123, ABC Road" name="street" id="street" class="form-control @error('street') is-invalid @enderror" autocomplete="off" required value="{{auth()->user()->customer_info->street}}"/>
                                    @error('street')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Post Code <sup class="text-[red]">*</sup></label>
                                    <input type="text" name="post_code" id="post_code" class="form-control @error('post_code') is-invalid @enderror" autocomplete="off" required value="{{auth()->user()->customer_info->post_code}}"/>
                                    @error('post_code')
                                        <small class="form-text text-danger">{{$message}}</small>
                                    @enderror
                                </div>
                                @endisset
                                
                            </div>
                            <button type="submit" class="mt-1 btn btn-primary btn-block">
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

@endsection

@push('js')
    
<script src="{{asset('/')}}assets/frontend/js/city.js"></script>

    <script>
        (function () {
            var input = document.getElementById('avatar');
            if (!input) return;
            input.addEventListener('change', function (e) {
                var file = e.target.files && e.target.files[0];
                if (!file) return;
                var url = URL.createObjectURL(file);
                var img = document.getElementById('pi-preview');
                var ph = document.getElementById('pi-placeholder');
                var drop = document.getElementById('pi-drop');
                if (img) { img.src = url; img.style.display = 'block'; }
                if (ph) { ph.style.display = 'none'; }
                if (drop) { drop.classList.remove('pi-empty'); }
            });
        })();
    </script>
@endpush