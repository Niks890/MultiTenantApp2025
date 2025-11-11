document.addEventListener('DOMContentLoaded', function () {
    const addForm = document.getElementById('createUserForm');
    const editForm = document.getElementById('editUserForm');

    function validateDisplayName(value) {
        if (!value.trim()) return 'Vui lòng nhập họ tên';
        if (value.length < 3) return 'Họ tên phải có ít nhất 3 ký tự';
        if (value.length > 100) return 'Họ tên không được quá 100 ký tự';
        return '';
    }

    function validateEmail(value) {
        if (!value.trim()) return 'Vui lòng nhập email';
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regex.test(value)) return 'Email không hợp lệ';
        return '';
    }

    function validateUsername(value) {
        const trimmed = value.trim();
        if (!trimmed) return 'Vui lòng nhập tên tài khoản';
        if (trimmed.length < 6) return 'Tên tài khoản phải có ít nhất 6 ký tự';
        if (trimmed.length > 100) return 'Tên tài khoản không được quá 100 ký tự';
        if (/\s/.test(trimmed)) return 'Tên tài khoản không được chứa khoảng trắng';
        if (/[À-Ỵà-ỹ]/.test(trimmed)) {
            return 'Tên tài khoản không được chứa ký tự có dấu';
        }
        if (/[^\x00-\x7F]/.test(trimmed)) return 'Tên tài khoản không được chứa biểu tượng';
        return '';
    }


    function validatePassword(value, isEdit = false) {
        const trimmed = value.trim();
        if (isEdit && trimmed === '') return '';
        if (!trimmed) return 'Vui lòng nhập mật khẩu';
        if (trimmed.length < 8) return 'Mật khẩu phải có ít nhất 8 ký tự';
        if (trimmed.length > 100) return 'Mật khẩu không được quá 100 ký tự';
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[^\s]+$/;
        if (!regex.test(trimmed)) {
            return 'Mật khẩu phải bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt, không được chứa khoảng trắng';
        }
        return '';
    }

    function validateConfirmPassword(password, confirmPassword) {
        if (!confirmPassword.trim()) return 'Vui lòng nhập mật khẩu xác nhận';
        if (password.trim() !== confirmPassword.trim()) return 'Mật khẩu xác nhận không đúng';
        return '';
    }

    function validateAvatar(file) {
        if (!file) return '';
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!allowedTypes.includes(file.type)) return 'Ảnh phải thuộc định dạng jpeg, png, jpg, gif hoặc webp!';
        if (file.size > 2 * 1024 * 1024) return 'Ảnh tải lên không đúng định dạng hoặc vượt quá 2MB';
        return '';
    }


    function showError(input, message, fromSubmit = false) {
        const errorDiv = input.closest('.form-group')?.querySelector('.invalid-feedback');
        input.classList.remove('is-valid', 'is-invalid');

        if (fromSubmit) {
            if (message) {
                input.classList.add('is-invalid');
                if (errorDiv) errorDiv.textContent = message;
            } else {
                if (errorDiv) errorDiv.textContent = '';
            }
        } else {
            if (!message && input.value.trim() !== '') {
                input.classList.add('is-valid');
            }
            if (errorDiv) errorDiv.textContent = '';

        }
    }


    function attachRealtimeValidation(form, isEdit = false) {
        const fields = {
            display_name: validateDisplayName,
            email: validateEmail,
            username: validateUsername,
            password: value => validatePassword(value, isEdit),
        };

        Object.entries(fields).forEach(([name, validator]) => {
            const input = form.querySelector(`[name="${name}"]`);
            if (!input) return;

            input.addEventListener('input', () => {
                const error = validator(input.value);
                showError(input, error, false);
                if (name === 'password') {
                    const confirmInput = form.querySelector('[name="confirm_password"]');
                    if (confirmInput && confirmInput.value.trim() !== '') {
                        const confirmError = validateConfirmPassword(input.value.trim(), confirmInput.value.trim());
                        showError(confirmInput, confirmError, false);
                    }
                }
            });

            input.addEventListener('blur', () => {
                const error = validator(input.value);
                showError(input, error, false);
            });

            input.addEventListener('focus', () => {
                input.classList.remove('is-invalid');
                const errorDiv = input.closest('.form-group')?.querySelector('.invalid-feedback');
                if (errorDiv) errorDiv.textContent = '';
            });
        });

        //  Xu ly confirm password
        const passwordInput = form.querySelector('[name="password"]');
        const confirmInput = form.querySelector('[name="confirm_password"]');
        if (passwordInput && confirmInput) {
            confirmInput.addEventListener('input', () => {
                const error = validateConfirmPassword(passwordInput.value.trim(), confirmInput.value.trim());
                showError(confirmInput, error, false);
            });

            confirmInput.addEventListener('blur', () => {
                const error = validateConfirmPassword(passwordInput.value.trim(), confirmInput.value.trim());
                showError(confirmInput, error, false);
            });

            confirmInput.addEventListener('focus', () => {
                confirmInput.classList.remove('is-invalid');
                const errorDiv = confirmInput.closest('.form-group')?.querySelector('.invalid-feedback');
                if (errorDiv) errorDiv.textContent = '';
            });
        }

        // Xu ly avatar preview + validate
        const avatarInput = form.querySelector('[name="avatar_url"]');
        const avatarPreview = document.getElementById('avatarPreview');

        if (avatarInput) {
            avatarInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                const error = validateAvatar(file);
                showError(avatarInput, error, false);

                if (!error && file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        if (avatarPreview) avatarPreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    if (avatarPreview)
                        avatarPreview.src = `assets/images/avatars/default_avatar.png`;
                }
            });
        }

        // Xu ly  avatar preview + validate cho form edit
        const editAvatarInput = document.getElementById('edit_avatar_url');
        const editPreview = document.getElementById('editAvatarPreview');
        if (editAvatarInput && editPreview) {
            editAvatarInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                const error = validateAvatar(file);
                showError(editAvatarInput, error, false);

                if (!error && file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        editPreview.src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                } else {
                    editPreview.src = `assets/images/avatars/default_avatar.png`;
                }
            });
        }

    }

    // submit form
    function handleFormSubmit(form, isEdit = false) {
        form.addEventListener('submit', function (e) {
            let valid = true;
            const displayName = form.querySelector('[name="display_name"]');
            const email = form.querySelector('[name="email"]');
            const username = form.querySelector('[name="username"]');
            const password = form.querySelector('[name="password"]');
            const confirmPassword = form.querySelector('[name="confirm_password"]');
            const avatarInput = form.querySelector('[name="avatar_url"]');

            const errors = {
                display_name: validateDisplayName(displayName.value),
                email: validateEmail(email.value),
                username: validateUsername(username.value),
                password: validatePassword(password.value, isEdit),
                confirm_password: password.value.trim()
                    ? validateConfirmPassword(password.value.trim(), confirmPassword.value.trim())
                    : '',
                avatar_url: validateAvatar(avatarInput.files[0]),
            };
            Object.entries(errors).forEach(([field, msg]) => {
                const input = form.querySelector(`[name="${field}"]`);
                showError(input, msg, true);
                if (msg) valid = false;
            });


            if (!valid) {
                e.preventDefault();
                const firstInvalid = form.querySelector('.is-invalid');
                if (firstInvalid) firstInvalid.focus();
            }
        });
    }


    if (addForm) {
        attachRealtimeValidation(addForm);
        handleFormSubmit(addForm);
        handleConfirmPasswordToggle('password', 'confirm_password', '.toggle-password[data-target="confirm_password"]');
    }

    if (editForm) {
        attachRealtimeValidation(editForm, true);
        handleFormSubmit(editForm, true);
        handleConfirmPasswordToggle('edit_password', 'edit_confirm_password', '.toggle-password[data-target="edit_confirm_password"]');
    }

    function togglePasswordVisibility(toggle) {
        const targetId = toggle.dataset.target;
        const input = document.getElementById(targetId);
        if (!input) return;

        const icon = toggle.querySelector('i');
        const isPassword = input.type === 'password';

        input.type = isPassword ? 'text' : 'password';
        icon.classList.toggle('fa-eye', !isPassword);
        icon.classList.toggle('fa-eye-slash', isPassword);
    }

    document.querySelectorAll('.toggle-password').forEach(toggle => {
        toggle.addEventListener('click', () => togglePasswordVisibility(toggle));
    });

    function handleConfirmPasswordToggle(passwordId, confirmId, toggleSelector) {
        const passwordInput = document.getElementById(passwordId);
        const confirmInput = document.getElementById(confirmId);
        const confirmToggle = document.querySelector(toggleSelector);

        if (!passwordInput || !confirmInput) return;
        confirmInput.disabled = true;
        confirmInput.placeholder = "Vui lòng nhập mật khẩu xác nhận";
        if (confirmToggle) {
            confirmToggle.style.pointerEvents = 'none';
            confirmToggle.style.opacity = '0.5';
        }

        passwordInput.addEventListener('input', () => {
            const isEmpty = passwordInput.value.trim() === '';
            confirmInput.disabled = isEmpty;
            confirmInput.placeholder = isEmpty
                ? "Vui lòng nhập mật khẩu xác nhận"
                : "Nhập mật khẩu xác nhận";

            if (isEmpty) {
                confirmInput.value = '';
                confirmInput.classList.remove('is-valid', 'is-invalid');
                const errorDiv = confirmInput.closest('.form-group')?.querySelector('.invalid-feedback');
                if (errorDiv) errorDiv.textContent = '';
            }

            if (confirmToggle) {
                confirmToggle.style.pointerEvents = isEmpty ? 'none' : 'auto';
                confirmToggle.style.opacity = isEmpty ? '0.5' : '1';
            }
        });
    }

});
