$(document).on('click', '.btn-action-delete', function (e) {
    e.preventDefault();
    const userName = $(this).data('name');
    const form = $(this).closest('form');
    Swal.fire({
        title: `${confirmDeleteBtnLabel}`,
        html: `Bạn có chắc chắn muốn xóa người dùng <b>${userName}</b>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `${deleteBtnLabel}`,
        cancelButtonText: `${cancelBtnLabel}`,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#6c757d',
        reverseButtons: true,
        customClass: {
            confirmButton: 'ms-2 btn btn-danger',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            spinnerControl.show();
            form.submit();
        }
    });
});
