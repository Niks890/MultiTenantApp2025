@if ($tax->is_active)
    <button type="button" class="toggle-status status-badge status-active border-0"
        data-route="{{ route('taxes.update_status', ['tax' => $tax->id]) }}" data-status="{{ $tax->is_active }}"
        title="{{ __('press_item', ['item' => strtolower(__('turnOff'))]) }}">
        <i class="fas fa-check-circle me-1"></i>
        {{ __('active') }}
    </button>
@else
    <button type="button" class="toggle-status status-badge status-inactive border-0"
        data-route="{{ route('taxes.update_status', ['tax' => $tax->id]) }}" data-status="{{ $tax->is_active }}"
        title="{{ __('press_item', ['item' => strtolower(__('turnOn'))]) }}">
        <i class="fas fa-ban me-1"></i>
        {{ __('inactive') }}
    </button>
@endif
