@extends('layouts.frontend.app')
@push('meta')
    <meta property="og:image" content="{{ asset('uploads/setting/' . setting('auth_logo')) }}" />
@endpush
{{-- @section('title', setting('site_title')) --}}

@section('content')

    @php
        $pop = App\Models\Slider::where('is_pop', '1')->orderBy('id', 'desc')->first();
    @endphp

    @if (setting('SLIDER_LAYOUT_STATUS') != 0 || setting('SLIDER_LAYOUT_STATUS') == '')
        @if (!empty(setting('SLIDER_LAYOUT')))
            <!--================ slider Area =================-->
            @include('frontend.partial.slider_style_' . setting('SLIDER_LAYOUT'))
        @else
            @include('frontend.partial.slider_style_1')
            <!--================ / slider Area =================-->
        @endif
    @endif


    @if (setting('BELOW_SLIDER_HTML_CODE_STATUS') != 0 || setting('BELOW_SLIDER_HTML_CODE_STATUS') == '')
        <!--================ CUSTOM HTML BELOW SLIDER =================-->
        @php
            echo setting('BELOW_SLIDER_HTML_CODE');
        @endphp
        <!--================ / CUSTOM HTML BELOW SLIDER =================-->
    @endif


    @if (setting('NOTICE_STATUS') != 0 || setting('NOTICE_STATUS') == '')
        <!--================ CUSTOM NOTICE =================-->
        <br>
        <style>
            .slick-dots {
                padding: 10px;
            }
        </style>
        <section class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            New Updates
                        </div>
                        <div class="card-body">
                            {!! setting('CUSTOM_NOTICE') !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <br>
        <!--================ / CUSTOM NOTICE =================-->
    @endif

    <style>
        @media (max-width: 450px) {
            .shop-category .cat-row .cat-item {
                width: 24%;
            }
        }
        .slick-slides .slick-slide {
    padding: 0 40px; /* 👉 product er majhe gap */
}

.slick-list {
    margin: 0 -10px; /* 👉 outer spacing fix */
}
    </style>
    @if (setting('BRAND_STATUS') != 0 || setting('BRAND_STATUS') == '')
        <!--================product  Area start=================-->
        <div class="shop-category shop-brand" style="padding-bottom: 20px;text-align: center;">
            <div class="container-fluid">
                <h3 class="title" style="margin-bottom: 0 !important;"><span>Top Brands</span> <a href="/brands/list">view
                        all</a></h3>
                <div class="cat-row" style="display:block;">
                    @foreach (App\Models\Brand::where('status', 1)->take(7)->get() as $brand)
                        <a href="{{ route('brand.product', ['slug' => $brand->slug]) }}" class="cat-item">
                            <div class="">
                                <div class="thumbnail">
                                    <!--<img  src="{{ asset('uploads/brand/' . $brand->cover_photo) }}" alt="">-->
                                    <h3>{{ $brand->name }}</h3>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <!--================product  Area End=================-->
    @endif


    @if (setting('LATEST_PRODUCT_STATUS') != 0 || setting('LATEST_PRODUCT_STATUS') == '')
        <!--================ Latest product Area =================-->
        <div class="products mt">
            <div class="container-fluid">
                <!--<h3 class="title"><span>Latest Products</span> <a href="{{ route('product') }}">View All</a></h3>-->

                <h3 class="title"><span>Latest Products</span> </h3>
                <div class="row autoplay slick-slides">
                    @forelse ($products as $product)
                        <x-product-grid-view :product="$product" classes="" />
                    @empty
                        <x-product-empty-component />
                    @endforelse
                </div>
            </div>
        </div>
        <!--================ / Latest product Area =================-->
    @endif

    <!--Hridoy-->
    @if (!empty($homepage_category_products))
        @foreach ($homepage_category_products as $homepage_category)
            <div class="products">
                <div class="container-fluid">
                    <h3 class="title"><span>{{ $homepage_category->name }}</span> <a
                            href="{{ url('category/' . $homepage_category->slug) }}">View All</a></h3>
                    <div class="row autoplay slick-slides">
                        @forelse ($homepage_category->products->take(6) as $product)
                            <x-product-grid-view :product="$product" classes="" />
                        @empty
                            <x-product-empty-component />
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    @if (setting('NEWS_LETTER_STATUS') != 0 || setting('NEWS_LETTER_STATUS') == '')
        <div class="bef-footer">
            <div class="container-fluid" style="">
                <div class="items">
                    <div class="search-box">

                        <div class="row">
                            <div class="col-md-6">
                                <h5 style="margin-bottom: 10px;"><b>Sign Up For Newsletter</b> </h5>
                                <h6>
                                    We'll never share your email address with a third-party</h6>
                            </div>
                            <div class="col-md-6">
                                <form action="{{ route('subscription') }}" method="Post" id="subs">
                                    @csrf
                                    <div class="input-group">
                                        <input class="sear" type="email" name="subscription"
                                            placeholder="Enter Your Email">
                                        <button style="width:initial" class="input-group-addon components-bg"
                                            type="submit">Subscribe </button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <x-add-cart-modal />
    @include('components.cart-modal-attri')

    {{-- Catgory Collups and Expand System --}}
    @push('internal_css')
        .superCatHomeToggle{height:330px;overflow-y:hidden;}.superCatHomeToggle
        #superCatViewAll{bottom:0;}#superCatViewAll{position:absolute;bottom:-1.5rem;left:0;right:0;background:var(--MAIN_MENU_BG);color:var(--MAIN_MENU_ul_li_color);z-index:999;outline:none;}
    @endpush
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // var buttonElement = document.createElement('button');
                // buttonElement.id = 'superCatViewAll';
                // buttonElement.innerText = 'View All';
                var superCatElement = document.getElementById('superCat');
                // superCatElement.appendChild(buttonElement);

                superCatElement.classList.add('superCatHomeToggle');

                superCatElement.addEventListener('mouseenter', function() {
                    superCatElement.classList.remove('superCatHomeToggle');
                });

                superCatElement.addEventListener('mouseleave', function() {
                    superCatElement.classList.add('superCatHomeToggle');
                });

                // buttonElement.addEventListener('click', function () {
                //     superCatElement.classList.toggle('superCatHomeToggle');
                //     if (buttonElement.innerText === 'View All') {
                //         buttonElement.innerText = 'Close';
                //     } else {
                //         buttonElement.innerText = 'View All';
                //     }
                // });
            });
        </script>
    @endpush
    {{-- / Catgory Collups and Expand System --}}

