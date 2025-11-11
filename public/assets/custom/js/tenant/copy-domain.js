$(document).on('click', '.copy-domain', function () {
    const domain = $(this).data('domain');

    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(domain).then(() => {
            showToast('success', 'Đã sao chép thành công!');
        }).catch(() => {
            showToast('error', 'Không thể sao chép!');
        });
    } else {
        const textArea = document.createElement('textarea');
        textArea.value = domain;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            showToast('success', 'Đã sao chép thành công!');
        } catch (err) {
            showToast('error', 'Không thể sao chép!');
        }
        document.body.removeChild(textArea);
    }
});
