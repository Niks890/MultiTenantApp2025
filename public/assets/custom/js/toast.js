document.addEventListener('DOMContentLoaded', function () {
    ['success', 'error'].forEach(type => {
        const toast = document.getElementById(`toast-${type}`);
        if (toast) {
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => {
                toast.classList.remove('show');
                toast.classList.add('hide');
                toast.addEventListener('transitionend', () => {
                    toast.remove();
                }, { once: true });
            }, 2500);
            const closeBtn = toast.querySelector('.toast-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    toast.classList.remove('show');
                    toast.classList.add('hide');
                    toast.addEventListener('transitionend', () => toast.remove(), { once: true });
                });
            }
        }
    });
});


function showToast(type, message) {
    const existing = document.getElementById(`toast-${type}`);
    if (existing) existing.remove();
    const icon = type === 'success'
        ? '<i class="fas fa-check-circle me-2"></i>'
        : '<i class="fas fa-times-circle me-2  error-icon"></i>';

    const toast = document.createElement('div');
    toast.id = `toast-${type}`;
    toast.className = `toast-notification toast-${type}`;
    toast.role = 'alert';
    toast.innerHTML = `
        <div class="toast-content">
            ${icon}
            <span class="${type}-message">${message}</span>
        </div>
        <button type="button" class="toast-close">
            <i class="fas fa-times"></i>
        </button>
    `;

    document.body.appendChild(toast);

    setTimeout(() => toast.classList.add('show'), 100);
    setTimeout(() => {
        toast.classList.remove('show');
        toast.classList.add('hide');
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    }, 2500);

    const closeBtn = toast.querySelector('.toast-close');
    closeBtn.addEventListener('click', () => {
        toast.classList.remove('show');
        toast.classList.add('hide');
        toast.addEventListener('transitionend', () => toast.remove(), { once: true });
    });
}

