@if ($plan->is_active)
    <button type="button" class="toggle-status status-badge status-active border-0"
        data-route="{{ route('plans.update_status', ['plan' => $plan->id]) }}" data-status="1"
        data-name="{{ $plan->name }}" title="{{ __('press_item', ['item' => strtolower(__('turnOff'))]) }}">
        <i class="fas fa-check-circle me-1"></i>
        {{ __('active') }}
    </button>
@else
    <button type="button" class="toggle-status status-badge status-inactive border-0"
        data-route="{{ route('plans.update_status', ['plan' => $plan->id]) }}" data-status="0"
        data-name="{{ $plan->name }}" title="{{ __('press_item', ['item' => strtolower(__('turnOn'))]) }}">
        <i class="fas fa-ban me-1"></i>
        {{ __('inactive') }}
    </button>
@endif
