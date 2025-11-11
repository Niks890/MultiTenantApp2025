@extends('admin.master')
@section('title', __('item_detail', ['item' => strtolower(__('tenant'))]))

@section('content')
    <div class="page-heading">
        <x-central.page-header title="" :breadcrumbs="[
            __('manage_item', ['item' => strtolower(__('admin_tenant'))]) => route('admin-tenants.index'),
            __('item_detail', ['item' => strtolower(__('tenant'))]) => '',
        ]" />

        <section class="section">
            {{-- Admin Tenant Header --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body py-4">
                    <div class="row align-items-center">
                        @if (isset($adminTenantName))
                            <div class="col-auto row align-items-center">

                                <div class="col-auto">
                                    <div class="avatar avatar-lg bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 60px; height: 60px; font-size: 24px;">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <h5 class="card-title mb-1">{{ $adminTenantName }}</h5>
                                    <p class="text-muted mb-0 d-flex align-items-center">
                                        <i class="fas fa-store me-2"></i>
                                        <span class="badge bg-light-info color-info">
                                            {{ $tenants->count() }} {{ __('tenant') }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Empty State --}}
            @if ($tenants->isEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-5 text-center">
                        <div class="mb-3">
                            <i class="fas fa-inbox" style="font-size: 3rem; color: #d4d7e1;"></i>
                        </div>
                        <h5 class="text-muted mb-2">{{ __('nothing_item', ['item' => __('tenant')]) }}</h5>
                        <p class="text-muted">{{ __('no_tenant') }}</p>
                    </div>
                </div>
            @else
                {{-- Tenants Grid --}}
                <div class="row g-4">
                    @foreach ($tenants as $tenant)
                        <div class="col-12">
                            <div class="card border-0 shadow-sm h-100 modern-card" style="transition: all 0.3s ease;">
                                {{-- Card Header --}}
                                <div class="card-header border-0 bg-white pt-4 pb-3">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                @if ($tenant->logo)
                                                    <img class="rounded-circle"
                                                        src="{{ asset('storage/' . $tenant->logo) }}" alt="store_image"
                                                        style="width: 60px; height: 60px;">
                                                @else
                                                    <div class="avatar avatar-lg bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 60px; height: 60px; font-size: 24px;">
                                                        <i class="fas fa-store text-primary"></i>
                                                    </div>
                                                @endif
                                                <h6 class="card-title mb-0">{{ $tenant->name }}</h6>
                                            </div>
                                        </div>
                                        <div>
                                            @if ($tenant->is_active)
                                                <button class="border rounded-pill lh-sm switches active">
                                                    <span class="ms-2">{{ __('active') }}</span>
                                                    <i class="fas fa-circle"></i>
                                                </button>
                                            @else
                                                <div class="border rounded-pill lh-sm switches maintenance">
                                                    <i class="fas fa-circle"></i>
                                                    <span class="me-2">{{ __('maintenance') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Card Body --}}
                                <div class="card-body px-4">

                                    <div class="info-section mb-4">
                                        <h6 class="text-uppercase small fw-bold text-muted mb-3"
                                            style="letter-spacing: 0.5px;">
                                            <i class="fas fa-calendar-alt me-2"></i>{{ __('create_at') }}
                                        </h6>

                                        <p class="mb-0 fw-500">{{ $tenant->created_at->format('d/m/Y') }}</p>
                                    </div>

                                    {{-- Group Info --}}
                                    <div class="info-section mb-4">
                                        <h6 class="text-uppercase small fw-bold text-muted mb-3"
                                            style="letter-spacing: 0.5px;">
                                            <i class="fas fa-users me-2"></i>{{ __('group') }}
                                        </h6>
                                        @if (!empty($tenant->group?->name))
                                            <p class="mb-0 fw-500">{{ $tenant->group->name }}</p>
                                        @else
                                            <p class="text-muted small mb-0">{{ __('no_group') }}</p>
                                        @endif
                                    </div>

                                    <hr class="my-3" style="border-color: #f0f0f0;">
                                    @php
                                        $appHost = parse_url(config('app.url'), PHP_URL_HOST);
                                    @endphp
                                    {{-- Domain Info --}}
                                    <div class="info-section mb-4">
                                        <h6 class="text-uppercase small fw-bold text-muted mb-3"
                                            style="letter-spacing: 0.5px;">
                                            <i class="fas fa-globe me-2"></i>{{ __('domain') }}
                                        </h6>
                                        <div class="d-flex flex-column gap-2">
                                            @forelse ($tenant->domains as $domain)
                                                @php
                                                    $display = str_replace($appHost, '', $domain->domain);
                                                @endphp
                                                <div class="rounded" style="background-color: #f0f3ff;">
                                                    <code class="text-primary float-start p-2">{{ $display }}</code>
                                                    <code
                                                        class="text-primary float-end border-start border-primary-subtle rounded-0 p-2">{{ $appHost }}</code>
                                                </div>
                                            @empty
                                                <div class="rounded" style="background-color: #f0f3ff;">
                                                    <code
                                                        class="text-primary float-end border-start border-primary-subtle rounded-0 p-2">{{ $appHost }}</code>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>

                                    <hr class="my-3" style="border-color: #f0f0f0;">

                                    {{-- Technical Info --}}
                                    <div class="info-section">
                                        <h6 class="text-uppercase small fw-bold text-muted mb-3"
                                            style="letter-spacing: 0.5px;">
                                            <i class="fas fa-database me-2"></i>{{ __('technical_info') }}
                                        </h6>
                                        <div class="tech-info-grid"
                                            style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                                            <div>
                                                <p class="text-muted small mb-1">{{ __('database_name') }}</p>
                                                <code
                                                    class="d-block small text-break">{{ $tenant->db_name ?? $tenant->tenancy_db_name }}</code>
                                            </div>
                                            <div>
                                                <p class="text-muted small mb-1">{{ __('database_host') }}</p>
                                                <code
                                                    class="d-block small text-break">{{ $tenant->db_host ?? $tenant->tenancy_db_host }}:{{ $tenant->db_port ?? $tenant->tenancy_db_port }}</code>
                                            </div>
                                            <div>
                                                <p class="text-muted small mb-1">{{ __('access_key') }}</p>
                                                <code class="d-block small text-break">{{ $tenant->access_key }}</code>
                                            </div>
                                            <div>
                                                <p class="text-muted small mb-1">{{ __('hash_code') }}</p>
                                                <code class="d-block small text-break">{{ $tenant->hash_code }}</code>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col">
                        <a href="{{ route('admin-tenants.index') }}" class="btn btn-secondary-custom lh-lg"><i
                                class="fas fa-arrow-left me-2"></i> <span>{{ __('back') }}</span></a>
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/switches.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/admin-tenants.css') }}">
@endsection
