$(document).ready(function () {
    const $form = $('#searchContractForm');
    let typingTimer;
    let currentPage = 1;
    $form.on('submit', function (e) {
        e.preventDefault();
        fetchContracts(1);
    });


    const statusChoices = new Choices('select[name="status"]', {
        itemSelectText: 'Nhấn để chọn',
        allowHTML: false,
        placeholderValue: 'Lọc theo trạng thái hợp đồng',
        noResultsText: 'Không tìm thấy trạng thái hợp đồng',
        noChoicesText: 'Không tìm thấy trạng thái hợp đồng',
        shouldSort: false,
    });


    const tenantChoices = new Choices('select[name="tenant_id"]', {
        searchEnabled: true,
        allowHTML: false,
        placeholderValue: 'Lọc theo tên cửa hiệu',
        noResultsText: 'Không tìm thấy cửa hiệu',
        noChoicesText: 'Không tìm thấy cửa hiệu',
        itemSelectText: 'Nhấn để chọn',
        shouldSort: false,
    });

    const planChoices = new Choices('select[name="plan_id"]', {
        searchEnabled: true,
        allowHTML: false,
        placeholderValue: 'Lọc theo gói đăng ký',
        noResultsText: 'Không tìm thấy gói đăng ký',
        noChoicesText: 'Không tìm thấy gói đăng ký',
        itemSelectText: 'Nhấn để chọn',
        shouldSort: false,
    });
    function fetchContracts(page = 1) {
        currentPage = page;

        const keyword = $('input[name="keyword"]').val();
        const status = $('select[name="status"]').val();
        const tenantId = $('select[name="tenant_id"]').val();
        const planId = $('select[name="plan_id"]').val();
        const paginate = $('#paginate').val();

        const params = {
            keyword: keyword,
            status: status,
            tenant_id: tenantId,
            plan_id: planId,
            paginate: paginate,
            page: page
        };
        $.ajax({
            url: $form.attr('action'),
            type: 'GET',
            data: params,
            beforeSend: function () {
                $('#contractTableWrapper').addClass('loading');
                $('#contractTableWrapper').html(
                    '<div class="text-center"><i class="bx bx-loader bx-spin fs-2"></i> Đang tải...</div>'
                );
            },
            success: function (response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }
                $('#contractTableWrapper').html(response.table);
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
                $('#contractTableWrapper').removeClass('loading');
            }
        });
    }

    $('.search-input').on('input', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            fetchContracts(1);
        }, 1000);
    });

    $('.status-select, .plan-select, .tenant-select').on('change', function () {
        fetchContracts(1);
    });

    $(document).on('click', '.modern-pagination a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const urlParams = new URLSearchParams(url.split('?')[1]);
            const page = urlParams.get('page') || 1;
            fetchContracts(page);
        }
    });

    $(document).on('change', '#paginate', function (e) {
        fetchContracts(1);
    });

    $(window).on('popstate', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const keyword = urlParams.get('keyword') || '';
        const status = urlParams.get('status') || '';
        const tenantId = urlParams.get('tenant_id') || '';
        const planId = urlParams.get('plan_id') || '';
        const paginate = urlParams.get('paginate') || '10';
        const page = urlParams.get('page') || 1;
        $('input[name="keyword"]').val(keyword);
        $('select[name="status"]').val(status);
        $('select[name="tenant_id"]').val(tenantId);
        $('select[name="plan_id"]').val(planId);
        $('#paginate').val(paginate);
        fetchContracts(page);
    });
    window.fetchContracts = fetchContracts;
    window.currentPage = currentPage;
});
