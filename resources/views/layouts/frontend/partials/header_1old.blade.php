@use('App\Core\ShoppingCart\Facades\Cart')
<style>
    @font-face {
        font-family: muli;
        src: url('{{ asset('/') }}assets/frontend/font/Muli/Muli-VariableFont_wght.ttf');
    }

    header .main-menu {
        z-index: 999999;
    }

    @media(max-width:750px) {

        .sear_wrapper,
        #search-view {
            width: 100% !important;
        }
    }

    .goog-te-gadget img {
        display: none;
    }

    .goog-te-banner-frame {
        display: none;
    }

    .VIpgJd-ZVi9od-ORHb-OEVmcd {
        display: none !important;
    }
</style>
@if (!Request::is('/'))
    <style>
        .products .product .thumbnail {
            height: 190px !important;
        }

        #list-view .product .thumbnail img {
            width: 200px;
        }

        @media(max-width:767px) {
            #list-view .product .thumbnail img {
                width: inherit;
            }

            #list-view .product h4 {
                font-size: 17px;
                font-weight: 1;
                margin-top: 10px;
            }

            #list-view .product .details {
                margin-left: 15px;
            }

            #list-view .product .details .dis-label {
                display: none;
            }
        }
    </style>
@endif
<header class="not-home">
    <style>
        .dvts {
            display: flex;
            justify-content: center;
            text-align: center;
            width: 100%;


        }

        .dvts ul {
            display: flex;
            gap: 15px;
            list-style: none;
            padding: 0;
        }

        .dvts li,
        .dvts a {
            color: #e0d4d4;
            text-decoration: none;
        }

        @media (max-width: 600px) {
            .dvts ul {
                flex-direction: column;
            }
        }

        @media (min-width: 600px) {
            .carticone li {
                display: none
            }
        }
    </style>
    <div class="upper-header" style="border: 0px;">
        <div class="dvts">
            <ul>
                <li><i class="fal fa-phone-alt"></i> Call Us <a
                        href="tel:{{ setting('SITE_INFO_PHONE') }}">{{ setting('SITE_INFO_PHONE') }}</a></li>
                <li><a href=""><i style="padding:0px 7px;" class="fal fa-envelope"></i>Email:
                        {{ setting('email') }}</a></li>
            </ul>
        </div>



        <div class="container" style="padding: 0px 500px !important;">
            <div style="display: flex;">

                <div class="dvts">
                    <ul>
                        @if (auth()->check() && auth()->user()->role_id != 1)
                            <li><a href="{{ route('dashboard') }}"
                                    class="{{ Request::is('dashboard') ? 'active' : '' }}">My
                                    Account</a></li>
                        @endif
                        @auth
                            @if (auth()->user()->role_id == 2)
                                <span
                                    style="margin:-2px 5px;height: 15px;display: inline-block;width: 1px;background: black;"></span>
                                <li><a class="vendor-button" href="{{ routeHelper('dashboard') }}"> Dashboard1</a></li>
                            @endif
                        @endauth
                        @if (auth()->user())
                        @else
                            <!--<span -->
                            <!--    style="margin:-2px 5px;height: 15px;display: inline-block;width: 1px;background: #e0d4d4;"></span>-->
                            <!--<li><a style=" color: #e0d4d4; " href="{{ route('login') }}">Sign In</a></li>-->
                            <!--<span -->
                            <!--    style="margin:-2px 5px;height: 15px;display: inline-block;width: 1px;background: #e0d4d4;"></span>-->
                            <!--<li><a style=" color: #e0d4d4; " href="{{ route('register') }}">Sign Up</a></li>-->
                        @endif
                        <li hidden>
                            <div id="google_translate_element" onclick="foo();"> </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <style>
        .upper-header {
            background: rgba(34, 56, 90, 255);
            border-bottom: 1px solid rgba(34, 56, 90, 255);
        }

        @media screen and (max-width: 520px) {
            .logo-area {
                width: 92px;
            }

            #search_box {
                width: 100% !important;
            }

            .nav-categories {
                display: block !important;
            }

            .category-item {
                margin: 2px;
            }
        }

        header .main-menu .nav-bar .nav-menus ul li a:hover,
        header .main-menu .nav-bar .nav-menus ul li ul li a,
        .footer-mobile-menu ul li a:hover {
            color: var(--optional_bg_color_text) !important;
            background: #605d5c !important;
        }
        @media (max-width: 800px){
            .mb-hidden{
                display:none;
            }
        }
    </style>

    <div class="top-header header_area" style="background: #22385A !important; border-bottom: 0px;">
        <div class="container containe" style="gap: 10px;">
            <div class="mobile-menu-openar">
                <div class="bars">
                    <span style="background: #fff !important;"></span>
                    <span style="background: #fff !important;"></span>
                    <span style="background: #fff !important;"></span>
                </div>
            </div>
            <div class="logo-area">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('uploads/setting/' . setting('logo')) }}" alt="Application Logo"
                        style="width: auto; margin-left: 15%;">
                </a>
            </div>
            <div class="mobile-menu-openar">
                <ul class="carticone">
                    <li><a href="{{ route('cart') }}"
                            style="display: flex; align-items: center; position: relative;"><i
                                class="fal fa-shopping-basket"
                                style="color: #fff; font-size: 25px; margin-left: 12px;"></i><span
                                id="total-cart-amount"
                                style="background: red; color: white; font-size: 14px; font-weight: bold; width: 18px; height: 18px; display: flex; align-items: center; justify-content: center; border-radius: 50%; position: absolute; top: -5px; right: -10px;">{{ Cart::count() }}</span></a>
                    </li>

                    <!--    <li class="fixed-cart d-none"><a href="{{ route('cart') }}"><span style="padding-top: 7px;display:block"><i-->
                    <!--class="fas fa-shopping-bag" aria-hidden="true"></i></span> x {{ Cart::count() }} </a></li>-->
                </ul>
            </div>
            <div class="wrap" style="width: 30px;"></div>

            <!-- Search Toggle Button -->
            <button style="margin-left:30px;" id="toggle_search_btn" class="btn btn-primary d-lg-none">
                <!-- Search SVG Icon -->
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="18"
                     height="18"
                     viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2"
                     stroke-linecap="round"
                     stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
            <a style=" color: #e0d4d4; " href="{{ route('compare') }}">
                            <i class="fal fa-exchange-alt"></i>
                            <sup style="top: -15px;">
                                <span
                                    id="total-compare-amount">{{ session('compare') ? count(session('compare')) : 0 }}</span>
                            </sup>
            </a>


            <!-- Search Box (Initially Hidden) -->
            <div class="search-box mb-hidden" style=" padding: 0 0 0 8px;">
                <form action="{{ route('product.search') }}" method="GET" id="product-search-form">
                    <div id="search_box" class="input-group" style="margin: auto; width: 70%;">
                        <input placeholder="Enter Your 2Keywords" style="border-radius: 14px;" class="sear"
                            type="search" name="search" id="searchbox" value="{{ request('search') }}">
                        <button class="input-group-addon" style="border-radius: 0 14px 14px 0;" name="go">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 width="28"
                                 height="28"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2"
                                 stroke-linecap="round"
                                 stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </button>
                    </div>
                </form>
                <div class="search-results" id="search-results"></div>
            </div>
            

            <!-- jQuery Script for Toggle Effect -->
            @push('js')
                <script>
                    $(document).ready(function() {
                        $("#toggle_search_btn").click(function() {
                            $("#search_box").slideToggle(); // Slide effect for smooth show/hide
                        });
                    });
                </script>
            @endpush

            <style>
                /* Mobile View: Initially Hidden */
                @media (max-width: 601px) {
                    #search_box {
                        display: none;
                        /* Hide by default */
                    }

                    #toggle_search_btn {
                        display: block;
                        /* Show button */
                    }
                }

                /* Large Screen: Always Visible */
                @media (min-width: 602px) {
                    .search_box {
                        display: block;
                        /* Show search box */
                    }

                    .toggle_search_btn {
                        display: none;
                        /* Hide button */
                    }
                }
            </style>


            <div class="wrap" style="width: 30px;"></div>
            <div class="top-menu" style="width:%;">
                <ul>
                    <li><a style=" color: #e0d4d4; " href="{{ route('wishlist') }}"> <i class="fal fa-heart"
                                aria-hidden="true"></i> <sup style="top: -15px;"> <span
                                    id="total-cart-{{-- amount --}}">{{ App\Models\wishlist::where('user_id', auth()->id())->count() }}</span></sup></a>
                    </li>
                    <li>
                        <a style=" color: #e0d4d4; " href="{{ route('compare') }}">
                            <i class="fal fa-exchange-alt"></i>
                            <sup style="top: -15px;">
                                <span
                                    id="total-compare-amount">{{ session('compare') ? count(session('compare')) : 0 }}</span>
                            </sup>
                        </a>
                    </li>
                    <li>
                        <a style=" color: #e0d4d4; " href="{{ route('cart') }}">
                            <i class="fal fa-shopping-basket"></i>
                            <sup style="top: -15px;">
                                <span id="total-cart-amount2">{{ Cart::count() }}</span>
                            </sup>
                        </a>
                    </li>

                    @guest
                        {{-- <li><a href="{{route('login')}}">login</a></li>
                        <li><a href="{{route('register')}}">register</a></li>
                    <li><a href="{{route('login')}}">Sign in</a></li> --}}
                    @else
                        <style>
                            .dropdown-menu {
                                left: -87px;
                                text-align: center;
                            }
                        </style>
                        <li>
                            <div class="dropdown">
                                <button style=" color: #e0d4d4;outline: none !important; " class="dropdown-toggle"
                                    id="dropdownMenuButton" data-toggle="dropdown">
                                    <i class="fal fa-user"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                    <a class="dropdown-item" href="{{ route('login') }}">Dashboard</a>
                                </div>
                            </div>
                            <!--<a style=" color: #e0d4d4; " href="{{ route('logout') }}" onclick="event.preventDefault();-->
                        <!--    document.getElementById('logout-form').submit();">Log Out</a>-->
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </div>
    <div class="menu-overly"></div>


    {{-- Main Menu --}}
    @if (!empty(setting('MAIN_MENU_STYLE')))
        @include('layouts.frontend.partials.partial-part.header_main_menu_' . setting('MAIN_MENU_STYLE'))
    @else
        @include('layouts.frontend.partials.partial-part.header_main_menu_1')
    @endif

</header>

{{-- Header Advance Search - When Click Search icon then automatic this section in top with fixed search bar --}}
@include('layouts.frontend.partials.partial-part.header_advance_search')

@push('js')
    <script>
        $(window).on('load', function() {
            $('#myModal').modal('show');

        });
        var site_url = "{{ url('/') }}";
        $.ajax({
            url: site_url + "/render/superCat",
            type: "get",
            datatype: "html",
            beforeSend: function() {
                $('.ajax-loading').show();
            },
            success: function(response) {
                var result = $.parseJSON(response);
                $('.ajax-loading').hide();
                $("#superCat").append(result);
                subCat();
            },

        })

        function subCat() {
            var site_url = "{{ url('/') }}";
            $.ajax({
                url: site_url + "/render/subCat",
                type: "get",
                datatype: "html",
                beforeSend: function() {
                    $('.ajax-loading').show();
                },
                success: function(response) {
                    var result = $.parseJSON(response);
                    $('.ajax-loading').hide();
                    $("#subCat").append(result);

                },

            })
        }
    </script>
@endpush
