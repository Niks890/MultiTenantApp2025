<table class="table table-bordered modern-table mb-3">
    <thead>
        <tr>
            <th class="text-center">{{ __('no') }}</th>
            <th>{{ __('plan_name') }}</th>
            <th>{{ __('plan_price') }}</th>
            <th>{{ __('cycle') }}</th>
            <th>{{ __('description') }}</th>
            <th class="text-center">{{ __('status') }}</th>
            <th class="text-center">{{ __('action') }}</th>
        </tr>
    </thead>
    <tbody id="table-body">
        @forelse ($plans as $index => $plan)
            <tr class="table-row-hover">
                <td class="text-center">{{ $plans->firstItem() + $index }}</td>
                <td>
                    <span>
                        {{ $plan->name }}
                    </span>
                </td>
                <td class="text-end">
                    <span>
                        {{ number_format($plan->price, 0, '.', ',') }}
                    </span>
                </td>
                <td>
                    <span>
                        @if ($plan->cycle == 'weekly')
                            {{ __('week') }}
                        @elseif ($plan->cycle == 'monthly')
                            {{ __('month') }}
                        @elseif ($plan->cycle == 'yearly')
                            {{ __('year') }}
                        @endif
                    </span>
                </td>
                <td style="max-width: 400px;">
                    <span class="pre-wrap-text">{{ $plan->description }}</span>
                </td>
                <td class="td-nowrap text-center">
                    @if ($plan->is_active)
                        <button type="button" class="toggle-status status-badge status-active border-0"
                            data-route="{{ route('plans.update_status', ['plan' => $plan->id]) }}" data-status="1"
                            data-name="{{ $plan->name }}"
                            title="{{ __('press_item', ['item' => strtolower(__('turnOff'))]) }}">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ __('active') }}
                        </button>
                    @else
                        <button type="button" class="toggle-status status-badge status-inactive border-0"
                            data-route="{{ route('plans.update_status', ['plan' => $plan->id]) }}" data-status="0"
                            data-name="{{ $plan->name }}"
                            title="{{ __('press_item', ['item' => strtolower(__('turnOn'))]) }}">
                            <i class="fas fa-ban me-1"></i>
                            {{ __('inactive') }}
                        </button>
                    @endif
                </td>

                <td class="td-nowrap text-center">
                    <a href="{{ route('plans.edit', ['plan' => $plan->id]) }}"
                        class="edit-admin-tenant btn-action btn-action-edit" data-id="{{ $plan->id }}">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="{{ route('plans.show', ['plan' => $plan->id]) }}" class="btn-action btn-action-view"
                        title="{{ __('view') }} {{ __('detail') }}">
                        <i class="fas fa-info-circle"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <i class="fas fa-database fa-3x text-muted"></i>
                    <p class="text-muted mt-2 mb-0">{{ __('no_data') }}</p>
                </td>
            </tr>
        @endforelse

    </tbody>
</table>
