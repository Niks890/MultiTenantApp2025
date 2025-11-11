<table class="table table-bordered modern-table mb-3">
    <thead>
        <tr>
            <th class="text-center">{{ __('no') }}</th>
            <th>{{ __('account_name') }}</th>
            <th>{{ __('full_name') }}</th>
            <th>{{ __('email') }}</th>
            <th>{{ __('phone') }}</th>
            <th>{{ __('address') }}</th>
            <th>{{ __('tenant') }}</th>
            <th class="text-center">{{ __('action') }}</th>
        </tr>
    </thead>
    <tbody id="table-body">

        @forelse ($adminTenants as $index => $adminTenant)
            <tr class="table-row-hover">
                <td class="text-center">{{ $adminTenants->firstItem() + $index }}</td>
                <td>
                    <span class="truncate-text">
                        {{ $adminTenant->username }}
                    </span>
                </td>
                <td>
                    <span>
                        {{ $adminTenant->display_name }}
                    </span>
                </td>
                <td>
                    <span class="truncate-text" title="{{ $adminTenant->email }}">
                        {{ $adminTenant->email }}
                    </span>
                </td>
                <td>{{ $adminTenant->phone_number }}</td>
                <td title="{{ $adminTenant->address }}" style="max-width: 300px;">
                    <span class="truncate-text">
                        {{ $adminTenant->address }}
                    </span>
                </td>
                <td class="td-nowrap" data-tenant-id="{{ $adminTenant->id }}">
                    @php
                        $tenants = $adminTenant->tenants()->notDeleted();
                        $count = $tenants->count();
                    @endphp
                    @if ($count !== 0)
                        <div class="d-flex align-items-center gap-2 justify-content-center">
                            <a class="btn btn-outline-primary show-more-tenants" title="Xem chi tiết cửa hiệu"
                                href="{{ route('admin-tenants.getTenants', ['id' => $adminTenant->id]) }}">

                                <i class="fas fa-building"></i>

                                <span class="tenant-count-badge">
                                    {{ $count }}
                                </span>
                            </a>
                        </div>
                    @endif
                </td>
                <td class="td-nowrap text-center">
                    <a href="{{ route('admin-tenants.edit', ['id' => $adminTenant->id]) }}"
                        class="edit-admin-tenant btn-action btn-action-edit" data-id="{{ $adminTenant->id }}">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('admin-tenants.show', $adminTenant->id) }}" class="btn-action btn-action-view"
                        title="{{ __('view') }} {{ __('detail') }}">
                        <i class="fas fa-info-circle"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <i class="fas fa-database fa-3x text-muted"></i>
                    <p class="text-muted mt-2 mb-0">{{ __('no_data') }}</p>
                </td>
            </tr>
        @endforelse

    </tbody>
</table>
