$(document).on('click', '.toggle-status', function () {
    const tenantId = $(this).data('id');
    const currentStatus = $(this).data('status');
    const tenantName = $(this).data('name');
    const newStatus = currentStatus ? 0 : 1;
    const title = newStatus ?
        'Kích hoạt cửa hiệu này?' :
        'Đưa cửa hiệu vào chế độ bảo trì?';
    const confirmText = newStatus ? 'Kích hoạt' : 'Bảo trì';
    const icon = 'warning';

    Swal.fire({
        title: title,
        text: newStatus ?
            `Cửa hiệu ${tenantName} sẽ được mở lại và hoạt động bình thường.` :
            `Cửa hiệu ${tenantName} sẽ tạm dừng hoạt động để bảo trì.`,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: confirmText,
        cancelButtonText: 'Hủy',
        reverseButtons: true,
        customClass: {
            title: 'fs-3',
            text: 'fs-6',
            confirmButton: newStatus ? 'ms-2 btn btn-success' : 'ms-2 btn btn-warning',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            const url = updateStatusRoute.replace(':id', tenantId);
            spinnerControl.show();
            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    tenant_status: newStatus,
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    _method: 'PATCH'
                },
                success: function (response) {
                    spinnerControl.hide();
                    if (response.status) {
                        showToast('success', response.message);
                        window.fetchTenants(window.currentPage || 1);
                    } else {
                        showToast('error', response.message);
                        window.fetchTenants(window.currentPage || 1);
                    }
                },
                error: function (xhr, status, error) {
                    spinnerControl.hide();
                    let errorMessage =
                        'Không thể cập nhật trạng thái, vui lòng thử lại sau.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showToast('error', errorMessage);
                }
            });
        }
    });
});
