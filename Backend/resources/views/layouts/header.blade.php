<nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
            <i class="icon-base bx bx-menu icon-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center me-auto">
            <div class="nav-item d-flex align-items-center">
                <span class="w-px-22 h-px-22"><i class="icon-base bx bx-search icon-md"></i></span>
                <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2 d-md-block d-none"
                    placeholder="Search...">
            </div>
        </div>

        <!-- User -->
        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="#" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ auth()->guard('admin')->user()->avatar
                            ? auth()->guard('admin')->user()->avatar
                            : asset('assets/img/avatars/1.png') }}"
                            alt="{{ auth()->guard('admin')->user()->username }}" class="w-px-40 h-px-40 rounded-circle"
                            style="object-fit: cover;">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ auth()->guard('admin')->user()->avatar
                                            ? auth()->guard('admin')->user()->avatar
                                            : asset('assets/img/avatars/1.png') }}"
                                            alt="{{ auth()->guard('admin')->user()->username }}"
                                            class="w-px-40 h-px-40 rounded-circle" style="object-fit: cover;">
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ auth()->guard('admin')->user()->username }}</h6>
                                    <small
                                        class="text-body-secondary">{{ auth()->guard('admin')->user()->email }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider my-1"></div>
                    </li>

                    <li><a class="dropdown-item" href="{{ route('admin.my.profile') }}"><i class="bx bx-user me-3"></i>
                            My Profile</a></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.reset.password') }}">
                            <i class="bx bx-lock-alt me-3"></i> Reset Password
                        </a>
                    </li>
                    <li><a class="dropdown-item" href="#"><i class="bx bx-cog me-3"></i> Settings</a></li>
                    <li>
                        <form action="{{ route('admin.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item border-0 bg-transparent w-100 text-start">
                                <i class="bx bx-power-off me-3"></i> Log Out
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
