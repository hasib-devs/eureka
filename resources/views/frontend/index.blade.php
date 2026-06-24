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


    @if (setting('NOTICE_STATUS') != 0 || setting('NOTICE_STATUS') == '')
        <!--================ CUSTOM NOTICE =================-->
        <br>
        <style>
            .slick-dots {
                padding: 10px;
            }

            /* ===== FULL WIDTH VIDEO SECTION ===== */
            .homepage-video-section,
            .video-section,
            .home-video-wrapper {
                background: transparent !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                max-width: 100% !important;
            }

            .homepage-video-section .container,
            .video-section .container,
            .home-video-wrapper .container {
                max-width: 100% !important;
                width: 100% !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .homepage-video-section video,
            .video-section video,
            .home-video-wrapper video,
            .homepage-video-section iframe,
            .video-section iframe,
            .home-video-wrapper iframe {
                width: 100% !important;
                display: block !important;
                border-radius: 0 !important;
            }

            /* keep category menu normal font */
            .nav-categories>li>a,
            .nav-categories>li>button.link-like {
                font-family: inherit !important;
            }

            /* active menu color */
            .main-header .nav-categories>li>a.active {
                color: #e7c873 !important;
            }


            /* ===== FINAL FULL WIDTH VIDEO + BANNER FIX ===== */
            .lux-video-section,
            .lux-banner-showcase {
                width: 100vw !important;
                max-width: 100vw !important;
                margin-left: calc(50% - 50vw) !important;
                margin-right: calc(50% - 50vw) !important;
            }

            .lux-video-box,
            .lux-video-box video,
            .lux-banner-showcase-inner,
            .lux-banner-card,
            .lux-banner-card img {
                width: 100% !important;
                max-width: 100% !important;
            }


            /* ===== HOME CATEGORY + PRODUCT FULL WIDTH FIX ===== */
            .lux-category-section,
            .lux-product-section,
            .category-section,
            .product-section {
                width: 100vw !important;
                max-width: 100vw !important;
                margin-left: calc(50% - 50vw) !important;
                margin-right: calc(50% - 50vw) !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .lux-category-section .container,
            .lux-product-section .container,
            .category-section .container,
            .product-section .container {
                width: 100% !important;
                max-width: 100% !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .lux-products-grid,
            .lux-category-grid {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }


            /* ===== FORCE HOME CONTENT EDGE TO EDGE ===== */
            body:has(.lux-product-card) .container,
            body:has(.lux-product-card) . {
                max-width: 100% !important;
                width: 100% !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            .lux-category-wrapper,
            .lux-products-wrapper,
            .lux-product-grid,
            .lux-products-grid,
            .lux-category-grid,
            .lux-product-card {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .lux-product-grid,
            .lux-products-grid {
                gap: 0 !important;
            }
        </style>
        <section class="">
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
        @import url('https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@700&display=swap');



        .slick-dots {
            padding: 10px;
        }

        /* =========================
       Global Luxury Home Spacing
    ========================= */
        .lux-section-title {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 24px;
            color: #111;
        }

        /* =========================
       Category Section
    ========================= */
        .lux-category {
            padding: 28px 0 18px;
            background: #fff;
        }

        .lux-category .container {
            max-width: 1180px;
        }

        .lux-category-wrap {
            display: flex;
            gap: 18px;
            overflow-x: auto;
            scrollbar-width: none;
            justify-content: flex-start;
        }

        .lux-category-wrap::-webkit-scrollbar {
            display: none;
        }

        .lux-cat-item {
            min-width: 145px;
            text-align: center;
            text-decoration: none;
            color: #000;
        }

        .lux-cat-img {
            width: 145px;
            height: 145px;
            overflow: hidden;
            border-radius: 7px;
            background: #f5f5f5;
        }

        .lux-cat-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.4s ease;
        }

        .lux-cat-item:hover img {
            transform: scale(1.05);
        }

        .lux-cat-item span {
            display: block;
            margin-top: 9px;
            font-size: 13px;
            color: #111;
        }

        /* =========================
       Shop Header
    ========================= */
        .lux-shop-header {
            padding: 72px 20px 52px;
            text-align: center;
            background: #fff;
        }

        .lux-shop-header p {
            margin: 0 0 18px;
            color: #707070;
            font-size: 12px;
            letter-spacing: 7px;
            font-weight: 400;
        }

        .lux-shop-header h1 {
            margin: 0;
            color: #111;
            font-family: 'Cinzel Decorative', Georgia, serif;
            font-size: 52px;
            font-weight: 700;
            line-height: 1;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* =========================
       Premium Product Section
    ========================= */
        .lux-products {
            width: 100%;
            padding: 0 0 66px;
            background: #fff;
        }

        .lux-products . {
            padding-left: 34px;
            padding-right: 34px;
        }

        .product-slider .slick-slide {
            padding: 0 10px;
        }

        .product-slider .slick-list {
            margin: 0 -10px;
        }

        .lux-product-card {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        .lux-product-thumb {
            position: relative;
            height: 390px;
            overflow: hidden;
            border-radius: 7px;
            background: #f5f5f5;
        }

        .lux-product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .lux-product-info {
            padding: 13px 0 0;
        }

        .lux-info-top-row,
        .lux-variants-row,
        .lux-purchase-row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .lux-info-top-row {
            align-items: flex-start;
        }

        .lux-category-label {
            display: block;
            margin-bottom: 4px;
            font-size: 12px;
            color: #707070;
        }

        .lux-product-name {
            max-width: 210px;
            margin: 0;
            font-size: 19px;
            font-weight: 500;
            line-height: 1.16;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .lux-product-name a {
            color: #111;
            text-decoration: none;
        }

        .lux-wishlist-btn {
            margin-top: 3px;
            padding: 0;
            border: 0;
            background: transparent;
            color: #000;
            font-size: 12px;
            line-height: 1.2;
            white-space: nowrap;
            cursor: pointer;
        }

        .lux-variants-row {
            align-items: center;
            margin-top: 8px;
        }

        .lux-rating {
            color: #fbc831;
            font-size: 17px;
            line-height: 1;
            letter-spacing: -1px;
        }

        .lux-rating-count {
            margin-left: 4px;
            color: #707070;
            font-size: 12px;
        }

        .lux-color-variants {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .lux-color {
            width: 19px;
            height: 19px;
            display: inline-block;
            border-radius: 50%;
            border: 2px solid #eee;
        }

        .lux-purchase-row {
            align-items: center;
            margin-top: 12px;
        }

        .lux-product-price {
            margin: 0;
            color: #111;
            font-size: 19px;
            font-weight: 500;
        }

        .lux-add-to-cart {
            padding: 9px 18px;
            border: 0;
            border-radius: 7px;
            background: #000;
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s ease;
        }

        .lux-add-to-cart:hover {
            background: #333;
        }

        /* =========================
       Old Homepage Category Product Slider Support
    ========================= */
        .slick-slides .slick-slide {
            padding: 0 12px;
        }

        /* =========================
       Video Section
    ========================= */
        .lux-video-section {
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
        }

        .lux-video-wrap {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
        }

        .lux-video-card {
            padding: 0 !important;
            text-align: center;
            border: 0 !important;
            border-radius: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        .lux-video-card h2 {
            margin: 0 0 10px;
            font-size: 32px;
            font-weight: 800;
            color: #111;
        }

        .lux-video-card p {
            max-width: 650px;
            margin: 0 auto 24px;
            color: #666;
        }

        .lux-video-box video {
            width: 100% !important;
            max-height: none !important;
            border-radius: 0 !important;
            object-fit: cover;
            display: block;
        }

        /* =========================
       Dynamic Banner Showcase
    ========================= */
        .lux-banner-showcase {
            padding: 0 !important;
            margin: 0 !important;
            background: transparent !important;
            width: 100% !important;
        }

        .lux-banner-showcase-inner {
            width: 100%;
            margin: 0;
        }

        .lux-banner-card {
            position: relative;
            display: block;
            overflow: hidden;
            margin: 0;
            border-radius: 0;
            background: #111;
        }

        .lux-banner-card img {
            width: 100%;
            min-height: 520px;
            display: block;
            object-fit: cover;
        }

        .lux-banner-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(0, 0, 0, .12), rgba(0, 0, 0, .62));
            z-index: 1;
        }

        .lux-banner-content {
            position: absolute;
            top: 50%;
            right: 9%;
            transform: translateY(-50%);
            max-width: 430px;
            color: #fff;
            z-index: 2;
        }

        .lux-banner-content p {
            margin: 0 0 12px;
            font-size: 13px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .lux-banner-content h2 {
            margin: 0 0 14px;
            font-size: 52px;
            font-weight: 800;
            line-height: 1.05;
        }

        .lux-banner-content span {
            display: block;
            max-width: 360px;
            margin-bottom: 24px;
            font-size: 15px;
            line-height: 1.6;
            opacity: .86;
        }

        .lux-banner-buttons {
            display: flex;
            gap: 12px;
        }

        .lux-banner-buttons button {
            padding: 13px 24px;
            border: 0;
            border-radius: 6px;
            background: #ff6a21;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
        }

        .lux-banner-buttons .outline {
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .32);
        }

        /* =========================
       Premium Footer
    ========================= */
        .lux-footer {
            padding: 42px 20px 30px;
            background: #f6f6f6;
        }

        .lux-footer-cta {
            max-width: 1180px;
            margin: 0 auto 32px;
            text-align: center;
            padding: 58px 20px;
            border-radius: 16px;
            background: radial-gradient(circle at top, #3b3b3b 0%, #050505 62%, #000 100%);
            color: #fff;
        }

        .lux-footer-cta h3 {
            margin: 0 0 10px;
            font-size: 28px;
            font-weight: 700;
        }

        .lux-footer-cta p {
            margin: 0 0 20px;
            opacity: 0.72;
        }

        .lux-footer-btn {
            display: inline-block;
            padding: 11px 24px;
            background: #fff;
            color: #000;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
        }

        .lux-footer-main {
            max-width: 1180px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            gap: 50px;
            flex-wrap: wrap;
        }

        .lux-footer-left {
            max-width: 320px;
        }

        .lux-footer-left h4 {
            margin: 0 0 10px;
            font-size: 21px;
            font-weight: 600;
            color: #111;
        }

        .lux-footer-left p {
            margin: 0 0 14px;
            font-size: 14px;
            line-height: 1.6;
            color: rgba(0, 0, 0, .68);
        }

        .lux-footer-social span {
            display: inline-block;
            margin-right: 10px;
            font-size: 14px;
            color: #111;
        }

        .lux-footer-links {
            display: flex;
            gap: 64px;
        }

        .lux-footer-links h5 {
            margin: 0 0 12px;
            font-size: 15px;
            font-weight: 700;
            color: #111;
        }

        .lux-footer-links ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .lux-footer-links li {
            margin-bottom: 7px;
        }

        .lux-footer-links a {
            color: rgba(0, 0, 0, .72);
            font-size: 14px;
            text-decoration: none;
        }

        .lux-footer-bottom {
            max-width: 1180px;
            margin: 34px auto 0;
            padding-top: 14px;
            display: flex;
            justify-content: space-between;
            gap: 20px;
            font-size: 13px;
            color: rgba(0, 0, 0, .55);
            flex-wrap: wrap;
        }

        .lux-footer-bottom a {
            color: rgba(0, 0, 0, .55);
            text-decoration: none;
            margin-left: 16px;
        }

        /* =========================
       Responsive
    ========================= */
        @media (max-width: 992px) {
            .lux-product-thumb {
                height: 360px;
            }

            .lux-banner-content h2 {
                font-size: 44px;
            }
        }

        @media (max-width: 768px) {
            .lux-category {
                padding: 24px 0 10px;
            }

            .lux-category-wrap {
                padding: 0 10px;
                gap: 12px;
            }

            .lux-cat-item {
                min-width: 118px;
            }

            .lux-cat-img {
                width: 118px;
                height: 118px;
            }

            .lux-shop-header {
                padding: 50px 16px 38px;
            }

            .lux-shop-header p {
                font-size: 11px;
                letter-spacing: 4px;
            }

            .lux-shop-header h1 {
                font-size: 34px;
            }

            .lux-products {
                padding: 0 20px 50px;
            }

            .lux-products . {
                padding-left: 0;
                padding-right: 0;
            }

            .lux-product-thumb {
                height: 520px;
            }

            .lux-product-name {
                max-width: 220px;
                font-size: 24px;
            }

            .lux-rating {
                font-size: 18px;
            }

            .lux-color {
                width: 22px;
                height: 22px;
            }

            .lux-product-price {
                font-size: 22px;
            }

            .lux-add-to-cart {
                padding: 11px 22px;
                font-size: 14px;
            }

            .lux-video-section {
                padding: 35px 12px;
            }

            .lux-video-card {
                padding: 22px;
                border-width: 3px;
            }

            .lux-video-card h2 {
                font-size: 24px;
            }

            .lux-banner-showcase {
                padding: 36px 0;
            }

            .lux-banner-card img {
                min-height: 430px;
            }

            .lux-banner-content {
                left: 24px;
                right: 24px;
                max-width: none;
            }

            .lux-banner-content h2 {
                font-size: 34px;
            }

            .lux-banner-buttons {
                flex-wrap: wrap;
            }

            .lux-footer {
                padding: 32px 14px 24px;
            }

            .lux-footer-cta {
                padding: 44px 18px;
                margin-bottom: 28px;
            }

            .lux-footer-main {
                gap: 28px;
            }

            .lux-footer-links {
                width: 100%;
                gap: 28px;
                justify-content: space-between;
            }
        }

        @media (max-width: 450px) {
            .shop-category .cat-row .cat-item {
                width: 24%;
            }

            .lux-footer-links {
                flex-direction: column;
                gap: 18px;
            }
        }

        @media (max-width: 768px) {

            .hero-slider,
            .hero-slider .slider,
            .hero-slider .slider img,
            .slider,
            .slider img {
                height: 58vh !important;
                max-height: 520px !important;
            }

            .lux-product-thumb {
                height: 390px !important;
            }
        }

        @media (max-width: 450px) {

            .hero-slider,
            .hero-slider .slider,
            .hero-slider .slider img,
            .slider,
            .slider img {
                height: 50vh !important;
                max-height: 420px !important;
            }

            .lux-product-thumb {
                height: 340px !important;
            }
        }

        /* ===== FULL WIDTH VIDEO SECTION ===== */
        .homepage-video-section,
        .video-section,
        .home-video-wrapper {
            background: transparent !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            max-width: 100% !important;
        }

        .homepage-video-section .container,
        .video-section .container,
        .home-video-wrapper .container {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .homepage-video-section video,
        .video-section video,
        .home-video-wrapper video,
        .homepage-video-section iframe,
        .video-section iframe,
        .home-video-wrapper iframe {
            width: 100% !important;
            display: block !important;
            border-radius: 0 !important;
        }

        /* keep category menu normal font */
        .nav-categories>li>a,
        .nav-categories>li>button.link-like {
            font-family: inherit !important;
        }

        /* active menu color */
        .main-header .nav-categories>li>a.active {
            color: #e7c873 !important;
        }


        /* ===== FINAL FULL WIDTH VIDEO + BANNER FIX ===== */
        .lux-video-section,
        .lux-banner-showcase {
            width: 100vw !important;
            max-width: 100vw !important;
            margin-left: calc(50% - 50vw) !important;
            margin-right: calc(50% - 50vw) !important;
        }

        .lux-video-box,
        .lux-video-box video,
        .lux-banner-showcase-inner,
        .lux-banner-card,
        .lux-banner-card img {
            width: 100% !important;
            max-width: 100% !important;
        }


        /* ===== HOME CATEGORY + PRODUCT FULL WIDTH FIX ===== */
        .lux-category-section,
        .lux-product-section,
        .category-section,
        .product-section {
            width: 100vw !important;
            max-width: 100vw !important;
            margin-left: calc(50% - 50vw) !important;
            margin-right: calc(50% - 50vw) !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .lux-category-section .container,
        .lux-product-section .container,
        .category-section .container,
        .product-section .container {
            width: 100% !important;
            max-width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .lux-products-grid,
        .lux-category-grid {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }


        /* ===== FORCE HOME CONTENT EDGE TO EDGE ===== */
        body:has(.lux-product-card) .container,
        body:has(.lux-product-card) . {
            max-width: 100% !important;
            width: 100% !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .lux-category-wrapper,
        .lux-products-wrapper,
        .lux-product-grid,
        .lux-products-grid,
        .lux-category-grid,
        .lux-product-card {
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .lux-product-grid,
        .lux-products-grid {
            gap: 0 !important;
        }
    </style>

    {{-- CATEGORY SECTION --}}
    <section class="lux-category">
        <div class="container">

            {{-- CATEGORY TITLE --}}
            <h3 class="lux-section-title text-center">
                All Categories
            </h3>

            <div class="lux-category-wrap">

                @foreach ($categories as $category)
                    <a href="{{ route('category.product', $category->slug) }}" class="lux-cat-item">

                        <div class="lux-cat-img">
                            <img src="{{ asset('uploads/category/' . $category->cover_photo) }}"
                                alt="{{ $category->name }}">
                        </div>

                        <span>{{ $category->name }}</span>

                    </a>
                @endforeach

            </div>
        </div>
    </section>


    <!--Product area starts-->

    <!-- Product area starts -->
    @if (setting('LATEST_PRODUCT_STATUS') != 0 || setting('LATEST_PRODUCT_STATUS') == '')
        <section class="lux-products">
            <div class="lux-shop-header">
                <p>Robot-Crafted 3D Art</p>
                <h1>Cozy Lighting</h1>
            </div>
            <div class=" px-4">

                <div class="product-slider">
                    @foreach ($products as $product)
                        @php
                            $images = is_array($product->image) ? $product->image : json_decode($product->image, true);
                            if (!is_array($images)) {
                                $images = !empty($product->image) ? [$product->image] : [];
                            }

                            $mainImage = !empty($images[0])
                                ? asset('uploads/product/' . $images[0])
                                : asset('frontend/images/placeholder.png');

                            $price = $product->discount_price ?? $product->regular_price;
                            $categoryName =
                                optional($product->categories->first())->name ??
                                (optional($product->category)->name ?? 'Cozy Lighting');
                        @endphp

                        <div class="lux-product-card">
                            <div class="lux-product-thumb">
                                <a href="{{ url('product/' . $product->slug) }}">
                                    <img src="{{ $mainImage }}" alt="{{ $product->title }}">
                                </a>
                            </div>

                            <div class="lux-product-info">
                                <div class="lux-info-top-row">
                                    <div>
                                        <span class="lux-category-label">{{ $categoryName }}</span>
                                        <h2 class="lux-product-name">
                                            <a href="{{ url('product/' . $product->slug) }}">{{ $product->title }}</a>
                                        </h2>
                                    </div>

                                    <button type="button" class="lux-wishlist-btn">
                                        Save to Wishlist ♡
                                    </button>
                                </div>

                                <div class="lux-variants-row">
                                    <div>
                                        <span class="lux-rating">★★★★★</span>
                                        <span class="lux-rating-count">45 Review</span>
                                    </div>

                                    <div class="lux-color-variants">
                                        <span class="lux-color bg-[#000]"></span>
                                        <span class="lux-color bg-[#fff]"></span>
                                        <span class="lux-color bg-[#FFCC00]"></span>
                                    </div>
                                </div>

                                <div class="lux-purchase-row">
                                    <p class="lux-product-price">৳ {{ number_format($price) }}</p>

                                    <form id="lux-cart-form-{{ $product->id }}" action="{{ route('add.cart') }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $product->id }}">
                                        <input type="hidden" name="qty" value="1">

                                        <button type="button" class="lux-add-to-cart ajax-lux-cart-btn"
                                            data-form-id="lux-cart-form-{{ $product->id }}">
                                            Add to Cart
                                        </button>

                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!--Hridoy-->
    @if (!empty($homepage_category_products))
        @foreach ($homepage_category_products as $homepage_category)
            <div class="products">
                <div class="">
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

    @if (!empty($video))
        <section class="lux-video-section">
            <div class="lux-video-wrap">
                <div class="lux-video-card">
                    <h2>{{ $video->title }}</h2>

                    @if ($video->description)
                        <p>{{ $video->description }}</p>
                    @endif

                    <div class="lux-video-box">
                        <video controls poster="{{ $video->thumbnail ? asset('storage/' . $video->thumbnail) : '' }}">
                            <source src="{{ asset('storage/' . $video->video) }}">
                        </video>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if (!empty($banners) && $banners->count())
        <section class="lux-banner-showcase">
            <div class="lux-banner-showcase-inner">
                @foreach ($banners as $banner)
                    <a href="{{ $banner->url ?: 'javascript:void(0)' }}" class="lux-banner-card">
                        <img src="{{ asset('uploads/banner/' . $banner->image) }}" alt="Banner">
                        <div class="lux-banner-content">
                            <p>Premium Cozy Lighting</p>
                            <h2>Make Every Corner Glow</h2>
                            <span>Discover soft, elegant lighting made for modern homes.</span>
                            <div class="lux-banner-buttons">
                                <button type="button">Shop Now</button>
                                <button type="button" class="outline">Explore Collection</button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
    <section class="lux-footer">
        <div class="lux-footer-cta">
            <h3>Ready to transform your space?</h3>
            <p>Join thousands who are upgrading their home lighting experience.</p>
            <a href="{{ route('product') }}" class="lux-footer-btn">Shop Now</a>
        </div>

        <div class="lux-footer-main">
            <div class="lux-footer-left">
                <h4>AnasLuxyWorld</h4>
                <p>Premium cozy lighting crafted to elevate your space with elegance and warmth.</p>

                <div class="lux-footer-social">
                    <span>f</span>
                    <span>in</span>
                    <span>ig</span>
                </div>
            </div>

            <div class="lux-footer-links">
                <div>
                    <h5>Product</h5>
                    <ul>
                        <li><a href="{{ route('product') }}">Shop</a></li>
                        <li><a href="{{ route('product') }}">Categories</a></li>
                        <li><a href="{{ route('product') }}">New Arrivals</a></li>
                    </ul>
                </div>

                <div>
                    <h5>Resources</h5>
                    <ul>
                        <li><a href="{{ route('blogs') }}">Blogs</a></li>
                        <li><a href="{{ route('contact') }}">Support</a></li>
                        <li><a href="{{ route('blogs') }}">Guides</a></li>
                    </ul>
                </div>

                <div>
                    <h5>Company</h5>
                    <ul>
                        <li><a href="{{ route('page', ['slug' => 'about']) }}">About</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('page', ['slug' => 'refund-policy']) }}">Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="lux-footer-bottom">
            <span>© {{ date('Y') }} AnasLuxyWorld. All rights reserved.</span>
            <div>
                <a href="{{ route('page', ['slug' => 'privacy-policy']) }}">Privacy</a>
                <a href="{{ route('page', ['slug' => 'terms-and-conditions']) }}">Terms</a>
            </div>
        </div>
    </section>
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


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.ajax-lux-cart-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();

                    let form = document.getElementById(btn.getAttribute('data-form-id'));
                    if (!form) return;

                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: new FormData(form)
                        })
                        .then(res => res.json())
                        .then(data => {
                            document.querySelectorAll('.cart-count').forEach(function(el) {
                                if (data.count !== undefined) el.innerText = data.count;
                            });
                        })
                        .catch(err => console.log(err));
                });
            });
        });
    </script>

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
            $(document).ready(function() {

                if ($('.product-slider').length) {

                    $('.product-slider').slick({
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        rows: 1,
                        autoplay: true,
                        autoplaySpeed: 2500,
                        arrows: true,
                        dots: false,
                        speed: 600,
                        infinite: true,
                        cssEase: 'ease-in-out',

                        responsive: [{
                                breakpoint: 1200,
                                settings: {
                                    slidesToShow: 4,
                                    rows: 2
                                }
                            },
                            {
                                breakpoint: 992,
                                settings: {
                                    slidesToShow: 3,
                                    rows: 2
                                }
                            },
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 1,
                                    rows: 1
                                }
                            }
                        ]
                    });

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
            autoplay: true,
            autoplaySpeed: 4000,
            speed: 1200,
            fade: true,
            cssEase: 'ease-in-out',
            arrows: false,
            dots: false,
            pauseOnHover: false,
            infinite: true
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
