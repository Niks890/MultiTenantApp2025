document.addEventListener('DOMContentLoaded', function () {
    const addForm = document.getElementById('createGroupForm');
    const editForm = document.getElementById('editGroupForm');

    function validateGroupName(value) {
        if (!value.trim()) return 'Vui lòng nhập tên nhóm cửa hiệu';
        if (value.length < 6) return 'Tên nhóm cửa hiệu phải có ít nhất 6 ký tự';
        if (value.length > 100) return 'Tên nhóm cửa hiệu không được vượt quá 100 ký tự';
        return '';
    }

    function validateDescription(value) {
        if (value.length > 100) return 'Mô tả nhóm cửa hiệu không được vượt quá 100 ký tự';
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
            group_name: validateGroupName,
            group_description: validateDescription,
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
            const groupName = form.querySelector('[name="group_name"]');
            const groupDescription = form.querySelector('[name="group_description"]');

            const errors = {
                group_name: validateGroupName(groupName.value),
                group_description: validateDescription(groupDescription.value),
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
