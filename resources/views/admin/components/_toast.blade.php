@if (Session::has('success'))
    <div id="toast-success" class="toast-notification toast-success" role="alert">
        <div class="toast-content">
            <i class="fas fa-check-circle me-2"></i>
            <span class="success-message">{{ Session::get('success') }}</span>
        </div>
        <button type="button" class="toast-close">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif


@if (Session::has('error'))
    <div id="toast-error" class="toast-notification toast-error" role="alert">
        <div class="toast-content">
            <i class="fas fa-times-circle me-2  error-icon"></i>
            <span class="error-message">{{ Session::get('error') }}</span>
        </div>
        <button type="button" class="toast-close">
            <i class="fas fa-times"></i>
        </button>
    </div>
@endif
