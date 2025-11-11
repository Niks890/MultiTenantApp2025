    {{-- Modal thêm người dùng hệ thống --}}
    <div class="modal fade text-left" id="inlineForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel33">
                        {{ __('create_item', ['item' => strtolower(__('system_user'))]) }}
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('system-user.store') }}" method="POST" id="createUserForm"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-4 text-center">
                            <label class="form-label d-block fw-bold mb-2">
                                {{ __('upload_item', ['item' => strtolower(__('avatar'))]) }}:
                            </label>
                            <div class="position-relative d-inline-block">
                                <img id="avatarPreview" data-default-avatar="{{ asset('assets/images/avatars/default_avatar.png') }}" src="{{ asset('assets/images/avatars/default_avatar.png') }}"
                                    alt="Preview" class="rounded-circle border border-2 border-secondary"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                                <label for="avatar_url"
                                    class="btn btn-sm btn-outline-primary position-absolute bottom-0 end-0 translate-middle rounded-circle"
                                    style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-camera"></i>
                                </label>
                            </div>
                            <input type="file" name="avatar_url" id="avatar_url" accept="image/*" class="d-none">
                            <div class="invalid-feedback d-block" id="avatar_url_error"></div>
                        </div>


                        <div class="form-group mb-3">
                            <label for="display_name">{{ __('full_name') }}: <span class="text-danger">*</span></label>
                            <input type="text" name="display_name"
                                placeholder="{{ __('enter', ['item' => strtolower(__('full_name'))]) }}"
                                class="form-control" value="{{ old('display_name') }}" required>
                            <div class="invalid-feedback" id="display_name_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email">{{ __('email') }}: <span class="text-danger">*</span></label>
                            <input type="email" name="email"
                                placeholder="{{ __('enter', ['item' => strtolower(__('email'))]) }}"
                                class="form-control" value="{{ old('email') }}" required>
                            <div class="invalid-feedback" id="email_error"></div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="username">{{ __('account_name') }}: <span class="text-danger">*</span></label>
                            <input type="text" name="username"
                                placeholder="{{ __('enter', ['item' => strtolower(__('account_name'))]) }}"
                                class="form-control" value="{{ old('username') }}" required>
                            <div class="invalid-feedback" id="username_error"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">{{ __('password') }}: <span class="text-danger">*</span></label>
                            <div class="password-wrapper position-relative">
                                <input type="password" name="password" id="password"
                                    placeholder="{{ __('enter', ['item' => strtolower(__('password'))]) }}"
                                    class="form-control password-input" required>
                                <span class="toggle-password" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            <div class="invalid-feedback d-block" id="password_error"></div>
                        </div>
                        <div class="form-group mb-3">
                            <label for="confirm_password">{{ __('password_confirmation') }}: <span
                                    class="text-danger">*</span></label>
                            <div class="password-wrapper position-relative">
                                <input type="password" name="confirm_password" id="confirm_password"
                                    placeholder="{{ __('enter', ['item' => strtolower(__('password_confirmation'))]) }}"
                                    class="form-control password-input" required>
                                <span class="toggle-password" data-target="confirm_password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                            <div class="invalid-feedback d-block" id="confirm_password_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end">
                        <button type="button" class="btn btn-secondary-custom lh-lg" data-bs-dismiss="modal">
                            <i class="fas fa-window-close me-2"></i>
                            <span>{{ __('close') }}</span>
                        </button>

                        <button type="submit" class="btn btn-primary lh-lg" id="submitBtn">
                            <i class="fas fa-save me-2"></i>
                            <span>{{ __('save') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
