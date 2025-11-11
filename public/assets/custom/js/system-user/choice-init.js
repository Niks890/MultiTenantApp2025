new Choices('select[name="status"]', {
    searchEnabled: false,
    allowHTML: false,
    shouldSort: false,
    placeholderValue: 'Chọn trạng thái',
    noResultsText: 'Không tìm thấy trạng thái',
});
$('.choices__inner').each(function () {
    $(this).css({
        height: '42px',
        minHeight: '42px',
        display: 'flex',
        alignItems: 'center',
        fontSize: '16px',
        fontWeight: '400',
    });
});
