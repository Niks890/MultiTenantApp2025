
new Choices('select[name="admin_id"]', {
    searchEnabled: true,
    allowHTML: false,
    placeholderValue: 'Chọn tài khoản quản trị cửa hiệu',
    noResultsText: 'Không tìm thấy tài khoản quản trị cửa hiệu',
    noChoicesText: 'Không tìm thấy tài khoản quản trị cửa hiệu',
    itemSelectText: 'Nhấn để chọn',
    shouldSort: false,
});
$(document).on("click", ".btn-choose-admin", function () {
    let tenantId = $(this).data("tenant-id");
    let tenantName = $(this).data("tenant-name");
    $("#selectedTenantId").val(tenantId);
    $("#chooseAdminModal").modal("show");
    $("#chooseAdminModalLabel").text(
        "Chọn tài khoản quản trị cho cửa hiệu " + tenantName + ":"
    );
});
$("#saveAdminTenantBtn").on("click", function () {
    let tenantId = $("#selectedTenantId").val();
    let adminId = $("#adminAccountSelect").val();
    let csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!adminId) {
        showToast("error", "Vui lòng chọn tài khoản admin!");
        return;
    }
    let url = assignAdminRoute.replace(':id', tenantId);

    $.ajax({
        url: url,
        method: "POST",
        data: {
            admin_id: adminId,
            _token: csrf
        },
        success: function (response) {
            if (response.status) {
                $("#chooseAdminModal").modal("hide");
                window.fetchTenants(window.currentPage || 1);
                showToast("success", response.message || "Cập nhật thành công!");
            } else {
                showToast("error", response.message || "Có lỗi xảy ra!");
            }
        },
        error: function (xhr) {
            let response = xhr.responseJSON;
            if (response && response.message) {
                showToast("error", response.message);
            } else {
                showToast("error", "Lỗi kết nối!");
            }
            setTimeout(() => {
                window.fetchTenants(window.currentPage || 1);
            }, 1000);
        }
    });
});
$('#chooseAdminModal').on('hidden.bs.modal', function () {
    $('#adminAccountSelect').val('').trigger('change');
    $('#selectedTenantId').val('');
    $(this).find('form')[0]?.reset();
});
