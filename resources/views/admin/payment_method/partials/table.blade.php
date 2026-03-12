<table class="table table-bordered align-middle modern-table">
    <thead>
        <tr>
            <th class="text-center">{{ __('no') }}</th>
            <th>{{ __('payment_method_name') }}</th>
            <th class="text-center">{{ __('action') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($paymentMethods as $index => $paymentMethod)
            <tr class="table-row-hover">
                <td class="text-center">
                    <span class="group-info">{{ $paymentMethods->firstItem() + $index }}</span>
                </td>
                <td>
                    <span class="group-info">{{ $paymentMethod->name }}</span>
                </td>
                <td class="text-center action-cell">
                    <div class="action-buttons">
                        <a href="{{ route('payment-methods.edit', $paymentMethod->id) }}" class="btn-action btn-action-edit"
                            title="{{ __('edit') }}">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('payment-methods.destroy', $paymentMethod->id) }}" class="d-inline"
                            style="margin:0;">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-action btn-action-delete" data-id="{{ $paymentMethod->id }}"
                                data-name="{{ $paymentMethod->name }}" title="{{ __('delete') }}">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-database fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-0">{{ __('no_data') }}</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
