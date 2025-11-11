class CallApi {
    constructor(config) {
        // config = { url, method, data, headers }
        this.config = config;
        this.toaster = new ToastNotification();
    }


    execute(callbacks = {}, spinnerOptions = true) {
        // callbacks = { onSuccess, onError, onAlways }

        $("button").prop("disabled", true);
        if (spinnerOptions) {
            spinnerControl.show();
        }

        $.ajax({
            url: this.config.url,
            method: this.config.method || 'GET',
            data: this.config.data || null,
            dataType: 'json',
            headers: this.config.headers || {}
        })
        .done(response => {
            if (response.status) {
                if (callbacks.onSuccess) {
                    callbacks.onSuccess(response);
                } else {
                    this.toaster.success(response.message);
                }
            } else {
                this.toaster.error(response.message);
            }
        })
        .fail(xhr => {
            if (callbacks.onError) {
                callbacks.onError(xhr);
            } else {
                const errorMsg = xhr.responseJSON?.message || 'Có lỗi xảy ra. Vui lòng kiểm tra lại.';
                this.toaster.error(errorMsg);
            }
        })
        .always(() => {
            if (callbacks.onAlways) {
                callbacks.onAlways();
            }
            $("button").prop("disabled", false);
            if (spinnerOptions) {
                spinnerControl.hide();
            }
        });
    }
}

export default CallApi;
