    {{-- Modal sửa người dùng hệ thống --}}
    <div class="modal fade text-left" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editModalLabel">
                        {{ __('edit_item', ['item' => strtolower(__('system_user'))]) }}
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="editUserForm" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group mb-4 text-center">
                            <label class="form-label d-block fw-bold mb-2">
                                {{ __('change_item', ['item' => strtolower(__('avatar'))]) }}:
                            </label>
                            <div class="position-relative d-inline-block">
                                <img id="editAvatarPreview"
                                    data-default-avatar="{{ asset('assets/images/avatars/default_avatar.png') }}" src="{{ asset('assets/images/avatars/default_avatar.png') }}" alt="Preview"
                                    class="rounded-circle border border-2 border-secondary"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                                <label for="edit_avatar_url"
                                    class="btn btn-sm btn-outline-primary position-absolute bottom-0 end-0 translate-middle rounded-circle"
                                    style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-camera"></i>
                                </label>
                            </div>
                            <input type="file" name="avatar_url" id="edit_avatar_url" accept="image/*"
                                class="d-none">
                            <div class="invalid-feedback d-block" id="edit_avatar_url_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_display_name">{{ __('full_name') }}: <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="display_name" id="edit_display_name"
                                placeholder="{{ __('enter', ['item' => strtolower(__('full_name'))]) }}"
                                class="form-control" required>
                            <div class="invalid-feedback" id="edit_display_name_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_email">{{ __('email') }}: <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="edit_email"
                                placeholder="{{ __('enter', ['item' => strtolower(__('email'))]) }}"
                                class="form-control" required>
                            <div class="invalid-feedback" id="edit_email_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_username">{{ __('account_name') }}: <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="username" id="edit_username"
                                placeholder="{{ __('enter', ['item' => strtolower(__('account_name'))]) }}"
                                class="form-control" required autocomplete="off">
                            <div class="invalid-feedback" id="edit_username_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_password">{{ __('password') }}:</label>
                            <div class="password-wrapper position-relative">
                                <input type="password" name="password" id="edit_password"
                                    class="form-control password-input" autocomplete="off">
                                <span class="toggle-password" data-target="edit_password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            <small class="form-text text-muted">Chỉ nhập nếu muốn thay đổi mật khẩu</small>
                            <div class="invalid-feedback d-block" id="edit_password_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="edit_confirm_password">{{ __('password_confirmation') }}:</label>
                            <div class="password-wrapper position-relative">
                                <input type="password" name="confirm_password" id="edit_confirm_password"
                                    placeholder="{{ __('enter', ['item' => strtolower(__('password_confirmation'))]) }}"
                                    class="form-control password-input" required>
                                <span class="toggle-password" data-target="edit_confirm_password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            <div class="invalid-feedback d-block" id="edit_confirm_password_error"></div>
                        </div>
                        <div class="form-group mb-3" id="edit_is_active_group">
                            <label for="edit_is_active">
                                {{ __('status_item', ['item' => strtolower(__('account'))]) }}:
                                <span class="text-danger">*</span>
                            </label>
                            <select name="is_active" id="edit_is_active" class="form-select" required>
                                <option value="1">{{ __('active') }}</option>
                                <option value="0">{{ __('inactive') }}</option>
                            </select>
                            <div class="invalid-feedback" id="edit_is_active_error"></div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-secondary-custom lh-lg" data-bs-dismiss="modal">
                            <i class="fas fa-window-close me-2"></i>
                            <span>{{ __('close') }}</span>
                        </button>

                        <button type="submit" class="btn btn-primary lh-lg" id="editSubmitBtn">
                            <i class="fas fa-save me-2"></i>
                            <span>{{ __('save') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
