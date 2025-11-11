$(document).ready(function () {
    const $form = $('#searchTenantForm');
    let typingTimer;
    let currentPage = 1;
    $form.on('submit', function (e) {
        e.preventDefault();
        fetchTenants(1);
    });


    const statusChoices = new Choices('select[name="status"]', {
        itemSelectText: 'Nhấn để chọn',
        allowHTML: false,
        placeholderValue: 'Lọc theo trạng thái cửa hiệu',
        noResultsText: 'Không tìm thấy trạng thái cửa hiệu',
        noChoicesText: 'Không tìm thấy trạng thái cửa hiệu',
        shouldSort: false,
    });


    const groupChoices = new Choices('select[name="group_id"]', {
        searchEnabled: true,
        allowHTML: false,
        placeholderValue: 'Lọc theo nhóm cửa hiệu',
        noResultsText: 'Không tìm thấy nhóm cửa hiệu',
        noChoicesText: 'Không tìm thấy nhóm cửa hiệu',
        itemSelectText: 'Nhấn để chọn',
        shouldSort: false,
    });

    const adminTenantChoices = new Choices('select[name="admin_tenant_id"]', {
        searchEnabled: true,
        allowHTML: false,
        placeholderValue: 'Lọc theo chủ cửa hiệu',
        noResultsText: 'Không tìm thấy chủ cửa hiệu',
        noChoicesText: 'Không tìm thấy chủ cửa hiệu',
        itemSelectText: 'Nhấn để chọn',
        shouldSort: false,
    });
    function fetchTenants(page = 1) {
        currentPage = page;

        const keyword = $('input[name="keyword"]').val();
        const status = $('select[name="status"]').val();
        const groupId = $('select[name="group_id"]').val();
        const adminTenantId = $('select[name="admin_tenant_id"]').val();
        const paginate = $('#paginate').val();

        const params = {
            keyword: keyword,
            status: status,
            group_id: groupId,
            admin_tenant_id: adminTenantId,
            paginate: paginate,
            page: page
        };
        $.ajax({
            url: $form.attr('action'),
            type: 'GET',
            data: params,
            beforeSend: function () {
                $('#tenantTableWrapper').addClass('loading');
                $('#tenantTableWrapper').html(
                    '<div class="text-center"><i class="bx bx-loader bx-spin fs-2"></i> Đang tải...</div>'
                );
            },
            success: function (response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }
                $('#tenantTableWrapper').html(response.table);
                $('#paginationWrapper').html(response.pagination);
                if (response.selected_paginate) {
                    $('#paginate').val(response.selected_paginate);
                }
            },
            error: function (xhr) {
                let errorMessage = 'Lỗi khi tải danh sách cửa hiệu.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                alert(errorMessage);
            },
            complete: function () {
                $('#tenantTableWrapper').removeClass('loading');
            }
        });
    }

    $('.search-input').on('input', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            fetchTenants(1);
        }, 1000);
    });

    $('.status-select, .group-select, .admin-select').on('change', function () {
        fetchTenants(1);
    });

    $(document).on('click', '.modern-pagination a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const urlParams = new URLSearchParams(url.split('?')[1]);
            const page = urlParams.get('page') || 1;
            fetchTenants(page);
        }
    });

    $(document).on('change', '#paginate', function (e) {
        fetchTenants(1);
    });

    $(window).on('popstate', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const keyword = urlParams.get('keyword') || '';
        const status = urlParams.get('status') || '';
        const groupId = urlParams.get('group_id') || '';
        const adminTenantId = urlParams.get('admin_tenant_id') || '';
        const paginate = urlParams.get('paginate') || '10';
        const page = urlParams.get('page') || 1;
        $('input[name="keyword"]').val(keyword);
        $('select[name="status"]').val(status);
        $('select[name="group_id"]').val(groupId);
        $('select[name="admin_tenant_id"]').val(adminTenantId);
        $('#paginate').val(paginate);
        fetchTenants(page);
    });
    window.fetchTenants = fetchTenants;
    window.currentPage = currentPage;
});
