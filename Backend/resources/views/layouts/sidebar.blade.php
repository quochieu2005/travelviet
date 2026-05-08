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
        <!-- Menu items - Copy từ file gốc của bạn -->
        <li class="menu-item active open">
            <a href="#" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
            </a>
        </li>
        <!-- Thêm các menu khác vào đây... -->
    </ul>
</aside>
