
(function() {
    const loadingOverlay = document.getElementById('loading-overlay');

    if (!loadingOverlay) {
        console.error('Không tìm thấy phần tử #loading-overlay.');
        return;
    }

    function showSpinner() {
        if (loadingOverlay) {
            loadingOverlay.classList.remove('d-none');
            loadingOverlay.classList.add('d-flex');
        }
    }

    function hideSpinner() {
        if (loadingOverlay) {
            loadingOverlay.classList.add('d-none');
        }
    }

    spinnerControl = {
        show: showSpinner,
        hide: hideSpinner
    };


    const forms = document.querySelectorAll('.show-spinner-on-submit');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                return;
            }
            showSpinner();
        });
    });

})();

// Example usage:
// To show the spinner: spinnerControl.show();
// To hide the spinner: spinnerControl.hide();
// or add the class 'show-spinner-on-submit' to any form to automatically show the spinner on submit.