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
    </ul>
</aside>
