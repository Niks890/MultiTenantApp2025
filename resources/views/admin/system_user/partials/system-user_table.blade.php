<table class="table table-bordered align-middle modern-table">
    <thead class="">
        <tr>
            <th style="width: 80px; min-height: 80px;"></th>
            <th>{{ __('account_name') }}</th>
            <th>{{ __('full_name') }}</th>
            <th>{{ __('email') }}</th>
            <th>{{ __('last_login') }}</th>
            <th class="text-center">{{ __('status_item', ['item' => strtolower(__('account'))]) }}</th>
            <th class="text-center">{{ __('action') }}</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($systemUsers as $user)
            <tr class="table-row-hover">
                <td>
                    <div class="user-avatar">
                        <img class="rounded-circle"
                            src="{{ $user->avatar_url ? asset('storage/' . $user->avatar_url) : asset('assets/images/avatars/default_avatar.png') }}"
                            alt="avatar" style="width: 50px; height: 50px;">
                    </div>
                </td>
                <td>
                    <div class="user-info">
                        <span class="user-name" title="{{ $user->username }}">{{ $user->username }}</span>
                    </div>
                </td>
                <td>
                    <div class="user-info">
                        <span class="user-name" title="{{ $user->display_name }}">{{ $user->display_name }}</span>
                    </div>
                </td>
                <td>
                    <span title="{{ $user->email }}" class="text-dark">
                        {{ $user->email }}
                    </span>
                </td>
                <td>
                    <span class="text-dark">
                        {{ $user->last_login_date ?? '' }}
                    </span>
                </td>
                <td class="text-center">
                    @if ($user->is_active)
                        <span class="status-badge status-active">
                            <i class="fas fa-check-circle me-1"></i>
                            {{ __('active') }}
                        </span>
                    @else
                        <span class="status-badge status-inactive">
                            <i class="fas fa-ban me-1"></i>
                            {{ __('inactive') }}
                        </span>
                    @endif
                </td>
                <td class="text-center action-cell">
                    <div class="action-buttons">
                        <a href="javascript:void(0);" class="btn-action btn-action-edit" data-bs-toggle="tooltip"
                            title="{{ __('edit') }}" onclick="openEditModal({{ $user->id }})">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if ($user->id !== auth()->id() && !$user->is_super_admin)
                            <form method="POST" action="{{ route('system-user.destroy', $user->id) }}"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-action btn-action-delete"
                                    data-id="{{ $user->id }}" data-name="{{ $user->display_name }}"
                                    title="{{ __('delete') }}">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                </td>

            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center">
                    <div class="empty-state">
                        <i class="fas fa-database fa-3x text-muted mb-3"></i>
                        <p class="text-muted">{{ __('no_data') }}</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
