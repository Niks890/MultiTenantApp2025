<table class="table table-bordered align-middle modern-table">
    <thead class="">
        <tr>
            <th class="text-center">{{ __('no') }}</th>
            <th>Tên cửa hiệu</th>
            <th>Gói đăng ký</th>
            <th>Hình thức trả</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <th>Ngày đến hạn</th>
            <th>Tổng tiền phải trả</th>
            <th>Tổng tiền đã trả</th>
            <th class="text-center">Trạng thái hợp đồng</th>
            <th class="text-center">{{ __('action') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($contracts as $index => $contract)
            <tr class="table-row-hover">
                <td class="text-center tenant-info">
                    <span>{{ $contracts->firstItem() + $index }}</span>
                </td>
                <td>
                    <span class="tenant-info">{{ $contract->tenant->name }}</span>
                </td>
                <td>
                    <span class="tenant-info">{{ $contract->plan->name }}</span>
                </td>
                <td>
                    <span class="tenant-info">{{ $contract->payment_mode }}</span>
                </td>
                <td>
                    <span class="tenant-info">{{ $contract->start_at }}</span>
                </td>

                <td>
                    <span class="tenant-info">{{ $contract->end_at }}</span>
                </td>

                <td>
                    <span class="tenant-info">{{ $contract->due_date }}</span>
                </td>
                <td>
                    <span class="tenant-info">{{ $contract->amount_after_tax }}</span>
                </td>
                <td>
                    <span class="tenant-info">{{ $contract->total_paid }}</span>
                </td>
                <td class="text-center">
                    @if ($contract->status && !$contract->delete_flg)
                        <span class="status-badge status-active toggle-status" data-id="{{ $contract->id }}"
                            data-name="{{ $contract->name }}" data-status="{{ $contract->status ? 1 : 0 }}"
                            style="cursor: pointer;">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ __('active') }}
                        </span>
                    @elseif ($contract->delete_flg)
                        <span class="status-badge status-deleted">
                            <i class="fa-solid fa-trash me-1"></i>
                            {{ __('tenant_deleted') }}
                        </span>
                    @else
                        <span class="status-badge status-maintaince toggle-status" data-id="{{ $contract->id }}"
                            data-status="{{ $contract->is_active ? 1 : 0 }}" data-name="{{ $contract->name }}"
                            style="cursor: pointer;">
                            <i class="fas fa-ban me-1"></i>
                            {{ __('maintenance') }}
                        </span>
                    @endif
                </td>
                <td class="action-cell text-center">
                    <div class="action-buttons">
                        @if (!$contract->delete_flg)
                            <a href="{{ route('contracts.edit', $contract->id) }}" class="btn-action btn-action-edit"
                                data-bs-toggle="tooltip" title="{{ __('edit') }}">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif
                        <a href="{{ route('contracts.show', $contract->id) }}" class="btn-action btn-action-view"
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
