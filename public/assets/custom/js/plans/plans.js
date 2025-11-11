import { DataTableManager } from '../common/dataTableManager.js';

const headerTitles = [no, planName, planPrice, cycle, description, planStatus, action];
const messages = {
    loading: loadingMessage,
    fetchError: fetchErrorMessage,
    noDataFound: noDataFoundMessage,
    pressToSelect: pressToSelect,
    turnOn: turnOn,
    turnOff: turnOff,
    confirm: confirmMessage,
    confirmAction: confirmActionMessage,
    delete: deleteMessage,
    deleteConfirm: deleteConfirmMessage,
    cancel: cancelMessage,
    error: errorMessage
};

$(function() {
    const tableOptions = {
        formSelector: '#searchForm',
        tableSelector: '#table-responsive',
        paginationSelector: '#pagination-container',
        perPageSelector: '#perPage',
        headerTitles: headerTitles,
        messages: messages,

        addNewSelector: '#addNew',
        choicesSelectors: ['select[name="status"]', 'select[name="cycle"]'],
        searchSelectSelector: 'select',
        searchInputSelector: 'input[name="search"]',
        toggleStatusSelector: '.toggle-status',
        deleteButtonSelector: '.btn-action-delete'
    };

    const planTableManager = new DataTableManager(tableOptions);
    planTableManager.init();
});