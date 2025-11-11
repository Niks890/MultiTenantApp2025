class ToastNotification {
    constructor() {
        this.container = this.createContainer();
    }

    createContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
            document.body.appendChild(container);
        }
        return container;
    }

    show(message, type = 'success', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type} show`;
        toast.style.cssText = 'margin-bottom: 10px; animation: slideInRight 0.3s ease-out;';
        
        const icon = type === 'success' 
            ? '<i class="fas fa-check-circle me-2"></i>' 
            : '<i class="fas fa-times-circle me-2 error-icon"></i>';
        
        toast.innerHTML = `
            <div class="toast-content">
                ${icon}
                <span class="${type}-message">${message}</span>
            </div>
            <button type="button" class="toast-close">
                <i class="fas fa-times"></i>
            </button>
        `;

        this.container.appendChild(toast);

        // Close button handler
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => this.hide(toast));

        // Auto hide
        setTimeout(() => this.hide(toast), duration);

        return toast;
    }

    hide(toast) {
        toast.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => toast.remove(), 300);
    }

    success(message, duration = 3000) {
        return this.show(message, 'success', duration);
    }

    error(message, duration = 3000) {
        return this.show(message, 'error', duration);
    }
}

// Export instance
window.toastNotification = new ToastNotification();