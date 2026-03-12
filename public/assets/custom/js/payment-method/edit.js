
function resetEditValidation() {
    $('#editPaymentMethodForm .form-control, #editPaymentMethodForm .form-select').removeClass('is-invalid').removeClass(
        'is-valid');
    $('#editPaymentMethodForm .invalid-feedback').empty();
}
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
$('#editPaymentMethodForm').on('submit', function (e) {
    e.preventDefault();
    const $form = $(this);
    let formData = new FormData(this);
    formData.append('_method', 'PUT');
    $('#editSubmitBtn').prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Đang xử lý...');
    spinnerControl.show();
    resetEditValidation();
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
                showEditValidationErrors(response.errors);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: response.message || 'Có lỗi xảy ra khi cập nhật nhóm cửa hiệu.'
                });
            }
        })
        .always(function () {
            $('#editSubmitBtn').prop('disabled', false).html(
                `<i class="fas fa-save me-1"></i> <span>${saveBtnLabel}</span>`
            );
            spinnerControl.hide();
        });
});

