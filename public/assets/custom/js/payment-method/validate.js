document.addEventListener('DOMContentLoaded', function () {
    const addForm = document.getElementById('createPaymentMethodForm');
    const editForm = document.getElementById('editPaymentMethodForm');

    function validatePaymentMethodName(value) {
        if (!value.trim()) return 'Vui lòng nhập tên nhóm cửa hiệu';
        if (value.length > 100) return 'Tên nhóm cửa hiệu không được vượt quá 100 ký tự';
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
            if (!message && input.name !== 'group_description') {
                input.classList.add('is-valid');
            }
            if (errorDiv) errorDiv.textContent = '';
        }
    }
    function attachRealtimeValidation(form, isEdit = false) {
        const fields = {
            group_name: validatePaymentMethodName,
        };

        Object.entries(fields).forEach(([name, validator]) => {
            const input = form.querySelector(`[name="${name}"]`);
            if (!input) return;

            input.addEventListener('input', () => {
                const error = validator(input.value);
                showError(input, error, false);
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
    }

    // submit form
    function handleFormSubmit(form, isEdit = false) {
        form.addEventListener('submit', function (e) {
            let valid = true;
            const paymentMethodName = form.querySelector('[name="payment_method_name"]');
            const errors = {
                payment_method_name: validatePaymentMethodName(paymentMethodName.value),
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
    }
    if (editForm) {
        attachRealtimeValidation(editForm, true);
        handleFormSubmit(editForm, true);
    }
});
