<table class="table table-hover align-middle mb-0">
    <thead class="table-light">
        <tr>
            <th style="width: 50px;">{{ __('STT') }}</th>
            <th>{{ __('Số tiền thanh toán') }}</th>
            <th>{{ __('Phương thức thanh toán') }}</th>
            <th>{{ __('Ngày thanh toán') }}</th>
            <th>{{ __('Ảnh hoá đơn giao dịch') }}</th>
            <th style="width: 120px;">{{ __('Hành động') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($transactions as $index => $transaction)
            <tr class="{{ $transaction->delete_flg == 1 ? 'text-muted bg-light' : '' }}">
                <td>{{ $transactions->firstItem() + $index }}</td>
                <td>
                    {{ number_format($transaction->amount, 0, ',', '.') }} đ
                    @if ($transaction->delete_flg == 1)
                        <span class="badge bg-secondary ms-1 small">{{ __('Đã bị xóa') }}</span>
                    @endif
                </td>
                <td>{{ $transaction->paymentMethod->name ?? '—' }}</td>
                <td>{{ $transaction->payment_date ? $transaction->payment_date->format('d/m/Y') : '—' }}</td>
                <td>
                    @if ($transaction->file_path)
                        <a href="{{ Storage::url($transaction->file_path) }}" target="_blank"
                           class="{{ $transaction->delete_flg == 1 ? 'text-secondary' : 'text-primary' }}">
                            {{ __('Xem ảnh') }}
                        </a>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @if ($transaction->delete_flg == 0)
                        <a href="javascript:void(0)" class="btn-action btn-action-edit me-1 btn-edit-transaction"
                            data-id="{{ $transaction->id }}"
                            data-amount="{{ (int)$transaction->amount }}"
                            data-paid_at="{{ $transaction->payment_date?->format('Y-m-d') }}"
                            data-payment_method_id="{{ $transaction->payment_method_id }}"
                            data-file="{{ $transaction->file_path ? Storage::url($transaction->file_path) : '' }}">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form method="POST" action="{{ route('transaction.destroy', $transaction->id) }}"
                            class="d-inline delete-transaction-form" style="margin:0;">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-action btn-action-delete"
                                data-id="{{ $transaction->id }}"
                                title="{{ __('Xóa') }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    @else
                        <span class="text-muted small"><i class="fas fa-ban"></i> No Action</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    {{ __('no_data') }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
