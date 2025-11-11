<table class="table table-bordered align-middle modern-table">
    <thead class="">
        <tr>
            <th class="text-center">{{ __('no') }}</th>
            <th style="width: 80px; min-height: 80px;">{{ __('tenant_logo') }}</th>
            <th>{{ __('tenant_name') }}</th>
            <th>{{ __('domain') }}</th>
            <th>{{ __('admin_tenant_username') }}</th>
            <th>{{ __('address') }}</th>
            <th class="text-center">{{ __('tenant_status') }}</th>
            <th class="text-center">{{ __('action') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($tenants as $index => $tenant)
            <tr class="table-row-hover">
                <td class="text-center tenant-info">
                    <span>{{ $tenants->firstItem() + $index }}</span>
                </td>
                <td>
                    <div>
                        @if ($tenant->logo)
                            <img class="rounded-circle" src="{{ asset('storage/' . $tenant->logo) }}" alt="Logo"
                                style="width: 60px; height: 60px;">
                        @else
                            <div class="avatar avatar-lg bg-light-primary text-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px; font-size: 24px;">
                                <i class="fas fa-store text-primary"></i>
                            </div>
                        @endif
                    </div>
                </td>
                <td>
                    <span class="tenant-info">{{ $tenant->name }}</span>
                </td>
                <td>
                    @php
                        $domain = $tenant->domains->first()?->domain;
                    @endphp
                    @if ($domain)
                        <div class="tenant-domain d-flex align-items-center justify-content-between">
                            <a href="http://{{ $domain }}:{{ request()->getPort() }}" target="_blank">
                                <i class="fas fa-globe-americas"></i>
                                <span class="tenant-domain-text">{{ $domain }}</span>
                            </a>
                            <i class="fas fa-copy ms-2 text-primary copy-domain" style="cursor: pointer;"
                                data-domain="{{ $domain }}:{{ request()->getPort() }}"></i>
                        </div>
                    @endif
                </td>
                <td>
                    @if ($tenant->admin_tenant_id && $tenant->adminTenant)
                        <span class="tenant-info">
                            {{ $tenant->adminTenant->username }}
                            <p class="tenant-info">{{ '(' . $tenant->adminTenant->display_name . ')' }}</p>
                        </span>
                    @elseif (!$tenant->delete_flg)
                        <div class="d-flex justify-content-start align-items-center">
                            <button type="button" class="btn btn-success btn-choose-admin"
                                data-tenant-id="{{ $tenant->id }}" data-tenant-name="{{ $tenant->name }}">
                                <i class="fa fa-plus"></i>
                            </button>
                        </div>
                    @endif
                </td>

                <td>
                    <span class="tenant-info" title="{{ $tenant->address ?? '' }}">
                        {{ $tenant->address ?? '' }}
                    </span>
                </td>
                <td class="text-center">
                    @if ($tenant->is_active && !$tenant->delete_flg)
                        <span class="status-badge status-active toggle-status" data-id="{{ $tenant->id }}"
                            data-name="{{ $tenant->name }}" data-status="{{ $tenant->is_active ? 1 : 0 }}"
                            style="cursor: pointer;">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ __('active') }}
                        </span>
                    @elseif ($tenant->delete_flg)
                        <span class="status-badge status-deleted">
                            <i class="fa-solid fa-trash me-1"></i>
                            {{ __('tenant_deleted') }}
                        </span>
                    @else
                        <span class="status-badge status-maintaince toggle-status" data-id="{{ $tenant->id }}"
                            data-status="{{ $tenant->is_active ? 1 : 0 }}" data-name="{{ $tenant->name }}"
                            style="cursor: pointer;">
                            <i class="fas fa-ban me-1"></i>
                            {{ __('maintenance') }}
                        </span>
                    @endif
                </td>
                <td class="action-cell text-center">
                    <div class="action-buttons">
                        @if (!$tenant->delete_flg)
                            <a href="{{ route('tenant.edit', $tenant->id) }}" class="btn-action btn-action-edit"
                                data-bs-toggle="tooltip" title="{{ __('edit') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif
                        <a href="{{ route('tenant.show', $tenant->id) }}" class="btn-action btn-action-view"
                            title="{{ __('view') }} {{ __('detail') }}">
                            <i class="fas fa-info-circle"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center">
                    <div class="empty-state">
                        <i class="fas fa-database fa-3x text-muted mb-3"></i>
                        <p class="text-muted">{{ __('no_data') }}</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
