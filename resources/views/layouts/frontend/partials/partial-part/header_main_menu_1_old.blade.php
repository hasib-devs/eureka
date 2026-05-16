<div class="main-menu">
    <div class="container" style="padding: 0px 20px !important;">
        <div class="back">
            <i class="fas fa-long-arrow-alt-left"></i> back
        </div>
        <div class="collpase-menu-open" style="display: none;">
            <a id="menu" class="active" href="#">MENU</a>
            <a id="cat" href="#">CATEGORIES</a>
        </div>
        <div class="nav-bar">
            <!--<div class="header-category-wrap">-->
            <!--    <div class="header-category-nav">-->
            <!--        <span><i class="icofont icofont-navigation-menu"></i></span>-->
            <!--        Categories-->
            <!--        <span class="arrow"></span>-->
            <!--        <section class="hero-area" style="display: {{ Request::is('/') ? 'block' : '' }}">-->
            <!--            <div class="container">-->
            <!--                <div class="row" id="superCat">-->
            <!--                </div>-->
            <!--            </div>-->
            <!--        </section>-->
            <!--    </div>-->
            <!--    <div id="subCat"></div>-->
            <!--</div>-->

            <style>
                header .main-menu {
                    background: #22385A !important;
                    text-align: -webkit-center;
                    padding-bottom: 6px;
                }
            </style>
            <style>
                .nav-categories {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                    display: flex;
                    gap: 20px;
                }

                .nav-categories li {
                    position: relative;
                }

                .sub-menu {
                    display: none;
                    position: absolute;
                    left: 0;
                    top: 100%;
                    background: #fff;
                    padding: 0;
                    list-style: none;
                    border: 1px solid #ccc;
                    min-width: 160px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                    z-index: 999;
                }

                .sub-menu li a {
                    display: block;
                    padding: 8px 12px;
                    text-decoration: none;
                    color: #333;
                }

                .category-item:hover .sub-menu {
                    display: block;
                }

                .category-item {
                    /*width: max-content;*/
                }
            </style>

            <div class="nav-menus">
                <ul>
                    @php
                        use App\Models\Category;
                        $categories = Category::with('subcategories')->get();
                    @endphp

                    <ul class="nav-categories">
                        @if (auth()->check() && auth()->user()->role_id != 1)
                            <li class="authpro" style="border: 1px solid gray; border-radius: 10px; display: none;">
                                <img src="{{ asset('/') }}uploads/member/{{ auth()->user()->avatar == 'default.png' ? 'on_53876-5907.avif' : auth()->user()->avatar }}"
                                    style="width: 50px;height: 50px;border-radius: 50%;margin: auto;">
                                {{ auth()->user()->name }}
                            </li>
                        @endif

                        <!--@foreach ($categories as $category)
-->
                        <!--    <li>-->
                        <!--        <a style="padding: 6px !important;" href="{{ url('category/' . $category->slug) }}" class="{{ Request::is('/') ? 'active' : '' }}"> {{ $category->name }} </a>-->
                        <!--    </li>-->
                        <!--
@endforeach-->

                        <li style="border: 1px solid gray; border-radius: 10px; "><a style="padding: 6px !important;"
                                href="{{ route('home') }}" class="{{ Request::is('/') ? 'active' : '' }}">Home</a></li>
                        @foreach ($categories as $category)
                            <li class="category-item" style="border: 1px solid gray; border-radius: 10px; ">
                                <a style="padding: 6px !important;" href="{{ url('category/' . $category->slug) }}"
                                    class="{{ Request::is('/') ? 'active' : '' }}">
                                    @if ($category->status == 1)
                                        {{ $category->name }}
                                    @endif

                                </a>

                                @if ($category->subcategories->count())
                                    <ul class="sub-menu">
                                        @foreach ($category->subcategories as $sub)
                                            <li>
                                                <!--<a href="{{ url('subcategory/' . $sub->slug) }}">{{ $sub->name }}</a>-->
                                                <a
                                                    href="{{ url('sub-category/' . $sub->slug) }}">{{ $sub->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach

                        <li style="border: 1px solid gray; border-radius: 10px; "><a style="padding: 6px !important;"
                                href="{{ route('product') }}"
                                class="{{ Request::is('product*') ? 'active' : '' }}">All
                                Products</a></li>
                        <!--<li class="submenu" style="position:relative !important"><a style="padding: 6px !important;" href="{{ route('blogs') }}">Updates</a></li>-->
                        <li style="border: 1px solid gray; border-radius: 10px; "><a style="padding: 6px !important;"
                                href="{{ route('track') }}" class="{{ Request::is('track*') ? 'active' : '' }}">Order
                                Track</a></li>
                        <!-- <li><a href="{{ route('category') }}" class="{{ Request::is('category*') ? 'active' : '' }}">All Category</a></li> -->

                        <li style="border: 1px solid gray; border-radius: 10px; "><a style="padding: 6px !important;"
                                href="{{ route('contact') }}"
                                class="{{ Request::is('contact') ? 'active' : '' }}">Contact Us</a></li>
                        <!--  <li><a href="{{ route('sheba') }}" class="{{ Request::is('sheba') ? 'active' : '' }}"><i class="icofont icofont-live-support"></i> Sheba</a></li>  -->
                        <!-- <li><a href="{{ route('service') }}" class="{{ Request::is('service') ? 'active' : '' }}"><i class="icofont icofont-live-support"></i> Sheba</a></li> -->

                        @if (auth()->check() && auth()->user()->role_id != 1)
                            <li style="border: 1px solid gray; border-radius: 10px; "><a
                                    href="{{ route('order') }}">Orders</a></li>
                            <li style="border: 1px solid gray; border-radius: 10px; "><a
                                    href="{{ route('wishlist') }}">Wishlist</a></li>
                            <li style="border: 1px solid gray; border-radius: 10px; "><a
                                    href="{{ route('dashboard') }}"
                                    class="{{ Request::is('dashboard') ? 'active' : '' }}">My
                                    Account</a></li>
                        @endif

                        @if (auth()->check() && auth()->user()->role_id == 1)
                            <li style="border: 1px solid gray; border-radius: 10px; "><a
                                    href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        @endif
                        @foreach (App\Models\Page::where('position', 0)->where('status', 1)->get() as $page)
                            <li style="border: 1px solid gray; border-radius: 10px; "><a
                                    href="{{ route('page', ['slug' => $page->name]) }}">{{ $page->name }}</a></li>
                        @endforeach
                        <!--@foreach (App\Models\Page::where('position', 2)->where('status', 1)->get() as $page)
-->
                        <!--<li><a href="{{ route('page', ['slug' => $page->name]) }}">{{ $page->name }}</a></li>-->
                        <!--
@endforeach-->
                    </ul>
                </ul>
            </div>
        </div>
    </div>
</div>
