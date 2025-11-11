class AdminTenantFormValidator {
    constructor(config = {}) {
        this.config = {
            formId: config.formId || 'admin-tenant-form',
            mode: config.mode || 'create',
            vietnamUnits: config.vietnamUnits || [],
            currentId: config.currentId || null,
            originalData: config.originalData || {},
            redirectUrl: config.redirectUrl || '',
            endpoints: {
                checkUsername: '/api/admin/validation-admin-tenant/check-username',
                checkEmail: '/api/admin/validation-admin-tenant/check-email',
                checkPhone: '/api/admin/validation-admin-tenant/check-phone',
                ...config.endpoints
            }
        };

        this.validationState = {
            isNameValid: this.config.mode === 'edit',
            isUsernameValid: this.config.mode === 'edit',
            isPasswordValid: this.config.mode === 'edit',
            isPasswordMatch: true,
            isEmailValid: this.config.mode === 'edit',
            isPhoneValid: this.config.mode === 'edit',
            isProvinceSelected: true,
            isWardSelected: true,
            isStreetValid: true,
            isBirthdayValid: true
        };

        this.wardChoices = null;
        this.flatpickrInstance = null;
        this.form = null;
        
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.form = document.getElementById(this.config.formId);
            if (!this.form) return;

            this.form.submitted = false;
            this.initializePlugins();
            this.attachEventListeners();
            this.loadSavedSelections();
        });
    }

    // === UTILITY FUNCTIONS ===
    debounce(func, delay) {
        let timeoutId;
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(this, args), delay);
        };
    }

    async callAPI(method, endpoint, data = null) {
        const config = {
            url: endpoint,
            method: method.toUpperCase(),
        };

        if (data) {
            if (data instanceof FormData) {
                config.data = data;
                config.processData = false;
                config.contentType = false;
            }
            
            else {
            const upperMethod = method.toUpperCase();
                if (upperMethod === 'GET' || upperMethod === 'DELETE') {
                    config.data = data;
                } else {
                    config.data = JSON.stringify(data);
                    config.contentType = "application/json; charset=utf-8";
                    config.dataType = "json";
                }
            }
        }

        return $.ajax(config)
            .catch(function(xhr, textStatus, errorThrown) {                
                if (xhr.status > 0) {
                    return xhr.responseJSON || xhr.responseText;
                }

                let errorMessage;
                if (xhr.status === 0) {
                    errorMessage = 'Lỗi mạng: Không nhận được phản hồi từ máy chủ.';
                } else {
                    errorMessage = errorThrown || 'Đã xảy ra lỗi không mong muốn.';
                }
                
                throw new Error(errorMessage);
            });
    }

    getFieldElements(fieldId) {
        return {
            input: document.getElementById(fieldId),
            feedback: document.getElementById(`${fieldId}-validation-feedback`)
        };
    }

    clearValidationState(inputElement, feedbackElement) {
        if (!inputElement || !feedbackElement) return;
        
        inputElement.classList.remove('is-valid', 'is-invalid');
        feedbackElement.innerHTML = '';
        feedbackElement.className = '';

        const existingSpinner = inputElement.parentElement.querySelector('.spinner-border');
        if (existingSpinner) existingSpinner.remove();
    }

    addValidationState(inputElement, feedbackElement, message = '', isValid = true) {
        if (message) feedbackElement.innerHTML = message;

        const inputClass = isValid ? 'is-valid' : 'is-invalid';
        const feedbackClass = isValid ? 'valid-feedback' : 'invalid-feedback';

        inputElement.classList.remove('is-valid', 'is-invalid');
        inputElement.classList.add(inputClass);
        feedbackElement.className = feedbackClass;
    }

    showLoading(feedbackElement, inputElement) {
        const spinner = document.createElement('div');
        spinner.className = 'spinner-border spinner-border-sm text-info me-2';
        spinner.setAttribute('role', 'status');
        spinner.innerHTML = '<span class="visually-hidden">Loading...</span>';
        feedbackElement.innerHTML = 'Đang kiểm tra...';
        feedbackElement.className = 'text-checking text-info d-inline';
        inputElement.parentElement.insertBefore(spinner, feedbackElement);
    }

    // === VALIDATION FUNCTIONS ===
    validateName() {
        const { input, feedback } = this.getFieldElements('name');
        const value = input.value.trim();

        this.clearValidationState(input, feedback);

        if (value.length === 0) {
            // this.addValidationState(input, feedback, 'Vui lòng nhập họ tên', false);
            return false;
        }
        if (value.length < 3) {
            // this.addValidationState(input, feedback, 'Họ tên phải có ít nhất 3 ký tự', false);
            return false;
        }
        if (value.length > 100) {
            // this.addValidationState(input, feedback, 'Họ tên có tối đa 100 ký tự', false);
            return false;
        }

        const regex = /^[a-zA-ZÀ-ỹ\s]+$/u;
        if (!regex.test(value)) {
            // this.addValidationState(input, feedback, 'Họ tên chỉ bao gồm chữ cái và khoảng trắng.', false);
            return false;
        }

        this.addValidationState(input, feedback);
        return true;
    }

    validateUsername() {
        const { input, feedback } = this.getFieldElements('username');
        const value = input.value.trim();

        this.clearValidationState(input, feedback);

        if (value.length === 0) {
            this.clearValidationState(input, feedback);
            // this.addValidationState(input, feedback, 'Vui lòng nhập tên tài khoản', false);
            return false;
        }
        if (value.length < 3) {
            this.clearValidationState(input, feedback);
            // this.addValidationState(input, feedback, 'Tên tài khoản phải có ít nhất 3 ký tự', false);
            return false;
        }
        if (value.length > 100) {
            this.clearValidationState(input, feedback);
            // this.addValidationState(input, feedback, 'Tên tài khoản có tối đa 100 ký tự', false);
            return false;
        }

        this.addValidationState(input, feedback);
        return true;
    }

    validatePassword() {
        const { input, feedback } = this.getFieldElements('password');
        const value = input.value;

        this.clearValidationState(input, feedback);

        if (value.length === 0) {
            if (this.config.mode === 'create') {
                // this.addValidationState(input, feedback, 'Vui lòng nhập mật khẩu.', false);
                return false;
            }
            return true;
        }

        if (value.length < 8) {
            // this.addValidationState(input, feedback, 'Mật khẩu phải có ít nhất 8 ký tự.', false);
            return false;
        }
        if (value.length > 255) {
            // this.addValidationState(input, feedback, 'Mật khẩu có tối đa 255 ký tự.', false);
            return false;
        }

        if (/\s/.test(value)) {
            // this.addValidationState(input, feedback, 'Mật khẩu không được chứa dấu cách.', false);
            return false;
        }

        const hasUpperCase = /[A-Z]/.test(value);
        const hasLowerCase = /[a-z]/.test(value);
        const hasNumber = /[0-9]/.test(value);
        const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(value);

        if (!(hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar)) {
            // this.addValidationState(input, feedback, 'Mật khẩu phải bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.', false);
            return false;
        }

        this.addValidationState(input, feedback);
        return true;
    }

    validatePasswordMatch() {
        const { input: confirmInput, feedback: confirmFeedback } = this.getFieldElements('password-confirmation');
        const passwordValue = document.getElementById('password').value;
        const confirmValue = confirmInput.value;

        this.clearValidationState(confirmInput, confirmFeedback);

        if (!passwordValue) return true;

        if (!confirmValue) {
            // this.addValidationState(confirmInput, confirmFeedback, 'Vui lòng xác nhận mật khẩu.', false);
            return false;
        }

        if (passwordValue !== confirmValue) {
            // this.addValidationState(confirmInput, confirmFeedback, 'Mật khẩu xác nhận không khớp', false);
            return false;
        }

        return true;
    }

    validateEmail() {
        const { input, feedback } = this.getFieldElements('email');
        const value = input.value.trim();

        if (value.length > 254) {
            this.clearValidationState(input, feedback);
            // this.addValidationState(input, feedback, 'Email có tối đa 254 ký tự.', false);
            return false;
        }

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(value)) {
            this.clearValidationState(input, feedback);
            // this.addValidationState(input, feedback, 'Định dạng email không hợp lệ.', false);
            return false;
        }

        this.clearValidationState(input, feedback);
        return true;
    }

    validatePhone() {
        const { input, feedback } = this.getFieldElements('phone');
        const value = input.value.trim();

        if (value.length === 0) {
            this.clearValidationState(input, feedback);
            // this.addValidationState(input, feedback, 'Vui lòng nhập số điện thoại.', false);
            return false;
        }

        const phonePattern = /^\+?[0-9]{10,11}$/;
        if (!phonePattern.test(value)) {
            this.clearValidationState(input, feedback);
            // this.addValidationState(input, feedback, 'Định dạng số điện thoại không hợp lệ.', false);
            return false;
        }

        this.clearValidationState(input, feedback);
        this.addValidationState(input, feedback);
        return true;
    }

    validateProvince() {
        const { input: provinceInput } = this.getFieldElements('province');
        const provinceValue = provinceInput.value;

        if (provinceValue === '') {
            this.wardChoices.clearStore();
            return true;
        }

        const provinceOldValue = localStorage.getItem('selected_province');
        const wardOldValue = localStorage.getItem('selected_ward');
        const selectedProvince = this.config.vietnamUnits.find(unit => unit.province_code === provinceValue);
        
        if (!selectedProvince) return false;

        this.wardChoices.clearStore();
        this.wardChoices.setChoices([{
            value: '',
            label: window.translationsVi.select_item.replace(':item', window.translationsVi.ward || 'Phường/Xã') || 'Chọn Phường/Xã',
            placeholderValue: window.translationsVi.select_item.replace(':item', window.translationsVi.ward || 'Phường/Xã') || 'Chọn Phường/Xã',
            selected: true,
        }], 'value', 'label', true);

        if (selectedProvince && selectedProvince.wards) {
            const wardItems = selectedProvince.wards.map(ward => ({
                value: ward.ward_code,
                label: ward.name,
                selected: (wardOldValue === ward.ward_code && provinceOldValue === selectedProvince.province_code)
            }));
            this.wardChoices.setChoices(wardItems, 'value', 'label', true);
        }

        return true;
    }

    validateWard() {
        const { input, feedback } = this.getFieldElements('ward');
        this.clearValidationState(input, feedback);
        return true;
    }

    validateStreet() {
        const { input, feedback } = this.getFieldElements('street');
        const value = input.value.trim();

        this.clearValidationState(input, feedback);

        if (!value) return true;
        
        if (value.length > 200) {
            // this.addValidationState(input, feedback, 'Địa chỉ có tối đa 200 ký tự', false);
            return false;
        }

        return true;
    }

    validateBirthday() {
        const { input: hiddenInput, feedback } = this.getFieldElements('birthday');
        const displayInput = document.getElementById('birthday-display');
        const btnElement = document.getElementById('birthday-btn');
        const value = hiddenInput.value;

        this.clearValidationState(displayInput, feedback);

        if (!value) {
            btnElement.classList.remove('is-invalid', 'is-valid');
            return true;
        }

        const selectedDate = new Date(value);
        const today = new Date();

        // if (selectedDate < minDate) {
        //     // this.addValidationState(displayInput, feedback, 'Ngày sinh phải sau ngày 01/01/1900', false);
        //     btnElement.classList.remove('is-valid');
        //     btnElement.classList.add('is-invalid');
        //     return false;
        // }
        if (selectedDate > today) {
            // this.addValidationState(displayInput, feedback, 'Ngày sinh không được lớn hơn ngày hiện tại', false);
            btnElement.classList.remove('is-valid');
            btnElement.classList.add('is-invalid');
            return false;
        }

        btnElement.classList.remove('is-invalid', 'is-valid');
        return true;
    }

    // === ASYNC VALIDATION ===
    async checkFieldAsync(fieldId, endpoint, callback) {
        const { input, feedback } = this.getFieldElements(fieldId);
        if (!input || !feedback) return;

        try {
            this.clearValidationState(input, feedback);

            const data = { [fieldId]: input.value.trim() };
            if (this.config.mode === 'edit') {
                data.id = this.config.currentId;
            }

            const result = await this.callAPI('post', endpoint, data);

            if (result.status === 'success') {
                this.clearValidationState(input, feedback);
                this.addValidationState(input, feedback);
                callback(true);
            } else {
                const fieldErrors = result.errors ? result.errors[fieldId] : null;
                const errorMessage = (fieldErrors && fieldErrors[0]) 
                                    || 'Dữ liệu không hợp lệ';
                this.addValidationState(input, feedback, errorMessage, false);
                callback(false);
            }
        } catch (error) {
            this.addValidationState(input, feedback, error.message, false);
            callback(false);
        } finally {
            const spinner = input.parentElement?.querySelector('.spinner-border');
            if (spinner) spinner.remove();
        }
    }

    // === FORM VALIDATION ===
    validateForm() {
        const fieldConfigs = {
            name: { validate: () => this.validateName(), stateKey: 'isNameValid', required: true },
            username: { validate: () => this.validateUsername(), stateKey: 'isUsernameValid', required: true, async: true },
            password: { validate: () => this.validatePassword(), stateKey: 'isPasswordValid', required: this.config.mode === 'create' },
            'password-confirmation': { validate: () => this.validatePasswordMatch(), stateKey: 'isPasswordMatch', required: false },
            email: { validate: () => this.validateEmail(), stateKey: 'isEmailValid', required: true, async: true },
            phone: { validate: () => this.validatePhone(), stateKey: 'isPhoneValid', required: true, async: true },
            province: { validate: () => this.validateProvince(), stateKey: 'isProvinceSelected', required: false },
            ward: { validate: () => this.validateWard(), stateKey: 'isWardSelected', required: false },
            street: { validate: () => this.validateStreet(), stateKey: 'isStreetValid', required: false },
            birthday: { validate: () => this.validateBirthday(), stateKey: 'isBirthdayValid', required: false }
        };

        let isValid = true;

        for (const [fieldId, config] of Object.entries(fieldConfigs)) {
            const result = config.validate();
            this.validationState[config.stateKey] = result;

            if (config.required && !result) {
                isValid = false;
            }

            if (config.async && !this.validationState[config.stateKey]) {
                isValid = false;
            }
        }

        const passwordValue = document.getElementById('password')?.value?.trim();
        if (passwordValue && !this.validationState.isPasswordMatch) {
            isValid = false;
        }

        return isValid;
    }

    // === PLUGIN INITIALIZATION ===
    initializePlugins() {
        // Flatpickr
        this.flatpickrInstance = flatpickr("#birthday-display", {
            dateFormat: "d/m/Y",
            maxDate: "today",
            locale: "vn",
            onChange: (selectedDates) => {
                const hiddenInput = document.getElementById('birthday');
                if (selectedDates.length > 0) {
                    const selectedDate = selectedDates[0];
                    const year = selectedDate.getFullYear();
                    const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
                    const day = String(selectedDate.getDate()).padStart(2, '0');
                    hiddenInput.value = `${year}-${month}-${day}`;
                } else {
                    hiddenInput.value = '';
                }
                this.validationState.isBirthdayValid = this.validateBirthday();
            }
        });

        document.getElementById('birthday-btn').addEventListener('click', () => {
            this.flatpickrInstance.open();
        });

        // Choices.js
        const provinceChoices = new Choices('#province', {
            searchEnabled: true,
            noResultsText: window.translationsVi.noDataFoundMessage || 'Không tìm thấy Tỉnh/Thành phố',
            itemSelectText: window.translationsVi.pressToSelect || 'Nhấn để chọn',
            allowHTML: false
        });

        this.wardChoices = new Choices('#ward', {
            searchEnabled: true,
            noResultsText: window.translationsVi.noDataFoundMessage || 'Không tìm thấy Phường/Xã',
            itemSelectText: window.translationsVi.pressToSelect || 'Nhấn để chọn',
            noChoicesText: window.translationsVi.please_select.replace(':item', window.translationsVi.province || 'Tỉnh/Thành phố') || 'Vui lòng chọn Tỉnh/Thành phố',
            placeholderValue: window.translationsVi.please_select.replace(':item', window.translationsVi.province || 'Tỉnh/Thành phố') || 'Vui lòng chọn Tỉnh/Thành phố',
            allowHTML: false
        });
    }

    // === EVENT LISTENERS ===
    attachEventListeners() {
        const nameInput = document.getElementById('name');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password-confirmation');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const provinceSelect = document.getElementById('province');
        const wardSelect = document.getElementById('ward');
        const streetInput = document.getElementById('street');

        // Basic validation
        nameInput.addEventListener('keyup', () => {
            this.validationState.isNameValid = this.validateName();
        });

        streetInput.addEventListener('keyup', () => {
            this.validationState.isStreetValid = this.validateStreet();
        });

        usernameInput.addEventListener('keyup', () => {
            this.validationState.isUsernameValid = this.validateUsername();
        });

        emailInput.addEventListener('keyup', () => {
            this.validationState.isEmailValid = this.validateEmail();
        });

        phoneInput.addEventListener('keyup', () => {
            this.validationState.isPhoneValid = this.validatePhone();
        });

        // Password validation
        passwordInput.addEventListener('input', () => {
            const passwordValue = passwordInput.value.trim();
            
            if (this.config.mode === 'edit') {
                if (passwordValue) {
                    passwordConfirmInput.disabled = false;
                } else {
                    passwordConfirmInput.disabled = true;
                    passwordConfirmInput.value = '';
                    const { feedback } = this.getFieldElements('password-confirmation');
                    this.clearValidationState(passwordConfirmInput, feedback);
                    this.validationState.isPasswordValid = true;
                    this.validationState.isPasswordMatch = true;
                    return;
                }
            }

            this.validationState.isPasswordValid = this.validatePassword();
            // this.validationState.isPasswordMatch = this.validatePasswordMatch();
        });

        passwordConfirmInput.addEventListener('keyup', () => {
            this.validationState.isPasswordMatch = this.validatePasswordMatch();
        });

        // const debouncedCheckUsername = this.debounce(() => {
        //     const value = usernameInput.value.trim();
            
        //     if (!this.validateUsername()) {
        //         this.validationState.isUsernameValid = false;
        //         return;
        //     }

        //     if (this.config.mode === 'edit' && value === this.config.originalData.username) {
        //         const { input, feedback } = this.getFieldElements('username');
        //         this.clearValidationState(input, feedback);
        //         this.addValidationState(input, feedback);
        //         this.validationState.isUsernameValid = true;
        //         return;
        //     }

        //     this.checkFieldAsync('username', this.config.endpoints.checkUsername, (isValid) => {
        //         this.validationState.isUsernameValid = isValid;
        //     });
        // }, 500);

        // const debouncedCheckEmail = this.debounce(() => {
        //     const value = emailInput.value.trim();
            
        //     if (!this.validateEmail()) {
        //         this.validationState.isEmailValid = false;
        //         return;
        //     }

        //     if (this.config.mode === 'edit' && value === this.config.originalData.email) {
        //         const { input, feedback } = this.getFieldElements('email');
        //         this.clearValidationState(input, feedback);
        //         this.addValidationState(input, feedback);
        //         this.validationState.isEmailValid = true;
        //         return;
        //     }

        //     this.checkFieldAsync('email', this.config.endpoints.checkEmail, (isValid) => {
        //         this.validationState.isEmailValid = isValid;
        //     });
        // }, 500);

        // const debouncedCheckPhone = this.debounce(() => {
        //     const value = phoneInput.value.trim();
            
        //     if (!this.validatePhone()) {
        //         this.validationState.isPhoneValid = false;
        //         return;
        //     }

        //     if (this.config.mode === 'edit' && value === this.config.originalData.phone) {
        //         const { input, feedback } = this.getFieldElements('phone');
        //         this.clearValidationState(input, feedback);
        //         this.addValidationState(input, feedback);
        //         this.validationState.isPhoneValid = true;
        //         return;
        //     }

        //     this.checkFieldAsync('phone', this.config.endpoints.checkPhone, (isValid) => {
        //         this.validationState.isPhoneValid = isValid;
        //     });
        // }, 500);

        // usernameInput.addEventListener('keyup', debouncedCheckUsername);
        // emailInput.addEventListener('keyup', debouncedCheckEmail);
        // phoneInput.addEventListener('keyup', debouncedCheckPhone);

        // Selects
        provinceSelect.addEventListener('change', () => {
            localStorage.setItem('selected_province', provinceSelect.value);
            this.validationState.isProvinceSelected = this.validateProvince();
        });

        wardSelect.addEventListener('change', () => {
            localStorage.setItem('selected_ward', wardSelect.value);
            this.validationState.isWardSelected = this.validateWard();
        });

        // Form submit
        this.form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(this.form);
            spinnerControl.show();
            try {
                const responseData = await this.callAPI('post', this.form.action, formData);
                console.log(responseData);
                if (responseData.status === 'success') {
                    localStorage.removeItem('selected_province');
                    localStorage.removeItem('selected_ward');
                    window.location.href = this.config.redirectUrl;
                } else if (responseData.errors) {
                    for (const [field, messages] of Object.entries(responseData.errors)) {
                        const { input, feedback } = this.getFieldElements(field);
                        if (input && feedback) {
                            this.addValidationState(input, feedback, messages[0], false);
                            feedback.classList.add('d-block');
                        }
                    }
                    spinnerControl.hide();
                }
            } catch (error) {
                spinnerControl.hide();
            }
        });

        // Cleanup localStorage on page leave
        window.addEventListener('beforeunload', () => {
            if (!this.form.submitted) {
                localStorage.removeItem('selected_province');
                localStorage.removeItem('selected_ward');
            }
        });
    }

    // === LOAD SAVED DATA ===
    loadSavedSelections() {
        const savedProvince = this.config.mode === 'edit' 
            ? this.config.originalData.province 
            : localStorage.getItem('selected_province');
        const savedWard = this.config.mode === 'edit' 
            ? this.config.originalData.ward 
            : localStorage.getItem('selected_ward');

        if (savedProvince) {
            localStorage.setItem('selected_province', savedProvince);
            const selectedProvince = this.config.vietnamUnits.find(unit => unit.province_code === savedProvince);
            
            if (selectedProvince && selectedProvince.wards) {
                this.wardChoices.clearStore();

                const wardOptions = [{
                    value: '',
                    label: window.translationsVi.select_item.replace(':item', window.translationsVi.ward || 'Phường/Xã') || 'Chọn Phường/Xã',
                    placeholderValue: window.translationsVi.select_item.replace(':item', window.translationsVi.ward || 'Phường/Xã') || 'Chọn Phường/Xã',
                    selected: !savedWard,
                }].concat(selectedProvince.wards.map(ward => ({
                    value: ward.ward_code,
                    label: ward.name,
                    selected: savedWard === ward.ward_code
                })));

                this.wardChoices.setChoices(wardOptions, 'value', 'label', true);
                if (savedWard) {
                    localStorage.setItem('selected_ward', savedWard);
                }
            }
        }
    }
}

if (typeof module !== 'undefined' && module.exports) {
    module.exports = AdminTenantFormValidator;
}