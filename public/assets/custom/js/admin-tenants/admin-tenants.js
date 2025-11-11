import { DataTableManager } from '../common/dataTableManager.js';

const headerTitles = [no, accountName, fullName, email, phone, address, tenant, action];
const messages = {
    loading: loadingMessage,
    fetchError: fetchErrorMessage,
    noDataFound: noDataFoundMessage,
    pressToSelect: pressToSelect,
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
        choicesSelectors: ['select[name="tenant_id"]'],
        searchSelectSelector: 'select',
        searchInputSelector: 'input[name="search"]',
    };

    const adminTenantTableManager = new DataTableManager(tableOptions);
    adminTenantTableManager.init();
});