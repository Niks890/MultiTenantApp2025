function formatVND(val) {
    const num = parseInt(String(val).replace(/[^\d]/g, ''), 10);
    if (isNaN(num) || num === 0) return '';
    return num.toLocaleString('vi-VN');
}

function stripFormat(val) {
    return String(val).replace(/[^\d]/g, '');
}

function validateImageFile(file, maxMB = 2) {
    if (!file) return '';
    const allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!allowed.includes(file.type)) return TRANSLATIONS.error_format;
    if (file.size > maxMB * 1024 * 1024) return TRANSLATIONS.error_size.replace(':size', maxMB);
    return '';
}

function reloadContractPartials() {
    $.get(window.location.href, function(html) {
        const $html = $(html);
        $('#paymentTableWrapper').html($html.find('#paymentTableWrapper').html());
        $('#paymentPaginationWrapper').html($html.find('#paymentPaginationWrapper').html());
        $('#summaryPaymentWrapper').html($html.find('#summaryPaymentWrapper').html());
    });
}

// Flatpickr & Choices giữ nguyên cấu hình của bạn
const fpConfig = {
    dateFormat: 'd/m/Y',
    allowInput: true,
    locale: 'vn',
    monthSelectorType: 'static',
    onOpen: function(selectedDates, dateStr, instance) {
        $(instance.calendarContainer).css('z-index', '9999');
    }
};

const fpAddInstance = flatpickr("#payment_date_display", {
    ...fpConfig,
    onChange: function(selectedDates) {
        if (selectedDates[0]) {
            const iso = selectedDates[0].toISOString().split('T')[0];
            $('#payment_date_raw').val(iso);
            $('#payment_date_display').removeClass('is-invalid');
        }
    }
});

const fpEditInstance = flatpickr("#edit_paid_at_display", {
    ...fpConfig,
    onChange: function(selectedDates) {
        if (selectedDates[0]) {
            const iso = selectedDates[0].toISOString().split('T')[0];
            $('#edit_paid_at_raw').val(iso);
            $('#edit_paid_at_display').removeClass('is-invalid');
        }
    }
});

const choicesAdd = new Choices('#payment_method', { searchEnabled: false, itemSelectText: '', allowHTML: false });
const choicesEdit = new Choices('#edit_payment_method', { searchEnabled: false, itemSelectText: '', allowHTML: false });

// Input mask cho tiền tệ
$('#amount_display, #edit_amount_display').on('input', function() {
    const isEdit = this.id.includes('edit');
    const raw = stripFormat($(this).val());
    $(this).val(raw ? formatVND(raw) : '');
    $(isEdit ? '#edit_amount_raw' : '#amount_raw').val(raw);
});

// Submit Form Thêm mới
$('#formAddTransaction').on('submit', function(e) {
    e.preventDefault();
    const amountRaw = stripFormat($('#amount_display').val());
    $('#amount_raw').val(amountRaw);

    const dateValid = !!$('#payment_date_raw').val();
    const methodValid = !!$('#payment_method').val();
    const fileInput = $('#file_path')[0];
    const fileValid = fileInput.files && fileInput.files.length > 0;

    if (!dateValid) $('#payment_date_display').addClass('is-invalid');
    if (!methodValid) $('#payment_method').closest('.choices').addClass('is-invalid');
    if (!fileValid) $('#file_path_error').addClass('d-block').text(TRANSLATIONS.error_invoice);

    if (!this.checkValidity() || !dateValid || !methodValid || !fileValid || !amountRaw) {
        $(this).addClass('was-validated');
        return;
    }

    const formData = new FormData(this);
    const $btn = $('#btnSaveTransaction');
    $btn.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm me-1"></span>${TRANSLATIONS.saving}`);

    $.ajax({
        url: storeTransactionUrl,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            $('#modalAddTransaction').modal('hide');
            reloadContractPartials();
            showToast('success', res.message ?? TRANSLATIONS.success_add);
        },
        error: function(xhr) {
            const logicError = xhr.responseJSON?.error;
            const validationErrors = xhr.responseJSON?.errors;
            if (logicError) {
                showToast('error', logicError);
            } else if (validationErrors) {
                Object.values(validationErrors).flat().forEach(msg => showToast('error', msg));
            } else {
                showToast('error', TRANSLATIONS.error_general);
            }
        },
        complete: function() {
            $btn.prop('disabled', false).html(`<i class="fa fa-save me-1"></i>${TRANSLATIONS.save}`);
        }
    });
});

// Edit Transaction Logic
$(document).on('click', '.btn-edit-transaction', function() {
    $('#formEditTransaction').removeClass('was-validated');
    const id = $(this).data('id');
    let amount = $(this).data('amount');
    const paidAt = $(this).data('paid_at');
    const methodId = String($(this).data('payment_method_id'));
    const fileUrl = $(this).data('file');

    $('#edit_transaction_id').val(id);
    $('#edit_amount_raw').val(amount);
    $('#edit_amount_display').val(formatVND(amount));

    if (paidAt) {
        fpEditInstance.setDate(paidAt, false);
        $('#edit_paid_at_raw').val(paidAt);
    }
    choicesEdit.setChoiceByValue(methodId);

    if (fileUrl) {
        $('#editCurrentImage').attr('src', fileUrl);
        $('#editCurrentImageWrapper').removeClass('d-none');
        $('#editUploadArea').addClass('d-none');
        $('#edit_keep_file').val('1');
    }
    $('#modalEditTransaction').modal('show');
});

// Submit Form Cập nhật
$('#formEditTransaction').on('submit', function(e) {
    e.preventDefault();
    const amountRaw = stripFormat($('#edit_amount_display').val());
    $('#edit_amount_raw').val(amountRaw);

    const isKeepFile = $('#edit_keep_file').val() === '1';
    const hasNewFile = $('#edit_file_path')[0].files.length > 0;
    const fileEditValid = isKeepFile || hasNewFile;

    if (!fileEditValid) {
        $('#edit_file_path_error').addClass('d-block').text(TRANSLATIONS.error_file_select);
        return;
    }

    const formData = new FormData(this);
    formData.append('_method', 'PUT');
    const url = updateTransactionUrl.replace(':id', $('#edit_transaction_id').val());
    const $btn = $('#btnSaveEditTransaction');

    $btn.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm me-1"></span>${TRANSLATIONS.saving}`);

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            $('#modalEditTransaction').modal('hide');
            reloadContractPartials();
            showToast('success', res.message ?? TRANSLATIONS.success_update);
        },
        error: function(xhr) {
            const logicError = xhr.responseJSON?.error;
            if (logicError) {
                showToast('error', logicError);
            } else {
                showToast('error', TRANSLATIONS.error_general);
            }
        },
        complete: function() {
            $btn.prop('disabled', false).html(`<i class="fa fa-save me-1"></i>${TRANSLATIONS.save}`);
        }
    });
});
