class validate {
    constructor(formId, routeIndex, callback) {
        const toaster = new ToastNotification();
        $(function () {
            $("#" + formId).on("submit", function (e) {
                e.preventDefault();
                $("button").prop("disabled", true);
                spinnerControl.show();
                let formData = new FormData(this);
                const url = $(this).attr('action');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                })
                    .done(data => {
                        $('button').prop("disable", false);
                        if (data.status == true) {
                            if (routeIndex != null) {
                                window.location.href = routeIndex;
                            } else {
                                callback(data);
                                toaster.success(data.message);
                            }
                        } else {
                            toaster.error(data.message);
                            resetValidate();
                        }
                    })
                    .fail(data => {
                        $('button').prop("disabled", false);
                        resetValidate();
                        if (data.status == 422) {
                            const errorFields = Object.keys(data.responseJSON.errors);
                            const requiredFields = data.responseJSON.required_fields || [];

                            $.each(data.responseJSON.errors, function (key, error) {
                                showValidationError(key, error);
                            });

                            requiredFields.forEach(function (fieldName) {
                                if (!errorFields.includes(fieldName)) {
                                    const field = $("#" + formId).find('[id="' + fieldName + '"]');

                                    if (field.length) {
                                        if (field.hasClass('dropify')) {
                                            field.parents('.dropify-wrapper').addClass("is-valid");
                                        } else if (field.hasClass('select2')) {
                                            field.next().addClass("is-valid");
                                        } else if (field.hasClass('choices')) {
                                            field.next('.choices').addClass("is-valid");
                                        } else {
                                            field.addClass("is-valid");
                                        }

                                        let successField = field.closest('.input-group');
                                        if (!successField.length) {
                                            successField = field;
                                        }
                                        successField.find('button').addClass('is-valid');
                                    }
                                }
                            });

                            return;
                        }
                        else if (data.status == 500) {
                            let message = "";
                            if (data.responseJSON && data.responseJSON.message) {
                                message = data.responseJSON.message;
                            } else if (data.responseText) {
                                const html = data.responseText;
                                const match = html.match(/<title>(.*?)<\/title>/i);
                                if (match && match[1]) {
                                    message = match[1];
                                } else {
                                    message = html.substring(0, 200);
                                }
                            }
                            console.error("Lỗi", data);
                        }
                        else if (!data.status && data.statusText == 'error') {
                            const message = window.translationsVi.fileTooLargeMessage || "Tệp tải lên quá lớn. Vui lòng thử lại với tệp nhỏ hơn.";
                            toaster.error(message);
                        }
                    })
                    .always(() => {
                        spinnerControl.hide();
                    })
            });
        });

        function resetValidate() {
            $(".invalid-feedback").remove();
            $(".is-invalid").removeClass("is-invalid");
            $(".is-valid").removeClass("is-valid");
            $(".border-danger").removeClass("border-danger");
            $('html, body').animate({ scrollTop: '0px' }, 10);
        }

        function createValidationMessages(arrError) {
            let messages = "";
            if (arrError != null && arrError.length != 0) {
                arrError.forEach((err) => {
                    messages += '<div class="invalid-feedback">' + err + "</div>";
                });
            }
            return messages;
        }

        function showValidationError(key, error) {
            let element = $("textarea[id='" + key + "']");

            if (!element.length) {
                element = $("select[id='" + key + "']");
            }

            if (!element.length) {
                // input (m-tel, multi-errors, input simple)
                element = $("input[id='" + key + "']");
                if (!element.length) {
                    element = $("input[id^='" + key + "']").first();
                }
            }

            if (!element.length) {
                return;
            }

            let visualElement = element;
            let errorMessage = '';

            // element textarea
            if (element.is('textarea')) {
                errorMessage = '<div class="invalid-feedback d-block">' + error[0] + '</div>';

            } else if (element.is('input') && element.hasClass('dropify')) {
                visualElement = $("input[id='" + key + "']").parents('.dropify-wrapper');
                errorMessage = '<div class="invalid-feedback d-block">' + error[0] + '</div>';

                // element select and class select2
            } else if (element.is('select') && element.hasClass('select2')) {
                visualElement = element.next();
                visualElement.find("[aria-controls='select2-" + key + "-container']").addClass("border-danger");
                errorMessage = '<div class="invalid-feedback d-block">' + error[0] + '</div>';

                // class m-tel
            } else if (element.is('input') && element.hasClass('m-tel')) {
                visualElement = $("input[id^='" + key + "']");
                errorMessage = '<div class="invalid-feedback d-block">' + error[0] + '</div>';

                // class multi-errors
            } else if (element.is('input') && element.hasClass('multi-errors')) {
                errorMessage = createValidationMessages(error);

                // class select-simple
            } else if (element.is('select') && element.hasClass('select-simple')) {
                errorMessage = '<div class="invalid-feedback d-block">' + error[0] + '</div>';

            // element select other
            } else if (element.is('select')) {
                visualElement = $("select[name='" + key + "']").parent();
                errorMessage = '<div class="invalid-feedback d-block">' + error[0] + '</div>';

            // element input simple
            } else {
                errorMessage = '<div class="invalid-feedback text-start d-block">' + error[0] + '</div>';
            }

            visualElement.addClass('is-invalid');

            let errorContainer = element.closest('.form-group');
            if (!errorContainer.length) {
                errorContainer = element;
                errorContainer.parent().append(errorMessage);
            } else {
                errorContainer.find('.row').append('<div class="col-md-10 offset-md-2">' + errorMessage + '</div>');
            }
            errorContainer.find('button').addClass('is-invalid');

        }
    }
}

export default validate;

// Example usage:
// import validate from 'public/assets/custom/js/common/validateForm.js';


// For form validation with redirect:
// new validate('formId', 'routeIndex');


// For form validation with custom callback function:
// routeIndex can be set to null if using a callback
// new validate('formId', null, function(responseData) {
//     // Custom callback function on success
// });


// Controller return example:
// return response()->json(['status' => true, 'message' => 'Success message']);
// or
// return response()->json(['status' => false, 'message' => 'Error message']);


// Example html form:
// <form id="formId" action="/your-endpoint(routeIndex)" method="POST" enctype="multipart/form-data">
//     @csrf

//     ...

//     Your form fields here:
//     required name attribute and id attribute must match

//     <input type="text" id="inputField" name="inputField" />

//     Or

//     <div class="form-group">
//         <div class="row align-items-center">
//             <div class="col-md-2">
//                 <label for="inputField">Input Field</label>
//             </div>
//             <div class="col-md-10">
//                 <input type="text" id="inputField" name="inputField" class="form-control" />
//             </div>
//         </div>
//     </div>

//     ...

//     <button type="submit">Submit</button>
// </form>
