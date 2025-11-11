function openEditModal(userId) {
    $('#editSubmitBtn').prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Đang tải...');
    resetPasswordFields('#edit_password', '#edit_confirm_password');

    $.ajax({
        url: `/admin/system-user/${userId}/edit`,
        type: 'GET',
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
        .done(function (data) {
            $('#edit_display_name').val(data.display_name || '');
            $('#edit_email').val(data.email || '');
            $('#edit_username').val(data.username || '');
            $('#edit_is_active').val(data.is_active ? '1' : '0');
            const preview = $('#editAvatarPreview');
            if (data.avatar_url) {
                preview.attr('src', `/storage/${data.avatar_url}`);
            } else {
                preview.attr('src', 'assets/images/avatars/default_avatar.png');
            }
            // bien currentUserIsSuperAdmin va currentUserId truyen tu blade sang
            const shouldHide = data.id === currentUserId || data.is_super_admin;
            $('#edit_is_active_group').toggle(!shouldHide);

            $('#editUserForm').attr('action', `/admin/system-user/${userId}`);
            resetEditValidation();
            $('#editModal').modal('show');
        })
        .fail(function (xhr, status, error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải thông tin người dùng');
        })
        .always(function () {
            $('#editSubmitBtn').prop('disabled', false).html(
                `<i class="fas fa-save me-2"></i><span>${updateBtnLabel}</span>`
            );
        });
}

function resetEditValidation() {
    $('#editUserForm .invalid-feedback').empty();
    $('#editUserForm .form-control, #editUserForm .form-select').removeClass('is-invalid').removeClass('is-valid');
}

function resetCreateValidation() {
    $('#createUserForm .form-control, #createUserForm .form-select').removeClass('is-invalid').removeClass('is-valid');
    $('#createUserForm .invalid-feedback').empty();
    const preview = $('#avatarPreview');
    preview.attr('src', 'assets/images/avatars/default_avatar.png');
}


$('#editUserForm').on('submit', function (e) {
    e.preventDefault();
    const form = $(this);
    const url = form.attr('action');
    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    const croppedAvatar = $('#edit_cropped_avatar').val();
    if (croppedAvatar && croppedAvatar.length > 0) {
        formData.set('edit_cropped_avatar', croppedAvatar);
    }

    $('#editSubmitBtn').prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Đang xử lý...');
    spinnerControl.show();
    resetEditValidation();

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
        .done(function (data) {
            if (data.success) {
                $('#editModal').modal('hide');
                showToast('success', data.message);
                if (data.updated_user_id === currentUserId && data.password_changed) {
                    window.location.reload();
                } else {
                    window.fetchUsers(window.currentPage || 1);
                }
            } else {
                if (data.errors) {
                    showEditValidationErrors(data.errors);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: data.message || 'Có lỗi xảy ra khi cập nhật người dùng'
                    });
                }
            }
        })
        .fail(function (xhr, status, error) {
            try {
                const data = xhr.responseJSON;
                if (data.errors) {
                    showEditValidationErrors(data.errors);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: data.message || 'Có lỗi xảy ra khi cập nhật người dùng'
                    });
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi kết nối',
                    text: 'Không thể kết nối tới máy chủ'
                });
            }
        })
        .always(function () {
            $('#editSubmitBtn').prop('disabled', false).html(
                `<i class="fas fa-save me-2"></i><span>${updateBtnLabel}</span>`
            );
            spinnerControl.hide();
        });
});

function showEditValidationErrors(errors) {
    for (const [field, messages] of Object.entries(errors)) {
        const input = $(`#edit_${field}`);
        const errorDiv = $(`#edit_${field}_error`);

        if (input.length) {
            input.addClass('is-invalid');
            errorDiv.text(messages[0]);
        }
    }
}

$('#editModal').on('hidden.bs.modal', function () {
    resetEditValidation();
    resetPasswordFields('#edit_password', '#edit_confirm_password');
});


$('#createUserForm').on('submit', function (e) {
    e.preventDefault();
    const form = $(this);
    const formData = new FormData(this);
    const url = form.attr('action');

    $('#submitBtn').prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Đang xử lý...');
    spinnerControl.show();

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
        .done(function (data) {
            if (data.success) {
                $('#inlineForm').modal('hide');
                window.fetchUsers(window.currentPage || 1);
                showToast('success', data.message);
            } else if (data.errors) {
                showCreateValidationErrors(data.errors);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: data.message || 'Có lỗi xảy ra khi thêm người dùng'
                });
            }
        })
        .fail(function (xhr, status, error) {
            try {
                const data = xhr.responseJSON;
                if (data.errors) {
                    showCreateValidationErrors(data.errors);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: data.message || 'Có lỗi xảy ra khi thêm người dùng'
                    });
                }
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi kết nối',
                    text: 'Không thể kết nối tới máy chủ'
                });
            }
        })
        .always(function () {
            $('#submitBtn').prop('disabled', false).html(`<i class="fas fa-save"></i>${updateBtnLabel}`);
            spinnerControl.hide();
        });
});

function showCreateValidationErrors(errors) {
    for (const [field, messages] of Object.entries(errors)) {
        $(`[name="${field}"]`).addClass('is-invalid');
        $(`#${field}_error`).text(messages[0]);
    }
}

$('#inlineForm').on('hidden.bs.modal', function () {
    resetCreateValidation();
    $('#createUserForm')[0].reset();
    resetPasswordFields('#password', '#confirm_password');
});

$('#inlineForm').on('show.bs.modal', function () {
    resetCreateValidation();
    $('#createUserForm')[0].reset();
    resetPasswordFields('#password', '#confirm_password');
});

function resetPasswordFields(passwordSelector, confirmSelector) {
    const passwordInput = $(passwordSelector);
    const confirmInput = $(confirmSelector);
    const passwordToggle = $(`.toggle-password[data-target="${passwordSelector.replace('#', '')}"]`);
    const confirmToggle = $(`.toggle-password[data-target="${confirmSelector.replace('#', '')}"]`);
    passwordInput.val('');
    confirmInput.val('');
    passwordInput.removeClass('is-invalid is-valid');
    confirmInput.removeClass('is-invalid is-valid');
    $(`${passwordSelector}_error`).text('');
    $(`${confirmSelector}_error`).text('');
    confirmInput.prop('disabled', true).attr('placeholder', 'Nhập mật khẩu xác nhận');
    if (confirmToggle.length) {
        confirmToggle.css({
            'pointer-events': 'none',
            'opacity': '0.5'
        });
    }
    passwordInput.attr('type', 'password');
    confirmInput.attr('type', 'password');
    if (passwordToggle.length) {
        passwordToggle.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
    }
    if (confirmToggle.length) {
        confirmToggle.find('i').removeClass('fa-eye-slash').addClass('fa-eye');
    }
}
