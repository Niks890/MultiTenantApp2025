<table class="table table-bordered modern-table mb-3">
    <thead>
        <tr>
            <th class="text-center">{{ __('no') }}</th>
            <th>{{ __('tax_rate') }}</th>
            <th class="text-center">{{ __('status') }}</th>
            <th class="text-center">{{ __('action') }}</th>
        </tr>
    </thead>
    <tbody id="table-body">
        @forelse ($taxes as $index => $tax)
            <tr class="table-row-hover">
                <td class="text-center" style="width: 10%;">{{ $taxes->firstItem() + $index }}</td>
                <td class="text-end">
                    <span>
                        {{ number_format($tax->rate, 0, '.', ',') }}&percnt;
                    </span>
                </td>
                <td class="td-nowrap text-center" style="width: 15%;">
                    @if ($tax->is_active)
                        <button type="button" class="toggle-status status-badge status-active border-0"
                            data-route="{{ route('taxes.update_status', ['tax' => $tax->id]) }}"
                            data-status="{{ $tax->is_active }}"
                            title="{{ __('press_item', ['item' => strtolower(__('turnOff'))]) }}">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ __('active') }}
                        </button>
                    @else
                        <button type="button" class="toggle-status status-badge status-inactive border-0"
                            data-route="{{ route('taxes.update_status', ['tax' => $tax->id]) }}"
                            data-status="{{ $tax->is_active }}"
                            title="{{ __('press_item', ['item' => strtolower(__('turnOn'))]) }}">
                            <i class="fas fa-ban me-1"></i>
                            {{ __('inactive') }}
                        </button>
                    @endif
                </td>

                <td class="td-nowrap text-center" style="width: 12%;">
                    <a href="{{ route('taxes.edit', ['tax' => $tax->id]) }}"
                        class="edit-admin-tenant btn-action btn-action-edit" data-id="{{ $tax->id }}">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('taxes.show', ['tax' => $tax->id]) }}" class="btn-action btn-action-view"
                        title="{{ __('view') }} {{ __('detail') }}">
                        <i class="fas fa-info-circle"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center py-5">
                    <i class="fas fa-database fa-3x text-muted"></i>
                    <p class="text-muted mt-2 mb-0">{{ __('no_data') }}</p>
                </td>
            </tr>
        @endforelse

    </tbody>
</table>
