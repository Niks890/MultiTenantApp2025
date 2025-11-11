$(document).ready(function () {
    const $form = $('#searchGroupForm');
    let typingTimer;
    let currentPage = 1;
    $form.on('submit', function (e) {
        e.preventDefault();
        fetchGroups(1);
    });
    function updateURL(params) {
        const url = new URL(window.location.href);
        Object.keys(params).forEach(key => {
            if (params[key]) {
                url.searchParams.set(key, params[key]);
            } else {
                url.searchParams.delete(key);
            }
        });

        if (params.page && params.page > 1) {
            url.searchParams.set('page', params.page);
        } else {
            url.searchParams.delete('page');
        }

        window.history.replaceState({}, '', url.toString());
    }

    function fetchGroups(page = 1) {
        currentPage = page;

        const keyword = $('input[name="keyword"]').val();
        const paginate = $('#paginate').val();

        const params = {
            keyword: keyword,
            paginate: paginate,
            page: page
        };

        // updateURL(params);

        $.ajax({
            url: $form.attr('action'),
            type: 'GET',
            data: params,
            beforeSend: function () {
                $('#groupTableWrapper').addClass('loading');
                $('#groupTableWrapper').html(
                    '<div class="text-center"><i class="bx bx-loader bx-spin fs-2"></i> Đang tải...</div>'
                );
            },
            success: function (response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }
                $('#groupTableWrapper').html(response.table);
                $('#paginationWrapper').html(response.pagination);
                if (response.selected_paginate) {
                    $('#paginate').val(response.selected_paginate);
                }
            },
            error: function (xhr) {
                let errorMessage = 'Lỗi khi tải danh sách nhóm cửa hiệu.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                alert(errorMessage);
            },
            complete: function () {
                $('#groupTableWrapper').removeClass('loading');
            }
        });
    }

    $('.search-input').on('input', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            fetchGroups(1);
        }, 1000);
    });

    $('.status-select').on('change', function () {
        fetchGroups(1);
    });



    $(document).on('click', '.modern-pagination a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const urlParams = new URLSearchParams(url.split('?')[1]);
            const page = urlParams.get('page') || 1;
            fetchGroups(page);
        }
    });

    $(document).on('change', '#paginate', function (e) {
        fetchGroups(1);
    });

    $(window).on('popstate', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const keyword = urlParams.get('keyword') || '';
        const paginate = urlParams.get('paginate') || '10';
        const page = urlParams.get('page') || 1;
        $('input[name="keyword"]').val(keyword);
        $('#paginate').val(paginate);
        fetchGroups(page);
    });
});
