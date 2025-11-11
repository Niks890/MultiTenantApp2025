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

            {{-- Tổng quan --}}
            <li class="sidebar-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                <a href="{{ route('admin.index') }}" class="sidebar-link">
                    <i class="bi bi-grid-fill"></i>
                    <span>{{ __('overview') }}</span>
                </a>
            </li>

            {{-- Quản lý cửa hiệu --}}
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

            {{-- Quản lý thuế --}}
            <li class="sidebar-item {{ request()->routeIs('taxes.*') ? 'active' : '' }}">
                <a href="{{ route('taxes.index') }}" class="sidebar-link">
                    <i class="bi bi-percent"></i>
                    <span>{{ __('manage_item', ['item' => strtolower(__('tax'))]) }}</span>
                </a>
            </li>

            {{-- Hợp đồng & thanh toán --}}
            {{-- <li class="sidebar-item has-sub">
                <a href="javascript:void(0)" class="sidebar-link">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Hợp đồng &amp; thanh toán</span>
                </a>
                <ul class="submenu">
                    <li class="submenu-item">
                        <a href="javascript:void(0)" class="submenu-link">Quản lý hợp đồng</a>
                    </li>
                    <li class="submenu-item">
                        <a href="javascript:void(0)" class="submenu-link">Giao dịch thanh toán</a>
                    </li>
                    <li class="submenu-item">
                        <a href="javascript:void(0)" class="submenu-link">Phương thức thanh toán</a>
                    </li>
                </ul>
            </li> --}}

            {{-- Quản lý gói dịch vụ --}}
            <li class="sidebar-item {{ request()->routeIs('plans.*') ? 'active' : '' }}">
                <a href="{{ route('plans.index') }}" class="sidebar-link">
                    <i class="bi bi-box-seam"></i>
                    <span>{{ __('manage_item', ['item' => strtolower(__('plan'))]) }}</span>
                </a>
            </li>

            {{-- Quản lý người dùng hệ thống --}}
            <li class="sidebar-item {{ request()->routeIs('system-user.*') ? 'active' : '' }}">
                <a href="{{ route('system-user.index') }}" class="sidebar-link">
                    <i class="bi bi-person-gear"></i>
                    <span>{{ __('manage_item', ['item' => strtolower(__('system_user'))]) }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>
