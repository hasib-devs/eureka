        <aside class="dashboard-sidebar">
            <div class="profile">
                <div>
                    <h4 class="text-lg">ANAS LUXY WORLD</h4>
                    <p>Admin</p>
                </div>
            </div>

            <nav class="nav-container">

                <!-- HOME -->
                <div class="menu-section">
                    <label>HOME</label>
                    <ul>
                        <li class="{{ request()->is('admin/dashboard') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/dashboard') }}'">
                            <i class="bx bxs-dashboard"></i> Dashboard
                        </li>
                    </ul>
                </div>

                <!-- ORDERS -->
                <div class="menu-section">
                    <label>ORDERS</label>
                    <ul>
                        <li class="{{ request()->is('admin/order') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/order') }}'">
                            <i class="bx bx-cart-alt"></i> Orders
                        </li>
                        <li class="{{ request()->is('admin/incomplete-leads') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/incomplete-leads') }}'">
                            <i class="bx bx-error-alt"></i> Incomplete Order
                        </li>
                        <li class="{{ request()->is('admin/order-guard') ? 'active' : '' }}"
                            style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-shield-alt-2"></i> Order Guard
                        </li>
                        <li class="{{ request()->is('admin/auto-call') ? 'active' : '' }}"
                            style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-phone-call"></i> Auto Call
                        </li>
                    </ul>
                </div>

                <!-- PRODUCTS -->
                <div class="menu-section">
                    <label>PRODUCTS</label>
                    <ul>
                        <li class="{{ request()->is('admin/product') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/product') }}'">
                            <i class="bx bx-package"></i> Products
                        </li>
                        <li class="{{ request()->is('admin/classic/list') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/classic/list') }}'">
                            <i class="bx bx-star"></i> Classic Product
                        </li>
                        <li class="{{ request()->is('admin/category') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/category') }}'">
                            <i class="bx bx-category"></i> Categories
                        </li>
                        <li class="{{ request()->is('admin/collection') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/collection') }}'">
                            <i class="bx bx-collection"></i> Collection
                        </li>
                        <li class="{{ request()->is('admin/brand') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/brand') }}'">
                            <i class="bx bx-bookmark"></i> Brands
                        </li>
                        <li class="{{ request()->is('admin/attribute/list') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/attribute/list') }}'">
                            <i class="bx bx-list-ul"></i> Attributes
                        </li>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-purchase-tag-alt"></i> Tags
                        </li>
                    </ul>
                </div>

                <!-- CUSTOMERS -->
                <div class="menu-section">
                    <label>CUSTOMERS</label>
                    <ul>
                        <li class="{{ request()->is('admin/customer') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/customer') }}'">
                            <i class="bx bx-user"></i> Customers
                        </li>
                        <li class="{{ request()->is('admin/connection/live-chat') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/connection/live-chat') }}'">
                            <i class="bx bx-message-dots"></i> Live Chat
                        </li>
                    </ul>
                </div>

                <!-- MARKETING -->
                <div class="menu-section">
                    <label>MARKETING</label>
                    <ul>
                        <li class="{{ request()->is('admin/campaing/list') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/campaing/list') }}'">
                            <i class="bx bx-bullseye"></i> Campaign
                        </li>
                        <li class="{{ request()->is('admin/coupon') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/coupon') }}'">
                            <i class="bx bx-cut"></i> Coupon
                        </li>
                    </ul>
                </div>

                <!-- CONTENT -->
                <div class="menu-section">
                    <label>CONTENT</label>
                    <ul>
                        <li class="{{ request()->is('admin/banner') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/banner') }}'">
                            <i class="bx bx-image"></i> Banners
                        </li>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-slideshow"></i> Sliders
                        </li>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-layer"></i> Sliders One
                        </li>
                        <li class="{{ request()->is('admin/notice') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/notice') }}'">
                            <i class="bx bx-info-circle"></i> Notice
                        </li>
                        <li class="{{ request()->is('admin/blogs') ? 'active' : '' }}"
                            onclick="window.location='{{ url('admin/blogs') }}'">
                            <i class="bx bx-news"></i> Blogs
                        </li>
                    </ul>
                </div>

                <!-- ANALYTICS -->
                <div class="menu-section">
                    <label>ANALYTICS & TOOLS</label>
                    <ul>
                        <li onclick="window.location='{{ url('admin/incomplete-leads/stats') }}'">
                            <i class="bx bx-line-chart"></i> Tracking
                        </li>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-shield-quarter"></i> Fraud Checker
                        </li>
                    </ul>
                </div>

                <!-- TEAM -->
                <div class="menu-section">
                    <label>TEAM</label>
                    <ul>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-group"></i> Staff
                        </li>
                        <li onclick="window.location='{{ url('admin/author') }}'">
                            <i class="bx bx-edit"></i> Author
                        </li>
                        <li style="opacity:.6;cursor:not-allowed;">
                            <i class="bx bx-store-alt"></i> Vendor
                        </li>
                    </ul>
                </div>

                <!-- SETTINGS -->
                <div class="menu-section">
                    <label>SETTINGS</label>
                    <ul>
                        <li onclick="window.location='{{ url('admin/connection/live-chat') }}'">
                            <i class="bx bx-link-alt"></i> Connection
                        </li>
                        <li onclick="window.location='{{ url('admin/php_info') }}'">
                            <i class="bx bx-cog"></i> Settings
                        </li>
                    </ul>
                </div>

            </nav>
        </aside>