@endsection



@push('js')
    <script>
        $(document).ready(function() {
            $('.value-plus').on('click', function() {
                var divUpd = $(this).parent().find('.value'),
                    newVal = parseInt(divUpd.val(), 10) + 1;
                divUpd.val(newVal);
                $('input#qty').val(newVal);
            });

            $('.value-minus').on('click', function() {
                var divUpd = $(this).parent().find('.value'),
                    newVal = parseInt(divUpd.val(), 10) - 1;
                if (newVal >= 1) {
                    divUpd.val(newVal);
                    $('input#qty').val(newVal);
                }

            });

            $(document).on('submit', '#addToCart', function(e) {
                e.preventDefault();

                let url = $(this).attr('action');
                let type = $(this).attr('method');
                let btn = $(this);
                let formData = $(this).serialize();

                $.ajax({
                    type: type,
                    url: url,
                    data: formData,
                    dataType: 'JSON',
                    beforeSend: function() {
                        $(btn).attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.alert != 'Congratulations') {

                            $.toast({
                                heading: 'Warning',
                                text: response.message,
                                icon: 'warning',
                                position: 'top-right',
                                stack: false
                            });
                        } else {

                            // Hridoy
                            loadCartOnCanvas()

                            $('span#total-cart-amount').text(response.subtotal);

                            $.toast({
                                heading: 'Congratulations',
                                text: response.message,
                                icon: 'success',
                                position: 'top-right',
                                stack: false
                            });

                            $('#cart-modal').modal('hide');
                        }

                    },
                    complete: function() {
                        $(btn).attr('disabled', false);
                    },
                    error: function(xhr) {
                        $.toast({
                            heading: xhr.status,
                            text: xhr.responseJSON.message,
                            icon: 'error',
                            position: 'top-right',
                            stack: false
                        });
                    }
                });
            });
            $(document).on('submit', '#subs', function(e) {
                e.preventDefault();

                let url = $(this).attr('action');
                let type = $(this).attr('method');
                let btn = $(this);
                let formData = $(this).serialize();

                $.ajax({
                    type: type,
                    url: url,
                    data: formData,
                    dataType: 'JSON',
                    beforeSend: function() {
                        $(btn).attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.alert != 'Congratulations') {

                            $.toast({
                                heading: 'Warning',
                                text: response.message,
                                icon: 'warning',
                                position: 'top-right',
                                stack: false
                            });
                        } else {
                            $('span#total-cart-amount').text(response.subtotal);

                            $.toast({
                                heading: 'Congratulations',
                                text: response.message,
                                icon: 'success',
                                position: 'top-right',
                                stack: false
                            });

                            $('#cart-modal').modal('hide');
                        }

                    },
                    complete: function() {
                        $(btn).attr('disabled', false);
                    },
                    error: function(xhr) {
                        $.toast({
                            heading: xhr.status,
                            text: xhr.responseJSON.message,
                            icon: 'error',
                            position: 'top-right',
                            stack: false
                        });
                    }
                });
            })

        });

        $('.slider').slick({
            draggable: true,
            autoplay: true,
            autoplaySpeed: 2500,
            arrows: false,
            dots: true,
            fade: true,
            speed: 500,
            infinite: true,
            cssEase: 'ease-in-out',
            touchThreshold: 100
        })
        $('.autoplay2').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2500,
            arrows: false,
            speed: 500,
            infinite: true,
            cssEase: 'ease-in-out',
            touchThreshold: 100,
            responsive: [{
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2,
                    }
                },

            ]
        });
        
        $('.autoplay').slick({
    slidesToShow: 4,   // 👉 এখানেই control
    slidesToScroll: 1,
    autoplay: true,
    autoplaySpeed: 2500,
    arrows: false,
    speed: 500,
    infinite: true,
    cssEase: 'ease-in-out',
    touchThreshold: 100,
    responsive: [
        {
            breakpoint: 992,
            settings: {
                slidesToShow: 3
            }
        },
        {
            breakpoint: 767,
            settings: {
                slidesToShow: 2
            }
        }
    ]
});
        $('.catplay').slick({
            slidesToShow: 7,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2500,
            arrows: false,
            speed: 500,
            infinite: true,
            cssEase: 'ease-in-out',
            touchThreshold: 100,
            responsive: [

                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2,
                    }
                },

            ]
        });
    </script>

    @if (env('FIREBASE_ON') == 1)
        <script src="https://www.gstatic.com/firebasejs/8.2.0/firebase.js"></script>
        <script>
            var firebaseConfig = {
                apiKey: env('FIREBASAE_apiKey'),
                authDomain: env('FIREBASAE_authDomain'),
                projectId: env('FIREBASAE_projectId'),
                storageBucket: env('FIREBASAE_storageBucket'),
                messagingSenderId: env('FIREBASAE_messagingSenderId'),
                appId: env('FIREBASAE_appId')
            };

            firebase.initializeApp(firebaseConfig);
            const messaging = firebase.messaging();


            messaging
                .requestPermission()
                .then(function() {
                    return messaging.getToken()
                })
                .then(function(token) {
                    console.log(token);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '{{ route('save-token') }}',
                        type: 'POST',
                        data: {
                            token: token
                        },
                        dataType: 'JSON',
                        success: function(response) {
                            alert('Token saved successfully.');
                        },
                        error: function(err) {
                            console.log('User Chat Token Error' + err);
                        },
                    });

                }).catch(function(err) {
                    console.log('User Chat Token Error' + err);
                });


            messaging.onMessage(function(payload) {
                const noteTitle = payload.notification.title;
                const noteOptions = {
                    body: payload.notification.body,
                    icon: payload.notification.icon,
                };
                new Notification(noteTitle, noteOptions);
            });
        </script>
    @endif

    <script type="text/javascript">
        $(window).on('load', function() {
            $('#myModal').modal('show');
        });
    </script>
@endpush
