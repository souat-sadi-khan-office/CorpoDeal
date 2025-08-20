<nav class="app-header navbar navbar-expand bg-body navAni">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item" style="margin-right: 10px;">
                <a class="btn btn-outline-dark istiyak btn-xs" data-lte-toggle="sidebar" href="#" role="button">
                    <i class="bi bi-list"></i>
                </a>
            </li>

            @if(Auth::guard('admin')->user()->hasPermissionTo('customer.view'))
                <li class="nav-item" style="margin-right: 10px;">
                    <a href="{{ route('admin.customer.view') }}" class="btn btn-outline-dark istiyak btn-xs" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View Customer Information">
                        <i class="bi bi-people"></i>
                    </a>
                </li>
            @endcan

            @if(Auth::guard('admin')->user()->hasPermissionTo('offline-order'))
                <li class="nav-item" style="margin-right: 10px;">
                    <a href="{{ route('admin.offline-order') }}" target="_blank" class="btn btn-outline-dark istiyak btn-xs" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Offline Order">
                        <i class="bi bi-bag-check"></i>
                    </a>
                </li>
            @endcan

            @if(Auth::guard('admin')->user()->hasPermissionTo('settings.general-settings'))
                <li class="nav-item" style="margin-right: 10px;">
                    <a href="{{ route('admin.settings.general') }}" target="_blank" class="btn btn-outline-dark istiyak btn-xs" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Settings">
                        <i class="bi bi-gear"></i>
                    </a>
                </li>
            @endcan

            @if(Auth::guard('admin')->user()->hasPermissionTo('settings.general-settings'))
                <li class="nav-item" style="margin-right: 10px;">
                    <a href="javascript:;" class="btn btn-outline-dark istiyak btn-xs" id="clearCache" data-url="{{ route('admin.clear.cache') }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Optimize">
                        <i class="bi bi-stars"></i>
                    </a>
                </li>
            @endcan

            <li class="nav-item">
                <a href="{{ route('home') }}" target="_blank" class="ml-2 btn btn-outline-dark istiyak btn-xs" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Website">
                    <i class="bi bi-globe-americas"></i>
                </a>
            </li>

            {{-- <li class="nav-item d-none d-md-block">
                <a href="javascript:;" class="nav-link">
                    {{ get_system_date(date('Y-m-d H:i:s')) }}
                    {{ get_system_time(date('Y-m-d H:i:s')) }}
                </a>
            </li> --}}

        </ul>

        <ul class="navbar-nav ms-center">
            <li id="clock" class="nav-item d-none d-md-block pt-md-1 mb-1"></li>
        </ul>

        <ul class="navbar-nav ms-end">

            <a class="nav-link show-search-modal" href="javascript:;" role="button">
                <i class="bi bi-search"></i>
            </a>

            <!-- Fullscreen Toggle -->
            <li class="nav-item">
                <a class="nav-link" href="javascript:;" data-lte-toggle="fullscreen">
                    <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                    <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none;"></i>
                </a>
            </li>

            <!-- Mode Toggle -->
            <li class="nav-item dropdown">
                <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static">
                    <span class="theme-icon-active">
                        <i class="my-1"></i>
                    </span>
                    <span class="d-lg-none ms-2" id="bd-theme-text">Toggle theme</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text"
                    style="--bs-dropdown-min-width: 8rem;">
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="false">
                            <i class="bi bi-sun-fill me-2"></i>
                            Light
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                            <i class="bi bi-moon-fill me-2"></i>
                            Dark
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="true">
                            <i class="bi bi-circle-half me-2"></i>
                            Auto
                            <i class="bi bi-check-lg ms-auto d-none"></i>
                        </button>
                    </li>
                </ul>
            </li>
            <!-- Notification Dropdown -->
            <li class="nav-item dropdown">
                <a href="javascript:;" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge badge bg-danger" id="notificationBadge">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end notification-dropdown" id="notificationDropdown">
                    <li class="notification-header d-flex justify-content-between align-items-center px-3 py-2">
                        <h6 class="m-0">Notifications</h6>
                        <button class="btn btn-sm btn-outline-secondary" id="markAllAsRead">Mark All as Read</button>
                    </li>
                    <li>
                        <ul class="notification-list list-unstyled mb-0" id="notificationList"></ul>
                    </li>
                </ul>
            </li>


            <!-- User Menu Dropdown -->
            <li class="nav-item dropdown user-menu">
                <a href="javascript:;" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img
                        src="{{ asset(Auth::guard('admin')->user()->avatar ? Auth::guard('admin')->user()->avatar : 'pictures/face.jpg') }}"
                        class="user-image rounded-circle shadow"
                        alt="User Image">
                    <span class="d-none d-md-inline">{{ Auth::guard('admin')->user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <li class="user-header text-bg-app-color">
                        <img src="{{ asset(Auth::guard('admin')->user()->avatar ? Auth::guard('admin')->user()->avatar : 'pictures/face.jpg') }}" class="rounded-circle shadow" alt="User Image">
                        <p>
                            {{ Auth::guard('admin')->user()->name }} - {{ Auth::guard('admin')->user()->designation }}
                            <small>Member since {{ date('F, Y', strtotime(Auth::guard('admin')->user()->created_at)) }}</small>
                        </p>
                    </li>
                    <li class="user-footer">
                        <a href="{{ route('admin.profile') }}" class="btn btn-soft-warning btn-flat">Profile</a>
                        <a href="javascript:;" data-url="{{ route('admin.logout') }}" id="logout" class="btn btn-soft-danger btn-flat float-end">Sign out</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
