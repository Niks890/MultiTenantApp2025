import showLoading from "../utils/loading-table.js";
import CallApi from "./callApi.js";

export class DataTableManager {
    constructor(options) {
        this.isLoading = false;
        this.searchTimeout = null;
        this.fetchUrl = ''; // URL search/pagination
        this.options = {
            ...options //  formSelector, tableSelector, paginationSelector, headerTitles, messages, ...
        };
        this.form = $(options.formSelector);
        this.tableContainer = $(options.tableSelector);
        this.paginationContainer = $(options.paginationSelector);
        this.toaster = new ToastNotification();
    }

    init() {
        const initialUrl = this.form.attr('action') + '?' + this.form.serialize();
        this.fetchData(initialUrl);

        if (this.options.choicesSelectors && Array.isArray(this.options.choicesSelectors)) {
            this.options.choicesSelectors.forEach(selector => {
                if ($(selector).length) {
                    new Choices(selector, {
                        searchEnabled: true,
                        allowHTML: false,
                        noResultsText: this.options.messages.noDataFound || 'Không tìm thấy dữ liệu. Vui lòng thử lại.',
                        itemSelectText: this.options.messages.pressToSelect || 'Nhấn để chọn',
                        shouldSort: false,
                    });
                }
            });
        }

        this.form.on('submit', (e) => {
            e.preventDefault();
            this.performSearch();
        });

        if (this.options.searchSelectSelector) {
            this.form.find(this.options.searchSelectSelector).on('change', () => {
                this.performSearch();
            });
        }

        if (this.options.searchInputSelector) {
            this.form.find(this.options.searchInputSelector).on('input', (e) => {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => this.performSearch(), 500);
            });
        }

        $(document).on('change', this.options.perPageSelector, (e) => {
            this.handlePerPageChange(e.currentTarget.value);
        });

        this.paginationContainer.on('click', '.pagination a', (e) => {
            e.preventDefault();
            this.handlePaginationClick(e.currentTarget.href);
        });

        if (this.options.toggleStatusSelector) {
            this.tableContainer.on('click', this.options.toggleStatusSelector, (e) => {
                this.toggleStatus($(e.currentTarget));
            });
        }

        if (this.options.deleteButtonSelector) {
            this.tableContainer.on('click', this.options.deleteButtonSelector, (e) => {
                this.deleteItem($(e.currentTarget));
            });
        }

        if (this.options.addNewSelector) {
            $(this.options.addNewSelector).on('click', function () {
                window.location.href = $(this).data('create-url');
            });
        }
    }

    fetchData(url) {
        if (this.isLoading) return;

        this.isLoading = true;
        this.fetchUrl = url;
        const { headerTitles, messages } = this.options;

        showLoading(headerTitles, messages.loading);

        const api = new CallApi({
            url: url,
            method: 'GET'
        });

        api.execute({
            onSuccess: (response) => {
                this.tableContainer.html(response.table);
                this.paginationContainer.html(response.pagination);
            },
            onError: (xhr) => {
                this.renderFetchError(xhr);
            },
            onAlways: () => {
                this.isLoading = false;
            }
        }, false);
    }

    performSearch() {
        let url = this.form.attr('action') + '?' + this.form.serialize();
        let urlObj = new URL(url, window.location.origin);
        let currentPerPage = $(this.options.perPageSelector).val();
        if (currentPerPage) {
        urlObj.searchParams.set('per_page', currentPerPage);
    }
        urlObj.searchParams.set('page', 1);
        this.fetchData(urlObj.toString());
    }

    handlePerPageChange(perPage) {
        let url = new URL(this.fetchUrl, window.location.origin);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', 1);
        this.fetchData(url.toString());
    }

    handlePaginationClick(url) {
        let currentPerPage = $(this.options.perPageSelector).val();
        let urlObj = new URL(url);
        if (currentPerPage && !urlObj.searchParams.has('per_page')) {
            urlObj.searchParams.set('per_page', currentPerPage);
        }
        this.fetchData(urlObj.toString());
    }

    toggleStatus(button) {
        const { messages } = this.options;
        const currentStatus = button.data('status');
        const name = button.data('name');
        const newStatus = currentStatus === 1 ? 0 : 1;
        const actionText = currentStatus === 1 ? (messages.turnOff || 'Khoá') : (messages.turnOn || 'Kích hoạt');

        const finalMessage = name
                            ? (messages.confirm
                            ? messages.confirm.replace(':item', actionText.toLowerCase() + ' "<strong>' + name + '</strong>"')
                            : `Bạn có chắc chắn muốn ${actionText.toLowerCase()} "<strong>${name}</strong>" không?`)
                            : `Bạn có chắc chắn muốn ${actionText.toLowerCase()} mục này không?`;

        Swal.fire({
            title: `${messages.confirmAction} ${actionText.toLowerCase()}?`,
            html: finalMessage,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: actionText,
            cancelButtonText: messages.cancel || 'Huỷ',
            confirmButtonColor: currentStatus === 1 ? '#e74c3c' : '#28a745',
            reverseButtons: true,
            // ...
        }).then((result) => {
            if (result.isConfirmed) {
                const apiCaller = new CallApi({
                    url: button.data('route'),
                    method: 'PATCH',
                    data: { status: newStatus },
                });
                apiCaller.execute({
                    onSuccess: (response) => {
                        this.toaster.success(response.message);

                        if (response.refreshTable === true) {
                            this.fetchData(this.fetchUrl);
                        } else {
                            button.closest('td').html(response.newStatusHtml);
                        }
                    }
                });
            }
        });
    }

    deleteItem(button) {
        const { messages } = this.options;
        const route = button.data('route');
        const name = button.data('name');

        const finalMessage = name
                            ? (messages.confirm
                            ? messages.confirm.replace(':item', messages.delete.toLowerCase() + ' "<strong>' + name + '</strong>"')
                            : `Bạn có chắc chắn muốn xóa ${name} không?`)
                            : 'Bạn có chắc chắn muốn xóa mục này không?';

        Swal.fire({
            title: `${messages.deleteConfirm}`,
            html: finalMessage,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: messages.confirmAction || 'Xác nhận',
            cancelButtonText: messages.cancel || 'Huỷ',
            confirmButtonColor: '#e74c3c',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                const apiCaller = new CallApi({
                    url: route,
                    method: 'DELETE',
                });

                apiCaller.execute({
                    onSuccess: (response) => {
                        this.toaster.success(response.message);
                        this.fetchData(this.fetchUrl);
                    }
                });
            }
        });
    }

    renderFetchError(xhr) {
        const { headerTitles, messages } = this.options;
        const errorMessage = xhr.responseJSON?.message || messages.fetchError || 'Dữ liệu không tải được. Vui lòng thử lại.';

        const errorHtml = `
            <tr>
                <td colspan="${headerTitles.length}" class="text-center py-5">
                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                    <p class="text-muted mt-2 mb-0">${errorMessage}</p>
                </td>
            </tr>
        `;

        this.tableContainer.find('#table-body').html(errorHtml);
    }
}
