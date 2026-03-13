@php
    $shopMenuActive = isMenuActive(['group.*', 'admin-tenants.*', 'tenant.*']);
@endphp
<div class="sidebar-wrapper active">
    <div class="sidebar-header position-relative">
        <div class="d-flex justify-content-center align-items-center">
            <div class="logo">
                <a href="{{ route('admin.index') }}">
                    <img src="{{ asset('assets/images/logos/logo-removebg-preview.png') }}" alt="Logo">
                </a>
            </div>
            <div class="sidebar-toggler x">
                <a href="javascript:void(0)" class="sidebar-hide d-xl-none d-block">
                    <i class="bi bi-x bi-middle"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="sidebar-menu">
        <ul class="menu">
            <li class="sidebar-title">{{ __('menu') }}</li>
            <li class="sidebar-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                <a href="{{ route('admin.index') }}" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i>
                    <span>{{ __('overview') }}</span>
                </a>
            </li>
            <li class="sidebar-item has-sub {{ $shopMenuActive ? 'active submenu-open' : '' }}">
                <a href="javascript:void(0)" class="sidebar-link">
                    <i class="bi bi-shop-window"></i>
                    <span>{{ __('manage_item', ['item' => strtolower(__('tenant'))]) }}</span>
                </a>
                <ul class="submenu" style="{{ $shopMenuActive ? 'display: block;' : '' }}">
                    <li class="submenu-item {{ request()->routeIs('tenant.index') ? 'active' : '' }}">
                        <a href="{{ route('tenant.index') }}" class="submenu-link">Danh sách cửa hiệu</a>
                    </li>
                    <li class="submenu-item {{ request()->routeIs('group.index') ? 'active' : '' }}">
                        <a href="{{ route('group.index') }}"
                            class="submenu-link">{{ __('manage_item', ['item' => strtolower(__('group_tenant'))]) }}
                        </a>
                    </li>
                    <li class="submenu-item {{ request()->routeIs('admin-tenants.index') ? 'active' : '' }}">
                        <a href="{{ route('admin-tenants.index') }}" class="submenu-link">
                            {{ __('manage_item', ['item' => strtolower(__('admin_tenant'))]) }}
                        </a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-item has-sub {{ request()->routeIs('contracts.*') || request()->routeIs('payment-methods.*') ? 'active submenu-open' : '' }}">
                <a href="javascript:void(0)" class="sidebar-link">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Quản lý cho thuê</span>
                </a>
                <ul class="submenu">
                    <li class="submenu-item {{ request()->routeIs('contracts.*') ? 'active' : '' }}">
                        <a href="{{ route('contracts.index') }}" class="submenu-link">Quản lý hợp đồng</a>
                    </li>
                    <li class="submenu-item {{ request()->routeIs('payment-methods.*') ? 'active' : '' }}">
                        <a href="{{ route('payment-methods.index') }}" class="submenu-link">Phương thức thanh toán</a>
                    </li>
                </ul>
            </li>
            <li class="sidebar-item {{ request()->routeIs('taxes.*') ? 'active' : '' }}">
                <a href="{{ route('taxes.index') }}" class="sidebar-link">
                    <i class="bi bi-percent"></i>
                    <span>{{ __('manage_item', ['item' => strtolower(__('tax'))]) }}</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('plans.*') ? 'active' : '' }}">
                <a href="{{ route('plans.index') }}" class="sidebar-link">
                    <i class="bi bi-box-seam"></i>
                    <span>{{ __('manage_item', ['item' => strtolower(__('plan'))]) }}</span>
                </a>
            </li>
            <li class="sidebar-item {{ request()->routeIs('system-user.*') ? 'active' : '' }}">
                <a href="{{ route('system-user.index') }}" class="sidebar-link">
                    <i class="bi bi-person-gear"></i>
                    <span>{{ __('manage_item', ['item' => strtolower(__('system_user'))]) }}</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="#" class="sidebar-link"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>{{ __('logout') }}</span>
                </a>

                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>
