const deleteConfirmTitle = window.translationsVi.delete_confirm || 'Xác nhận xóa?';
const deleteConfirmText = window.translationsVi.delete || 'Xóa';
const deleteCancelText = window.translationsVi.cancel || 'Hủy';


const ToastDelete = Swal.mixin({
    title: deleteConfirmTitle,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: deleteConfirmText,
    cancelButtonText: deleteCancelText,
    confirmButtonColor: '#e74c3c',
    cancelButtonColor: '#6c757d',
    reverseButtons: true,
    customClass: {
        confirmButton: 'ms-2 btn btn-danger',
        cancelButton: 'btn btn-secondary'
    },
    buttonsStyling: false
});

$(function() {
    
    $('#back-button').on('click', function(e) {
        e.preventDefault();
        const url = $(this).data('route');
        if (url) {
            window.location.href = url;
        } else {
            toastNotification.error(window.translationsVi.urlNotFoundMessage || 'Không tìm thấy trang');
        }
    });

    $('#edit-button').on('click', function(e) {
        e.preventDefault();
        const url = $(this).data('route');

        if (url) {
            window.location.href = url;
        } else {
            toastNotification.error(window.translationsVi.urlNotFoundMessage || 'Không tìm thấy trang');
        }
    });

    $('#delete-btn').on('click', function(e) {
        e.preventDefault();
        const url = $('#delete-form').attr('action');

        if (!url) {
            toastNotification.error(window.translationsVi.urlNotFoundMessage || 'Không tìm thấy trang');
            return;
        }

        const name = $('#delete-form').data('name');
        const deleteConfirmMessage = window.translationsVi.deleteConfirmMessage.replace(':item', name) ||
            'Bạn có chắc chắn muốn xóa?';
        const deleteErrorMessage = window.translationsVi.deleteErrorMessage ||
            'Dữ liệu không xóa được. Vui lòng thử lại.';
        const route = $('#delete-form').data('route');

        ToastDelete.fire({
            html: `${deleteConfirmMessage}`,
        }).then((result) => {
            if (result.isConfirmed) {
                $('button').prop('disable', true);
                spinnerControl.show();
                $.ajax({
                        url: url,
                        type: 'DELETE',
                        dataType: 'json',
                    })
                    .done(response => {
                        $('button').prop('disable', false);
                        if (response.status == true) {
                            window.location.href = route;
                        } else {
                            toastNotification.error(response.message || deleteErrorMessage);
                        }
                    })
                    .fail((xhr, status, error) => {
                        $('button').prop('disable', false);
                        let errorMessage = xhr.responseJSON.message || deleteErrorMessage;
                        toastNotification.error(errorMessage);
                    })
                    .always(() => {
                        spinnerControl.hide();
                    })

            }
        });
    });

});

