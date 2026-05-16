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
        .ddd {
            display: flex !important;
            align-items: center;
        }
        
        
        .banner-section img {
            width: 100%;
            height: 233px;
            object-fit: cover;
            border-radius: 5px;
        }
        .banner-section .banner-item {
            margin-bottom: 15px;
        }

        /* Responsive Styles */
        
        @media (max-width: 1200px) {
            .hero-slider, .banner-section {
                width: 100%;
                flex: 0 0 100%;
                max-width: 100%;
            }
            .banner-section {
                display: flex;
                justify-content: space-between;
            }
            .banner-section .banner-item {
                flex: 1;
                margin: 0 5px;
            }
        }
        
        
        
        @media (max-width: 991px) {
            .hero-slider, .banner-section {
                width: 100%;
                flex: 0 0 100%;
                max-width: 100%;
            }
            .banner-section {
                display: flex;
                justify-content: space-between;
            }
            .banner-section .banner-item {
                flex: 1;
                margin: 0 5px;
            }
        }
        
        
        @media (max-width: 576px) {
            .ddd {
                flex-direction: column;
                align-items: center;
            }
            .banner-section {
                flex-direction: row;
            }
            .banner-section .banner-item {
                margin-bottom: 10px;
            }
            .banner-section img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        }
    </style>
    
    <div class="container" style="padding:0 !important;">
        <div class="row ddd">
            
            <!-- Left Side - Slider (8 Columns on Large Screens) -->
            <div class="col-lg-8 col-md-8 col-8 hero-slider">
                <div class="slideshow">
                    hi
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

            <!-- Right Side - Latest 2 Banners (4 Columns on Large Screens) -->
            <div class="col-lg-4 col-md-4 col-4 banner-section" >
                @foreach ($banners->take(2) as $banner)  {{-- Only show latest 2 banners --}}
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
