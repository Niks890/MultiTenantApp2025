{{-- resources/views/admin/contracts/partials/payment-table.blade.php --}}
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
            <tr>
                <td>{{ $transactions->firstItem() + $index }}</td>
                <td>{{ number_format($transaction->amount, 0, ',', '.') }} đ</td>
                <td>{{ $transaction->paymentMethod->name ?? '—' }}</td>
                <td>{{ $transaction->payment_date->format('d/m/Y') ?? '—' }}</td>
                <td>
                    @if ($transaction->file_path)
                        <a href="{{ Storage::url($transaction->file_path) }}" target="_blank" class="text-primary">
                            {{ __('Xem ảnh') }}
                        </a>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('transaction.edit', [$transaction->contract_id, $transaction->id]) }}"
                        class="btn btn-sm btn-outline-primary me-1">
                        {{ __('Sửa') }}
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete-payment"
                        data-id="{{ $transaction->id }}" data-contract-id="{{ $transaction->contract_id }}"
                        data-url="{{ route('transaction.destroy', [$transaction->contract_id, $transaction->id]) }}">
                        {{ __('Xoá') }}
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    {{ __('Chưa có lần thanh toán nào.') }}
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
