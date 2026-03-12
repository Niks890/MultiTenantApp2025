@extends('admin.master')
@section('title', __('item_detail', ['item' => strtolower(__('tenant'))]))

@section('content')
    <div class="page-heading">
        <x-central.page-header title="" :breadcrumbs="[
            __('manage_item', ['item' => strtolower(__('tenant'))]) => route('tenant.index'),
            __('item_detail', ['item' => strtolower(__('tenant'))]) => '',
        ]" />

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-start align-items-center">
                    <h4 class="card-title">{{ __('item_detail', ['item' => strtolower(__('tenant'))]) }}</h4>
                </div>

                <hr class="border-dashed-form">

                <div class="card-body rounded p-3">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                <tr class="border-top border-bottom align-middle">
                                    <th scope="row" class="py-3 px-4 w-25 border-end text-end">
                                        {{ __('tenant_logo') }}:
                                    </th>
                                    <td class="py-3 px-4">
                                        @if ($tenant['logo'])
                                            <img src="{{ asset('storage/' . $tenant['logo']) }}" alt="Logo"
                                                class="rounded-circle border"
                                                style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="avatar avatar-lg bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 60px; height: 60px; font-size: 24px;">
                                                <i class="fas fa-store text-primary"></i>
                                            </div>
                                        @endif

                                    </td>
                                </tr>

                                <tr class="border-bottom">
                                    <th scope="row" class="py-3 px-4 border-end text-end ">
                                        {{ __('tenant_name') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                        {{ $tenant['name'] ?? '' }}</td>
                                </tr>


                                <tr class="border-bottom">
                                    <th scope="row" class="py-3 px-4 border-end text-end ">
                                        {{ __('tenant_status') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                        <div>
                                            @if ($tenant['is_active'])
                                                <button class="border rounded-pill lh-sm switches active mb-0">
                                                    <span class="ms-2">{{ __('active') }}</span>
                                                    <i class="fas fa-circle"></i>
                                                </button>
                                            @elseif ($tenant['delete_flg'])
                                                <button class="border rounded-pill lh-sm switches deleted mb-0">
                                                    <i class="fas fa-circle"></i>
                                                    <span class="me-2">{{ __('tenant_deleted') }}</span>
                                                </button>
                                            @else
                                                <button class="border rounded-pill lh-sm switches maintenance mb-0">
                                                    <i class="fas fa-circle"></i>
                                                    <span class="me-2">{{ __('maintenance') }}</span>
                                                </button>
                                            @endif
                                        </div>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row" class="py-3 px-4 border-end text-end ">
                                        {{ __('site_name') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                        {{ $tenant['site_name'] ?? '' }}</td>
                                </tr>

                                <tr class="border-bottom">
                                    <th scope="row" class="py-3 px-4 border-end text-end ">
                                        {{ __('domain') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                         <a href="http://{{ $tenant['tenancy_domain'] }}:{{ request()->getPort() }}" target="_blank">
                                            <i class="fas fa-globe-americas text-dark text-primary-emphasis-custom"></i>
                                            <span class="text-dark text-primary-emphasis-custom">{{ $tenant['tenancy_domain'] }}</span>
                                        </a>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 px-4 border-end text-end ">
                                        {{ __('database_name') }}:
                                    </th>
                                    <td class="py-3 px-4 text-primary-emphasis-custom">
                                        {{ $tenant['tenancy_db_name'] ?? '' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 px-4 border-end text-end ">
                                        {{ __('database_host') }}:
                                    </th>
                                    <td class="py-3 px-4 text-primary-emphasis-custom">
                                        {{ $tenant['tenancy_db_host'] ?? '' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 px-4 border-end text-end ">
                                        {{ __('port') }}:
                                    </th>
                                    <td class="py-3 px-4 text-primary-emphasis-custom">
                                        {{ $tenant['tenancy_db_port'] ?? '' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 px-4 border-end text-end ">
                                        {{ __('username_database') }}:
                                    </th>
                                    <td class="py-3 px-4 text-primary-emphasis-custom">
                                        {{ $tenant['tenancy_db_username'] ?? '' }}</td>
                                </tr>

                                <tr class="border-bottom">
                                    <th class="py-3 px-4 border-end text-end ">
                                        {{ __('access_key') }}:
                                    </th>
                                    <td class="py-3 px-4 text-primary-emphasis-custom">
                                        {{ $tenant['tenancy_access_key'] ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th class="py-3 px-4 border-end text-end ">
                                        {{ __('hash_code') }}:
                                    </th>
                                    <td class="py-3 px-4 text-primary-emphasis-custom">
                                        {{ $tenant['tenancy_hash_code'] ?? '' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row" class="py-3 px-4 border-end text-end ">
                                        {{ __('admin_tenant_username') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                        {{$tenant['tenancy_admin_username']}} ({{ $tenant['tenancy_admin'] ?? '' }})</td>
                                </tr>

                                <tr class="border-bottom">
                                    <th scope="row" class="py-3 px-4 border-end text-end ">
                                        {{ __('tenant_group') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                        {{ $tenant['tenancy_group'] ?? '' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row" class="py-3 px-4 border-end text-end ">
                                        {{ __('address') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                        {{ $tenant['address'] ?? '' }}</td>
                                </tr>
                                <tr class="border-bottom">
                                    <th scope="row" class="py-3 px-4 border-end text-end ">
                                        {{ __('create_at') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark text-primary-emphasis-custom">
                                        {{ \Carbon\Carbon::parse($tenant['created_at'])->format('d/m/Y') }}
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 px-4 border-end text-end ">
                                        <i class="fab fa-facebook text-primary me-1"></i>{{ __('fb_url') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark">
                                        @if ($tenant['facebook_url'])
                                            <a href="{{ $tenant['facebook_url'] }}" target="_blank"
                                                class="text-decoration-none text-primary-emphasis-custom">
                                                {{ Str::limit($tenant['facebook_url'], 60, '...') ?? '' }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 px-4 border-end text-end">
                                        <i class="fab fa-tiktok text-dark me-1"></i>{{ __('tiktok_url') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark">
                                        @if ($tenant['tiktok_url'])
                                            <a href="{{ $tenant['tiktok_url'] }}" target="_blank"
                                                class="text-decoration-none text-primary-emphasis-custom">
                                                {{ Str::limit($tenant['tiktok_url'], 60, '...') ?? '' }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <th class="py-3 px-4 border-end text-end">
                                        <i class="fab fa-instagram me-1"></i>{{ __('ig_url') }}:
                                    </th>
                                    <td class="py-3 px-4 text-dark">
                                        @if ($tenant['instagram_url'])
                                            <a href="{{ $tenant['instagram_url'] }}" target="_blank"
                                                class="text-decoration-none text-primary-emphasis-custom">
                                                {{ Str::limit($tenant['instagram_url'], 60, '...') ?? '' }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 mb-3">
                        <button id="back-button" class="btn btn-secondary-custom lh-lg"
                            data-route="{{ route('tenant.index') }}">
                            <i class="fas fa-arrow-left me-2"></i> {{ __('back') }}
                        </button>
                        @if (!$tenant['delete_flg'])
                            <button id="edit-button" class="btn btn-success-custom lh-lg"
                                data-route="{{ route('tenant.edit', $tenant['id'] ?? '#') }}">
                                <i class="fas fa-pen me-2"></i> {{ __('edit') }}
                            </button>

                            <form id="delete-form" action="{{ route('tenant.destroy', $tenant['id'] ?? '#') }}"
                                method="POST" class="d-inline-block" data-name="{{ $tenant['name'] }}"
                                data-route="{{ route('tenant.index') }}">
                                @csrf
                                @method('DELETE')
                                <button id="delete-btn" type="submit" class="btn btn-danger-custom lh-lg">
                                    <i class="fas fa-trash me-2"></i> {{ __('delete') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/custom/css/input.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/switches.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/custom/css/admin-tenants.css') }}">
@endsection
@section('js')
    <script src="{{ asset('assets/custom/js/tenant/delete-tenant.js') }}"></script>
@endsection
