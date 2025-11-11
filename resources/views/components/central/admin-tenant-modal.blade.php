<div class="modal fade text-left" id="{{ $id }}" tabindex="-1" role="dialog"
    aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="{{ $id }}Label">{{ $title }}</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>

            <form id="{{ $formId }}" action="{{ $actionUrl }}" method="POST" data-parsley-validate>
                @csrf
                @if ($isEditing)
                    @method('PUT')
                @endif

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="{{ $formId }}-fullname">Họ và tên: </label>
                                <input id="{{ $formId }}-fullname" name="display_name" type="text"
                                    placeholder="Nhập họ và tên" class="form-control" data-parsley-required="true"
                                    data-parsley-minlength="3" data-parsley-maxlength="50"
                                    data-parsley-unicode-pattern="^[\p{L}\s]+$"
                                    data-parsley-required-message="Vui lòng nhập họ và tên."
                                    data-parsley-minlength-message="Họ và tên phải có ít nhất 3 ký tự."
                                    data-parsley-maxlength-message="Họ và tên không được vượt quá 50 ký tự."
                                    data-parsley-unicode-pattern-message="Họ và tên chỉ được chứa chữ cái và khoảng trắng."
                                    value="{{ old('display_name', $isEditing ? $Data?->display_name : '') }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="{{ $formId }}-username">Tên đăng nhập: </label>
                                <input id="{{ $formId }}-username" name="username" type="text"
                                    placeholder="Nhập tên đăng nhập" class="form-control" data-parsley-required="true"
                                    data-parsley-minlength="3" data-parsley-maxlength="20"
                                    data-parsley-pattern="^[a-zA-Z0-9_]+$"
                                    data-parsley-required-message="Vui lòng nhập tên đăng nhập."
                                    data-parsley-minlength-message="Tên đăng nhập phải có ít nhất 3 ký tự."
                                    data-parsley-maxlength-message="Tên đăng nhập không được vượt quá 20 ký tự."
                                    data-parsley-pattern-message="Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới."
                                    value="{{ old('username', $isEditing ? $Data?->username : '') }}"
                                    {{ $isEditing ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="{{ $formId }}-password">Mật khẩu:
                                    @if ($isEditing)
                                        <small class="text-muted">(Để trống nếu không đổi)</small>
                                    @endif
                                </label>
                                <input id="{{ $formId }}-password" name="password" type="password"
                                    placeholder="Nhập mật khẩu" class="form-control" data-parsley-minlength="8"
                                    data-parsley-maxlength="255"
                                    data-parsley-pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$"
                                    data-parsley-required="{{ $isEditing ? 'false' : 'true' }}"
                                    data-parsley-required-message="Vui lòng nhập mật khẩu."
                                    data-parsley-minlength-message="Mật khẩu phải có ít nhất 8 ký tự."
                                    data-parsley-maxlength-message="Mật khẩu không được vượt quá 255 ký tự."
                                    data-parsley-pattern-message="Mật khẩu phải chứa ít nhất một chữ cái viết hoa, một chữ cái viết thường và một chữ số.">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="{{ $formId }}-password-confirmation">Xác nhận mật khẩu:
                                </label>
                                <input id="{{ $formId }}-password-confirmation" name="password_confirmation"
                                    type="password" placeholder="Nhập lại mật khẩu" class="form-control"
                                    data-parsley-equalto="#{{ $formId }}-password"
                                    data-parsley-equalto-message="Mật khẩu xác nhận không khớp."
                                    data-parsley-validate-if-empty="true"
                                    @if (!$isEditing) data-parsley-required="true"
                                        data-parsley-required-message="Vui lòng nhập xác nhận mật khẩu."
                                    @else
                                        data-parsley-required-if="#{{ $formId }}-password"
                                        data-parsley-required-if-message="Vui lòng nhập xác nhận mật khẩu khi đã nhập mật khẩu mới." @endif>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="{{ $formId }}-province">Tỉnh/Thành phố: </label>
                                <select id="{{ $formId }}-province" name="province_code"
                                    class="choices-select form-select">
                                    <option value="">-- Chọn Tỉnh/Thành phố --</option>
                                    @if (isset($administrativeUnits) && !empty($administrativeUnits))
                                        @foreach ($administrativeUnits as $province)
                                            <option value="{{ $province['province_code'] }}"
                                                {{ old('province_code', $isEditing ? $Data?->province_code : '') == $province['province_code'] ? 'selected' : '' }}>
                                                {{ $province['name'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="{{ $formId }}-ward">Phường/Xã: </label>
                                <select id="{{ $formId }}-ward" name="ward_code"
                                    class="choices-select form-select">
                                    <option value="">-- Vui lòng chọn Tỉnh/Thành phố trước --
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="{{ $formId }}-street-address">Số nhà, tên đường:
                                </label>
                                <input id="{{ $formId }}-street-address" name="street_address" type="text"
                                    placeholder="Nhập số nhà, tên đường" class="form-control"
                                    data-parsley-maxlength="100"
                                    data-parsley-maxlength-message="Số nhà, tên đường không được vượt quá 100 ký tự.">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="{{ $formId }}-birthday">Ngày sinh: </label>
                                <input id="{{ $formId }}-birthday" name="birthday" type="date"
                                    class="form-control" data-parsley-type="date"
                                    data-parsley-type-message="Ngày sinh không hợp lệ."
                                    value="{{ old('birthday', $isEditing ? $Data?->date_of_birth : '') }}">
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="{{ $formId }}-email">Email: </label>
                                <input id="{{ $formId }}-email" name="email" type="text"
                                    placeholder="Nhập email" class="form-control" data-parsley-required="true"
                                    data-parsley-type="email" data-parsley-required-message="Vui lòng nhập email."
                                    data-parsley-type-message="Email không hợp lệ."
                                    value="{{ old('email', $isEditing ? $Data?->email : '') }}"
                                    {{ $isEditing ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="{{ $formId }}-phone">Số điện thoại: </label>
                                <input id="{{ $formId }}-phone" name="phone" type="text"
                                    placeholder="Nhập số điện thoại" class="form-control"
                                    data-parsley-required="true" data-parsley-minlength="10"
                                    data-parsley-maxlength="11" data-parsley-pattern="^0\d{9,10}$"
                                    data-parsley-required-message="Vui lòng nhập số điện thoại."
                                    data-parsley-type-message="Số điện thoại không hợp lệ."
                                    data-parsley-minlength-message="Số điện thoại phải có ít nhất 10 chữ số."
                                    data-parsley-maxlength-message="Số điện thoại không được vượt quá 11 chữ số."
                                    data-parsley-pattern-message="Số điện thoại phải bắt đầu bằng số 0 và chỉ chứa chữ số."
                                    value="{{ old('phone', $isEditing ? $Data?->phone_number : '') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="bx bx-x d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">Đóng</span>
                    </button>
                    <button type="submit" class="btn btn-primary ms-1">
                        <i class="bx bx-check d-block d-sm-none"></i>
                        <span class="d-none d-sm-block">{{ $isEditing ? 'Lưu Thay Đổi' : 'Lưu' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
