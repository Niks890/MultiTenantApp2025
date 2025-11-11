export default function showLoading(headerTitles = [], loadingMessage = 'Đang tải...') {
    const headerHtml = headerTitles.map(title => `<th>${title}</th>`).join('');
    $('#table-responsive').html(`
        <table class="table table-bordered modern-table mb-3">
            <thead>
                <tr>
                    ${headerHtml}
                </tr>
            </thead>
            <tbody id="table-body">
                <tr>
                    <td colspan="${headerTitles.length}" class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">${loadingMessage}</span>
                        </div>
                        <p class="text-muted mt-3 mb-0">${loadingMessage}</p>
                    </td>
                </tr>
            </tbody>
        </table>
    `);
}