<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <span class="text-primary">
                    @include('layouts.partials.logo')
                </span>
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">Admin TravelViet</span>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>
    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Menu Dashboards -->
        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active open' : '' }}">
            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>

        <!-- Menu Destinations -->
        <li class="menu-item {{ request()->routeIs('admin.destinations.*') ? 'active open' : '' }}">
            <a href="{{ route('admin.destinations.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-map"></i>
                <div class="text-truncate" data-i18n="Destinations">Destinations</div>
            </a>
        </li>

        <!-- Menu Categories -->
        <li class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active open' : '' }}">
            <a href="{{ route('admin.categories.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-category"></i>
                <div class="text-truncate" data-i18n="Categories">Categories</div>
            </a>
        </li>

        <!-- Menu Tours -->
        <li class="menu-item {{ request()->routeIs('admin.tours.*') ? 'active open' : '' }}">
            <a href="{{ route('admin.tours.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-briefcase-alt-2"></i>
                <div class="text-truncate" data-i18n="Tours">Tours</div>
            </a>
        </li>

        {{-- Menu Hotels --}}
        <li class="menu-item {{ request()->routeIs('admin.hotels.*') ? 'active open' : '' }}">
            <a href="{{ route('admin.hotels.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-building-house"></i>
                <div class="text-truncate" data-i18n="Hotels">Hotels</div>
            </a>
        </li>

        <!-- Menu Promotions -->
        <li class="menu-item {{ request()->routeIs('admin.promotions.*') ? 'active open' : '' }}">
            <a href="{{ route('admin.promotions.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-gift"></i>
                <div class="text-truncate" data-i18n="Promotions">Promotions</div>
            </a>
        </li>

        <!-- Menu Images Tour -->
        <li class="menu-item {{ request()->routeIs('admin.image-tours.*') ? 'active open' : '' }}">
            <a href="{{ route('admin.image-tours.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-image"></i>
                <div class="text-truncate" data-i18n="Images">Images</div>
            </a>
        </li>

        <!-- Menu Tour Schedule -->
        <li class="menu-item {{ request()->routeIs('admin.tour-schedules.*') ? 'active open' : '' }}">
            <a href="{{ route('admin.tour-schedules.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-calendar"></i>
                <div class="text-truncate" data-i18n="Tour Schedules">Tour Schedules</div>
            </a>
        </li>

        <!-- Menu Contacts -->
        <li class="menu-item {{ request()->routeIs('admin.contacts.*') ? 'active open' : '' }}">
            <a href="{{ route('admin.contacts.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-phone"></i>
                <div class="text-truncate" data-i18n="Contacts">Contacts</div>
            </a>
        </li>

        <!-- Menu Pricing -->
        <li class="menu-item {{ request()->routeIs('admin.pricing.*') ? 'active' : '' }}">
            <a href="{{ route('admin.pricing.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-purchase-tag"></i>
                <div class="text-truncate" data-i18n="Pricing">Gói giá</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.pricing-inquiries.*') ? 'active' : '' }}">
            <a href="{{ route('admin.pricing-inquiries.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-envelope"></i>
                <div class="text-truncate" data-i18n="Inquiries">Yêu cầu đăng ký</div>
                @php
                    $pendingCount = App\Models\PricingInquiry::where('status', 'pending')->count();
                @endphp
                @if ($pendingCount > 0)
                    <span class="badge bg-danger rounded-pill ms-2">{{ $pendingCount }}</span>
                @endif
            </a>
        </li>

        <li
            class="menu-item {{ request()->routeIs('admin.blog-categories.*') || request()->routeIs('admin.blogs.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-blog"></i>
                <div class="text-truncate" data-i18n="Blog">Blog</div>
            </a>

            <ul class="menu-sub">
                <!-- Danh mục blog -->
                <li class="menu-item {{ request()->routeIs('admin.blog-categories.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.blog-categories.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-category"></i>
                        <div class="text-truncate" data-i18n="Categories">Danh mục</div>
                    </a>
                </li>

                <!-- Bài viết blog -->
                <li class="menu-item {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.blogs.index') }}" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-news"></i>
                        <div class="text-truncate" data-i18n="Posts">Bài viết</div>
                    </a>
                </li>

                <!-- Bình luận (nếu có) -->
                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-comment"></i>
                        <div class="text-truncate" data-i18n="Comments">Bình luận</div>
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</aside>
