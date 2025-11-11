<table class="table table-bordered align-middle modern-table">
    <thead>
        <tr>
            <th class="text-center">{{ __('no') }}</th>
            <th>{{ __('group_tenant_name') }}</th>
            <th>{{ __('description') }}</th>
            <th class="text-center">{{ __('action') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($groups as $index => $group)
            <tr class="table-row-hover">
                <td class="text-center">
                    <span class="group-info">{{ $groups->firstItem() + $index }}</span>
                </td>
                <td>
                    <span class="group-info">{{ $group->name }}</span>
                </td>
                <td>
                    <span class="group-info group-description">{{ $group->description }}</span>
                </td>
                <td class="text-center action-cell">
                    <div class="action-buttons">
                        <a href="{{ route('group.edit', $group->id) }}" class="btn-action btn-action-edit"
                            title="{{ __('edit') }}">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('group.destroy', $group->id) }}" class="d-inline"
                            style="margin:0;">
                            @csrf @method('DELETE')
                            <button type="button" class="btn-action btn-action-delete" data-id="{{ $group->id }}"
                                data-name="{{ $group->name }}" title="{{ __('delete') }}">
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
