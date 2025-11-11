import validate from '../common/validateForm.js';

$(function () {
    new validate('createTenantForm', tenantIndexUrl);
    new validate('editTenantForm', tenantIndexUrl);

    let globalLocationSelector = null;
    if (typeof LocationSelector !== 'undefined' && typeof locationDataFromPHP !== 'undefined') {
        const mode = $('#editTenantForm').length > 0 ? 'edit' : 'new';
        const origData = typeof originalData !== 'undefined' ? originalData : {};

        globalLocationSelector = new LocationSelector({
            provinceElementSelector: '#province',
            wardElementSelector: '#ward',
            locationData: locationDataFromPHP,
            translations: typeof translationsBetter !== 'undefined' ? translationsBetter : {},
            mode: mode,
            originalData: origData
        });
    }
    let croppieInstance = null;
    let activeFormType = 'create';

    $(document).on('change', '#tenancy_logo', function () {
        const file = this.files[0];
        if (!file) return;
        activeFormType = $(this).closest('form').attr('id') === 'createTenantForm' ? 'create' : 'edit';
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#cropModal').modal('show');

            $('#cropModal').on('shown.bs.modal', function () {
                if (croppieInstance) {
                    croppieInstance.croppie('destroy');
                }
                croppieInstance = $('#cropperArea').croppie({
                    viewport: {
                        width: 200,
                        height: 200,
                        type: 'circle'
                    },
                    boundary: {
                        width: 300,
                        height: 300
                    },
                    enableExif: true,
                    enableZoom: true,
                    showZoomer: true
                });
                croppieInstance.croppie('bind', {
                    url: e.target.result
                });
            });
        };
        reader.readAsDataURL(file);
    });
    $('#cropImageBtn').on('click', function () {
        if (!croppieInstance) return;

        croppieInstance.croppie('result', {
            type: 'base64',
            size: 'original',
            format: 'png',
            quality: 1
        }).then(function (base64) {
            $('#logoPreview').hide();
            const previewHtml = `
                <div class="mt-2 fade-in">
                    <div class="d-flex align-items-start gap-2">
                        <img src="${base64}"
                             style="
                                width: 100px;
                                height: 100px;
                                object-fit: cover;
                                border-radius: 50%;
                                border: 2px solid #28a745;
                                padding: 3px;
                                background: #fff;">
                        <button type="button" class="btn btn-sm btn-outline-danger remove-cropped-image" title="Xoá ảnh">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#logo_preview').html(previewHtml);
            $('.remove-cropped-image').off('click').on('click', function () {
                removeCroppedImage();
            });
            const hiddenInputName = 'cropped_logo';
            let hiddenInput = $(`[name="${hiddenInputName}"]`);
            if (!hiddenInput.length) {
                hiddenInput = $('<input>').attr({
                    type: 'hidden',
                    name: hiddenInputName
                }).appendTo(`#${activeFormType === 'create' ? 'createTenantForm' : 'editTenantForm'}`);
            }
            hiddenInput.val(base64);
            $('#cropModal').modal('hide');
        });
    });
    $('#cancelCrop').on('click', function () {
        handleCroppieCancel();
    });
    $('#cropModal').on('hidden.bs.modal', function () {
        if (croppieInstance) {
            croppieInstance.croppie('destroy');
            croppieInstance = null;
        }
        $('#cropperArea').empty();
    });
    function handleCroppieCancel() {
        $('#logoPreview').hide();
        $('input[name="cropped_logo"]').remove();
        $('#tenancy_logo').val('');
        const existingLogo = $('#tenancy_logo').attr('data-existing-logo');
        if (existingLogo) {
            displayExistingLogo(existingLogo);
        } else {
            $('#logo_preview').empty();
        }

        $('#cropModal').modal('hide');
    }

    function removeCroppedImage() {
        $('input[name="cropped_logo"]').remove();
        $('#tenancy_logo').val('');
        const existingLogo = $('#tenancy_logo').attr('data-existing-logo');
        if (existingLogo) {
            displayExistingLogo(existingLogo);
        } else {
            $('#logo_preview').empty();
        }
    }

    function displayExistingLogo(logoUrl) {
        if (!logoUrl) {
            $('#logo_preview').empty();
            return;
        }

        $('#logo_preview').html(`
            <div class="mt-2 fade-in d-inline-block position-relative">
                <img src="${logoUrl}"
                    alt="Logo hiện tại"
                    style="
                        width: 100px;
                        height: 100px;
                        object-fit: cover;
                        border-radius: 50%;
                        border: 2px solid #007bff;
                        padding: 3px;
                        background: #fff;
                    ">
                <div class="mt-1 text-center">
                    <small class="text-muted"><i class="fas fa-image me-1"></i>Logo hiện tại</small>
                </div>
            </div>
        `);
    }

    const dbConnectionChoices = new Choices('select[name="tenancy_db_connection"]', {
        itemSelectText: 'Nhấn để chọn',
        allowHTML: false,
        placeholderValue: 'Chọn kết nối cơ sở dữ liệu',
        shouldSort: false,
    });

    const groupChoices = new Choices('select[name="tenancy_group"]', {
        searchEnabled: true,
        allowHTML: false,
        placeholderValue: 'Chọn nhóm cửa hiệu',
        noResultsText: 'Không tìm thấy nhóm cửa hiệu',
        itemSelectText: 'Nhấn để chọn',
        shouldSort: false,
    });

    const adminTenantChoices = new Choices('select[name="tenancy_admin"]', {
        searchEnabled: true,
        allowHTML: false,
        placeholderValue: 'Chọn tài khoản quản trị',
        noResultsText: 'Không tìm thấy tài khoản quản trị',
        itemSelectText: 'Nhấn để chọn',
        shouldSort: false,
    });

    let copyTenantChoices = null;
    let isCopyEnabled = false;
    let currentSelectedTenantId = null;

    const initialAdminId = $('#tenancy_admin').val();
    if (initialAdminId) {
        loadTenantsByAdmin(initialAdminId);
    }

    const existingLogo = $('#tenancy_logo').attr('data-existing-logo');
    if (existingLogo) {
        displayExistingLogo(existingLogo);
    }

    $('.choices__inner').each(function () {
        $(this).css({
            height: '37.45px',
            minHeight: '37.45px',
            display: 'flex',
            alignItems: 'center',
            fontSize: '16px',
            fontWeight: '400',
        });
    });

    $('#enable_copy_info').on('change', function () {
        isCopyEnabled = $(this).is(':checked');

        if (!isCopyEnabled) {
            resetCopiedInfo();
            if (copyTenantChoices) {
                copyTenantChoices.disable();
            }
        } else {
            if (copyTenantChoices) {
                copyTenantChoices.enable();
                if (currentSelectedTenantId) {
                    console.log(currentSelectedTenantId);
                    loadTenantInfo(currentSelectedTenantId);
                }
            }
        }
    });

    $('#tenancy_admin').on('change', function () {
        const adminId = $(this).val();
        loadTenantsByAdmin(adminId);
    });

    function loadTenantsByAdmin(adminId) {
        $('#copy_from_box').hide();
        $('#enable_copy_info').prop('checked', false);
        isCopyEnabled = false;
        currentSelectedTenantId = null;
        if (copyTenantChoices) {
            copyTenantChoices.destroy();
            copyTenantChoices = null;
        }
        $('#copy_tenant_select').empty().append('<option value="">-- Chọn cửa hiệu để sao chép thông tin --</option>');
        resetCopiedInfo();

        if (!adminId) return;

        $.ajax({
            url: `/admin/tenant/by-admin/${adminId}`,
            method: 'GET',
            beforeSend: spinnerControl.show,
            success: function (res) {
                spinnerControl.hide();

                if (!res.success || !res.data || res.data.length === 0) {
                    return;
                }

                const tenants = res.data;
                tenants.forEach(t => {
                    const displayName = t.name || 'Cửa hiệu chưa đặt tên';
                    const address = t.address ? ` - ${t.address}` : '';
                    $('#copy_tenant_select').append(
                        `<option value="${t.id}">${displayName}${address}</option>`
                    );
                });

                $('#copy_from_box').fadeIn();

                copyTenantChoices = new Choices('#copy_tenant_select', {
                    searchEnabled: true,
                    allowHTML: false,
                    placeholderValue: 'Tìm kiếm cửa hiệu...',
                    noResultsText: 'Không tìm thấy cửa hiệu',
                    itemSelectText: 'Nhấn để chọn',
                    shouldSort: false,
                });

                copyTenantChoices.disable();
                setTimeout(() => {
                    $('#copy_tenant_select').closest('.choices').find('.choices__inner').css({
                        height: '37.45px',
                        minHeight: '37.45px',
                        display: 'flex',
                        alignItems: 'center',
                        fontSize: '16px',
                        fontWeight: '400',
                    });
                }, 50);

                $('#copy_tenant_select').on('change', function () {
                    const tenantId = $(this).val();
                    currentSelectedTenantId = tenantId;

                    if (!tenantId) {
                        resetCopiedInfo();
                        return;
                    }

                    if (!isCopyEnabled) {
                        showToast('success', 'Vui lòng chọn "Sao chép thông tin từ cửa hiệu khác" trước khi chọn cửa hiệu');
                        if (copyTenantChoices) {
                            copyTenantChoices.setChoiceByValue('');
                        }
                        currentSelectedTenantId = null;
                        return;
                    }
                    loadTenantInfo(tenantId);
                });
            },
            error: function () {
                spinnerControl.hide();
                showToast('error', 'Không thể tải danh sách cửa hiệu');
            }
        });
    }
    function loadTenantInfo(tenantId) {
        $.ajax({
            url: `/admin/tenant/detail/${tenantId}`,
            method: 'GET',
            beforeSend: spinnerControl.show,
            success: function (res) {
                spinnerControl.hide();

                if (!res.success) {
                    showToast('error', 'Không thể tải thông tin cửa hiệu');
                    return;
                }
                const tenantDetail = res.data;
                const streetAddress = tenantDetail.street ? tenantDetail.street.trim() : '';
                $('#tenancy_address').val(streetAddress);
                $('#tenancy_fb_url').val(tenantDetail.facebook_url || '');
                $('#tenancy_tiktok_url').val(tenantDetail.tiktok_url || '');
                $('#tenancy_ig_url').val(tenantDetail.instagram_url || '');
                $('#copy_from_tenant_id').val(tenantId);
                setTimeout(() => {
                    $('#tenancy_address').trigger('input').trigger('change');
                    $('#tenancy_fb_url').trigger('input').trigger('change');
                    $('#tenancy_tiktok_url').trigger('input').trigger('change');
                    $('#tenancy_ig_url').trigger('input').trigger('change');
                }, 100);
                if (tenantDetail.province && globalLocationSelector) {
                    console.log('📍 Setting location:', tenantDetail.province, tenantDetail.ward);
                    localStorage.setItem('selected_province', tenantDetail.province);
                    if (tenantDetail.ward) {
                        localStorage.setItem('selected_ward', tenantDetail.ward);
                    } else {
                        localStorage.removeItem('selected_ward');
                    }
                    setTimeout(() => {
                        if (globalLocationSelector && globalLocationSelector.provinceChoices) {
                            globalLocationSelector.provinceChoices.setChoiceByValue(tenantDetail.province);
                            setTimeout(() => {
                                globalLocationSelector.updateWards(tenantDetail.province, tenantDetail.ward || null);
                            }, 200);
                        }
                    }, 150);
                }
                if (tenantDetail.street) {
                    $('#tenancy_address').val(tenantDetail.street || '');
                }
                const fields = $('#tenancy_address, #tenancy_fb_url, #tenancy_tiktok_url, #tenancy_ig_url, #province, #ward');
                fields.addClass('highlighted');
                setTimeout(() => {
                    fields.removeClass('highlighted');
                }, 2000);
                if (tenantDetail.logo) {
                    displayCopiedLogo(tenantDetail.logo);
                } else {
                    $('#logo_preview').html(`
                    <div class="mt-2 fade-in">
                        <p class="text-muted mb-0" style="font-size: 0.875rem;">
                            <i class="fas fa-info-circle"></i> Cửa hiệu ${tenantDetail.name} không có logo
                        </p>
                    </div>
                `);
                }
                showToast('success', 'Đã sao chép thông tin thành công!');
            },
            error: function () {
                spinnerControl.hide();
                showToast('error', 'Lỗi khi tải thông tin cửa hiệu');
            }
        });
    }
    function displayCopiedLogo(logoUrl) {
        $('#logo_preview').html(`
            <div class="mt-2 fade-in">
                <div class="d-flex align-items-start gap-2">
                    <img src="${logoUrl}"
                         alt="Logo tham khảo"
                         style="
                            width: 100px;
                            height: 100px;
                            object-fit: cover;
                            border-radius: 50%;
                            border: 2px solid #28a745;
                            padding: 3px;
                            background: #fff;">
                    <button type="button" class="btn btn-sm btn-outline-danger" id="remove_copied_logo" title="Xoá logo">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `);

        $('#remove_copied_logo').off('click').on('click', function () {
            $('#copy_from_tenant_id').val('');
            const existingLogo = $('#tenancy_logo').attr('data-existing-logo');
            if (existingLogo) {
                displayExistingLogo(existingLogo);
            } else {
                $('#logo_preview').empty();
            }
        });
    }
    function resetCopiedInfo() {
        if ($('#createTenantForm').length > 0) {
            $('#tenancy_address').val('');
            $('#tenancy_fb_url').val('');
            $('#tenancy_tiktok_url').val('');
            $('#tenancy_ig_url').val('');
            localStorage.removeItem('selected_province');
            localStorage.removeItem('selected_ward');

            if (globalLocationSelector) {
                setTimeout(() => {
                    if (globalLocationSelector.provinceChoices) {
                        globalLocationSelector.provinceChoices.setChoiceByValue('');
                        globalLocationSelector.updateWards(null, null);
                    }
                }, 100);
            }
        }
        $('#copy_from_tenant_id').val('');
        $('#tenancy_address, #tenancy_fb_url, #tenancy_tiktok_url, #tenancy_ig_url, #province, #ward')
            .removeClass('highlighted');
        const existingLogo = $('#tenancy_logo').attr('data-existing-logo');
        if (existingLogo) {
            displayExistingLogo(existingLogo);
        } else {
            $('#logo_preview').empty();
        }
    }
    function handlePasswordConfirmToggle(passwordSelector, confirmSelector, errorSelector) {
        const $password = $(passwordSelector);
        const $confirm = $(confirmSelector);
        $confirm.prop('disabled', true);
        $password.on('input', function () {
            const val = $(this).val().trim();
            if (val.length > 0) {
                $confirm.prop('disabled', false);
            } else {
                $confirm.val('');
                $confirm.prop('disabled', true);
                $confirm.removeClass('is-valid is-invalid');
                $(errorSelector).text('');
            }
        });
    }

    handlePasswordConfirmToggle('#tenancy_db_password', '#tenancy_db_password_confirm', '#tenancy_db_password_confirm_error');

    const baseDomain = displayDomain.replace(/^\./, '');
    const $input = $("#tenancy_domain");
    const $preview = $("#domainPreview");

    $input.on("input", function () {
        const value = $(this).val().trim();
        if (value) {
            const safeValue = value.replace(/[^a-zA-Z0-9-.]/g, "").toLowerCase();
            $preview.text(`Tên miền dự kiến: ${safeValue}.${baseDomain}`);
        } else {
            $preview.text("");
        }
    });
    const initialValue = $input.val().trim();
    if (initialValue) {
        const safeValue = initialValue.replace(/[^a-zA-Z0-9-.]/g, "").toLowerCase();
        $preview.text(`Tên miền hiện tại: ${safeValue}.${baseDomain}`);
    }
});
