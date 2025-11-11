document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('loginForm');
    const usernameInput = document.getElementById('usernameInput');
    const passwordInput = document.getElementById('passwordInput');
    const loginError = document.getElementById('loginError');
    const passwordError = document.getElementById('passwordError');
    const submitBtn = document.getElementById('submitBtn');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const btnText = document.getElementById('btnText');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        if (type === 'text') {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    });

    function validateLogin(value) {
        if (!value || value.trim() === '') {
            return 'Vui lòng nhập tên tài khoản';
        }
        return '';
    }

    function validatePassword(value) {
        if (!value || value.trim() === '') {
            return 'Vui lòng nhập mật khẩu';
        }
        return '';
    }

    // Hiển thị lỗi
    function showError(element, message) {
        element.textContent = message;
        element.style.display = message ? 'block' : 'none';
    }

    function setInputState(input, isValid) {
        input.classList.remove('is-valid', 'is-invalid');
        if (isValid === true) {
            input.classList.add('is-valid');
        } else if (isValid === false) {
            input.classList.add('is-invalid');
        }
    }

    usernameInput.addEventListener('input', function () {
        const error = validateLogin(this.value);
        showError(loginError, error);
        setInputState(this, error === '' ? true : false);
    });

    passwordInput.addEventListener('input', function () {
        const error = validatePassword(this.value);
        showError(passwordError, error);
        setInputState(this, error === '' ? true : false);
    });

    usernameInput.addEventListener('blur', function () {
        const error = validateLogin(this.value);
        showError(loginError, error);
        setInputState(this, error === '' ? true : false);
    });

    passwordInput.addEventListener('blur', function () {
        const error = validatePassword(this.value);
        showError(passwordError, error);
        setInputState(this, error === '' ? true : false);
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const usernameValue = usernameInput.value;
        const passwordValue = passwordInput.value;

        const loginErrorMsg = validateLogin(usernameValue);
        const passwordErrorMsg = validatePassword(passwordValue);


        showError(loginError, loginErrorMsg);
        showError(passwordError, passwordErrorMsg);


        setInputState(usernameInput, loginErrorMsg === '');
        setInputState(passwordInput, passwordErrorMsg === '');


        if (loginErrorMsg || passwordErrorMsg) {
            return;
        }

        submitBtn.disabled = true;
        loadingSpinner.style.display = 'inline-block';
        btnText.textContent = 'Đang xử lý...';

        form.submit();
    });

    usernameInput.addEventListener('focus', function () {
        if (this.classList.contains('is-invalid')) {
            this.classList.remove('is-invalid');
            showError(loginError, '');
        }
    });

    passwordInput.addEventListener('focus', function () {
        if (this.classList.contains('is-invalid')) {
            this.classList.remove('is-invalid');
            showError(passwordError, '');
        }
    });
});
