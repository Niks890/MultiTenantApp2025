$(document).on('click', '.btn-action-delete', function (e) {
    e.preventDefault();
    const form = $(this).closest('form');
    const url = form.attr('action');
    const method = form.find('input[name="_method"]').val() || form.attr('method');
    const token = $('meta[name="csrf-token"]').attr('content');

    Swal.fire({
        title: `${confirmDeleteBtnLabel}`,
        html: `Bạn có chắc chắn muốn xóa hợp đồng này ?`,
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
            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        setTimeout(() => location.reload(), 500);
                    } else {
                        setTimeout(() => location.reload(), 500);
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Không thể kết nối đến server.'
                    });
                });
        }
    });
});
