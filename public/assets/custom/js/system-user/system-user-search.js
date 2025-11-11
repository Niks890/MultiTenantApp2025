$(document).ready(function () {
    const $form = $('#searchSystemUserForm');
    let typingTimer;
    let currentPage = 1;
    $form.on('submit', function (e) {
        e.preventDefault();
        fetchUsers(1);
    });
    // update url when search or paginate
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

    function fetchUsers(page = 1) {
        currentPage = page;

        const keyword = $('input[name="keyword"]').val();
        const status = $('select[name="status"]').val();
        const paginate = $('#paginate').val();

        const params = {
            keyword: keyword,
            status: status,
            paginate: paginate,
            page: page
        };

        // updateURL(params);

        $.ajax({
            url: $form.attr('action'),
            type: 'GET',
            data: params,
            beforeSend: function () {
                $('#userTableWrapper').addClass('loading');
                $('#userTableWrapper').html(
                    '<div class="text-center"><i class="bx bx-loader bx-spin fs-2"></i> Đang tải...</div>'
                );
            },
            success: function (response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }
                $('#userTableWrapper').html(response.table);
                $('#paginationWrapper').html(response.pagination);
                if (response.selected_paginate) {
                    $('#paginate').val(response.selected_paginate);
                }
            },
            error: function (xhr) {
                let errorMessage = 'Lỗi khi tải danh sách người dùng.';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
                alert(errorMessage);
            },
            complete: function () {
                $('#userTableWrapper').removeClass('loading');
            }
        });
    }

    $('.search-input').on('input', function () {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(() => {
            fetchUsers(1);
        }, 1000);
    });

    $('.status-select').on('change', function () {
        fetchUsers(1);
    });



    $(document).on('click', '.modern-pagination a', function (e) {
        e.preventDefault();
        const url = $(this).attr('href');
        if (url) {
            const urlParams = new URLSearchParams(url.split('?')[1]);
            const page = urlParams.get('page') || 1;
            fetchUsers(page);
        }
    });

    $(document).on('change', '#paginate', function (e) {
        fetchUsers(1);
    });

    $(window).on('popstate', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const keyword = urlParams.get('keyword') || '';
        const status = urlParams.get('status') || '';
        const paginate = urlParams.get('paginate') || '10';
        const page = urlParams.get('page') || 1;
        $('input[name="keyword"]').val(keyword);
        $('select[name="status"]').val(status);
        $('#paginate').val(paginate);
        fetchUsers(page);
    });
    window.fetchUsers = fetchUsers;
    window.currentPage = currentPage;
});
