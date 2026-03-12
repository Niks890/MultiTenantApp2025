
function resetCreateValidation() {
    $('#createPaymentMethodForm .form-control, #createPaymentMethodForm .form-select').removeClass('is-invalid').removeClass(
        'is-valid');
    $('#createPaymentMethodForm .invalid-feedback').empty();
}
function showCreateValidationErrors(errors) {
    for (const [field, messages] of Object.entries(errors)) {
        const input = $(`#${field}`);
        const errorDiv = $(`#${field}_error`);

        if (input.length) {
            input.addClass('is-invalid');
            errorDiv.text(messages[0]);
        }
    }
}
$('#createPaymentMethodForm').on('submit', function (e) {
    e.preventDefault();
    const $form = $(this);
    const formData = new FormData(this);
    $('#submitBtn').prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Đang xử lý...');
    spinnerControl.show();
    resetCreateValidation();
    $.ajax({
        url: $form.attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
        .done(function (data) {
            if (data.success) {
                window.location.href = paymentMethodIndexUrl;
            }
        })
        .fail(function (xhr) {
            const response = xhr.responseJSON || {};
            if (xhr.status === 422 && response.errors) {
                showCreateValidationErrors(response.errors);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: response.message || 'Có lỗi xảy ra khi thêm phương thức thanh toán.'
                });
            }
        })
        .always(function () {
            $('#submitBtn').prop('disabled', false).html(
                `<i class="fas fa-save me-1"></i> <span>${saveBtnLabel}</span>`
            );
            spinnerControl.hide();
        });
});

