@if($pop)
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a href="{{ $pop->url }}">
                    <div class="item">
                        <img src="{{ asset('uploads/slider/' . $pop->image) }}" />
                    </div>
                </a>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
@endif

@if($popBanner)
<div class="modal fade" id="bannerModal" tabindex="-1" role="dialog" aria-labelledby="bannerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bannerModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <a href="{{ $popBanner->url }}">
                    <div class="item">
                        <img src="{{ asset('uploads/banner/' . $popBanner->image) }}" />
                    </div>
                </a>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</div>
@endif

@push('internal_css')
.dtrr {width: 260px;} 
@media(max-width:576px) {
    .oc.shop-category .cat-row a:nth-last-child(2) {display: none;}
}
@media(max-width:1199px) {
    .hero-slider.col-lg-9 {flex: 0 0 72% !important; max-width: 72% !important;}
    .hero-categories ul li:last-child {display: none;}
    .hero-categories ul li:nth-last-child(2) {display: none;}
}
@media(max-width:1000px) {
    .hero-categories ul li:last-child {display: block;}
    .hero-categories ul li:nth-last-child(2) {display: block;}
    .dtrr {display: none;}
    .hero-slider.col-lg-9 {flex: inherit !important; max-width: inherit !important;}
}
@endpush


<section class="hero-area">
    <style>
        /* container */
        .hero-area .container { padding: 0 !important; }

        /* Use Bootstrap row behaviour (no overriding display) */
        .hero-row { margin: 0; }

        /* Slider column */
        .hero-slider { padding: 0;}

        .hero-slider .slider img {
            width: 100%;
            height: 585px;              /* desktop default */
            object-fit: cover;
            border-radius: 6px;
            display: block;
        }

        /* Banner column */
        .banner-section {
            padding: 0 12px;
            display: flex;
            flex-direction: column;     /* desktop/tablet: stacked inside right column */
            gap: 15px;
        }

        .banner-section .banner-item img {
            width: 100%;
            height: 285px;
            object-fit: cover;
            border-radius: 6px;
            display: block;
        }

        /* Tablet: make banners side-by-side and reduce heights */
        @media (max-width: 991px) {
            .hero-slider .slider img {
                height: 260px;
            }
            .banner-section {
                flex-direction: row;      /* two banners side-by-side */
                gap: 10px;
                padding: 0 8px;
            }
            .banner-section .banner-item img {
                height: 160px;
            }
        }

        /* Mobile (xs): stack slider then show banners side-by-side smaller */
        @media (max-width: 576px) {
            .hero-row { /* ensure Bootstrap row wraps */ }
            .hero-slider .slider img {
                height: 200px;
            }
            .banner-section {
                flex-direction: row;      /* keep banners side-by-side on mobile */
                gap: 8px;
                padding: 0 6px;
            }
            .banner-section .banner-item {
                flex: 1 1 50%;
                min-width: 0;            /* prevents overflow */
            }
            .banner-section .banner-item img {
                height: 100px;
            }
        }

        /* Small helper: avoid image hover transform interfering */
        .sub-slider img:hover { transform: none !important; }
    </style>

    <div class="container">
        <div class="row hero-row align-items-stretch">

            <!-- Left Side - Slider -->
            <div class="col-lg-8 col-md-12 col-12 hero-slider">
                <div class="slideshow">
                    <div class="slider slick-slides">
                        @foreach ($sliders as $slider)
                            <div>
                                <a href="{{ $slider->url }}">
                                    <img src="{{ asset('uploads/slider/' . $slider->image) }}" alt="Slider Image" />
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Side - Latest 2 Banners -->
            <div class="col-lg-4 col-md-12 col-12 banner-section">
                @foreach ($banners->take(2) as $banner)
                    <div class="banner-item">
                        <a href="{{ $banner->url }}">
                            <img src="{{ asset('uploads/banner/' . $banner->image) }}" alt="Banner Image" />
                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</section>

@push('internal_css')
.sub-slider img:hover {transform: scale(1.1) !important;}
.navbar_fixed {position: fixed; top: 0; left: 0; right: 0; z-index: 9999999;}
.catplay .draggable {padding: 20px 0px;}
.catplay .slick-slide {margin: 0px 5px;}
@endpush
