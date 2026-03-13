<table class="table table-bordered align-middle modern-table">
    <thead class="">
        <tr>
            <th class="text-center">{{ __('no') }}</th>
            <th>{{ __('tenant_name') }}</th>
            <th>{{ __('plan') }}</th>
            <th>{{ __('payment_mode') }}</th>
            <th>{{ __('start_at') }}</th>
            <th>{{ __('end_at') }}</th>
            <th>{{ __('due_date') }}</th>
            <th>{{ __('amount_after_tax') }}</th>
            <th>{{ __('total_paid') }}</th>
            <th class="text-center">{{ __('contract_status') }}</th>
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
                    @if ($contract->payment_mode == 1)
                        <span class="tenant-info">{{ __('cash') }}</span>
                    @elseif($contract->payment_mode == 2)
                        <span class="tenant-info">{{ __('bank_transfer') }}</span>
                    @elseif($contract->payment_mode == 3)
                        <span class="tenant-info">{{ __('credit_card') }}</span>
                    @elseif($contract->payment_mode == 4)
                        <span class="tenant-info">{{ __('e-wallet') }}</span>
                    @endif
                </td>
                <td>
                    <span class="tenant-info">{{ \Carbon\Carbon::parse($contract->start_at)->format('d/m/Y') }}</span>
                </td>

                <td>
                    <span class="tenant-info">{{ \Carbon\Carbon::parse($contract->end_at)->format('d/m/Y') }}</span>
                </td>

                <td>
                    <span class="tenant-info">{{ \Carbon\Carbon::parse($contract->due_date)->format('d/m/Y') }}</span>
                </td>
                <td>
                    <span class="tenant-info">{{ number_format($contract->amount_after_tax, 0, '.', ',') }} đ</span>
                </td>
                <td>
                    <span class="tenant-info">{{ number_format($contract->total_paid, 0, '.', ',') }} đ</span>
                </td>
                <td class="text-center">
                    @if ($contract->delete_flg)
                        <span class="status-badge status-deleted">
                            <i class="fa-solid fa-trash me-1"></i>
                            {{ __('tenant_deleted') }}
                        </span>
                    @else
                        @switch($contract->status)
                            @case(1)
                                <span class="status-badge status-active toggle-status" data-id="{{ $contract->id }}"
                                    data-status="1" style="cursor: pointer;">
                                    <i class="fas fa-check-circle me-1"></i>
                                    {{ __('paid') }}
                                </span>
                            @break

                            @case(2)
                                <span class="status-badge status-overdue toggle-status" data-id="{{ $contract->id }}"
                                    data-status="2" style="cursor: pointer;">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ __('overdue') }}
                                </span>
                            @break

                            @case(3)
                                <span class="status-badge status-unpaid toggle-status" data-id="{{ $contract->id }}"
                                    data-status="3" style="cursor: pointer;">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ __('unpaid') }}
                                </span>
                            @break

                            @default
                                <span class="status-badge status-unknown">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ __('expired') }}
                                </span>
                        @endswitch
                    @endif
                </td>
                <td class="action-cell text-center">
                    <div class="action-buttons">
                        <a href="{{ route('contracts.show', $contract->id) }}" class="btn-action btn-action-view"
                            title="{{ __('view') }} {{ __('detail') }}">
                            <i class="fas fa-info-circle"></i>
                        </a>
                        @if (!$contract->delete_flg && in_array($contract->status, [1]))
                            <form method="POST" action="{{ route('contracts.destroy', $contract->id) }}"
                                class="d-inline" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="button" class="btn-action btn-action-delete"
                                    data-id="{{ $contract->id }}" title="{{ __('delete') }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center">
                        <div class="empty-state">
                            <i class="fas fa-database fa-3x text-muted mb-3"></i>
                            <p class="text-muted">{{ __('no_data') }}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
