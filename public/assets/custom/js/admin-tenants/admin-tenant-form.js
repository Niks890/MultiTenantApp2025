import validate from '../common/validateForm.js';

export default class AdminTenantForm {
    constructor(formId, redirectRoute, dataCountry, mode = 'create', originalData = {}) {
        new validate(formId, redirectRoute);
        this.dataCountry = dataCountry;
        this.wardChoices = null;
        this.flatpickrInstance = null;
        this.mode = mode;
        this.originalData = originalData;
        this.init();
    }

    init() {
        this.initPlugins();
        this.loadSavedSelections();
        this.handleEvents();
    }

    initPlugins() {
        // Flatpickr
        this.flatpickrInstance = flatpickr("#birthday-display", {
            dateFormat: "d/m/Y",
            maxDate: "today",
            locale: "vn",
            onChange: (selectedDates) => {
                const hiddenInput = $('#birthday');
                if (selectedDates.length > 0) {
                    const selectedDate = selectedDates[0];
                    const year = selectedDate.getFullYear();
                    const month = String(selectedDate.getMonth() + 1).padStart(2, '0');
                    const day = String(selectedDate.getDate()).padStart(2, '0');
                    hiddenInput.val(`${year}-${month}-${day}`);
                } else {
                    hiddenInput.val('');
                }
            }
        });

        $('#birthday-btn').on('click', () => {
            this.flatpickrInstance.open();
        });

        const provinceChoices = new Choices('#province', {
            searchEnabled: true,
            noResultsText: window.translationsVi.noDataFoundMessage || 'Không tìm thấy Tỉnh/Thành phố',
            itemSelectText: window.translationsVi.pressToSelect || 'Nhấn để chọn',
            allowHTML: false
        });

        this.wardChoices = new Choices('#ward', {
            searchEnabled: true,
            noResultsText: window.translationsVi.noDataFoundMessage || 'Không tìm thấy Phường/Xã',
            itemSelectText: window.translationsVi.pressToSelect || 'Nhấn để chọn',
            noChoicesText: window.translationsVi.please_select.replace(':item', window.translationsVi.province || 'Tỉnh/Thành phố') || 'Vui lòng chọn Tỉnh/Thành phố',
            placeholderValue: window.translationsVi.please_select.replace(':item', window.translationsVi.province || 'Tỉnh/Thành phố') || 'Vui lòng chọn Tỉnh/Thành phố',
            allowHTML: false
        });
    }


    loadSavedSelections() {
        const savedProvince = this.mode === 'edit' ? this.originalData.province : localStorage.getItem('selected_province');
        const savedWard = this.mode === 'edit' ? this.originalData.ward : localStorage.getItem('selected_ward');

        if (savedProvince) {
            localStorage.setItem('selected_province', savedProvince);
            this.updateWards(savedProvince, savedWard); 
            if (savedWard) {
                localStorage.setItem('selected_ward', savedWard);
            }
        } else {
            this.updateWards(null);
        }
    }
    

    handleEvents() {

        $('#province').on('change', (event) => {
            const selectedProvinceCode = event.target.value;
            localStorage.setItem('selected_province', selectedProvinceCode);
            localStorage.removeItem('selected_ward');
            this.updateWards(selectedProvinceCode, null); 
        });
    
        $('#ward').on('change', (event) => {
            const selectedWardCode = event.target.value;
            localStorage.setItem('selected_ward', selectedWardCode);
        });

        $('#password').on('input', () => {
            const password = $('#password').val();
            const confirmationInput = $('#password-confirmation');
            if (!password) {
                confirmationInput.prop('disabled', true);
                confirmationInput.siblings('button').prop('disabled', true);
                confirmationInput.val('');
            } else {
                confirmationInput.prop('disabled', false);
                confirmationInput.siblings('button').prop('disabled', false);
            }
        });

        window.addEventListener('beforeunload', () => {
            localStorage.removeItem('selected_province');
            localStorage.removeItem('selected_ward');
        });
    }

    updateWards(provinceCode, savedWardCode = null) {
        if (!provinceCode) {
            this.wardChoices.clearStore();
            return;
        }

        const selectedProvince = this.dataCountry.find(unit => unit.province_code === provinceCode);

        if (selectedProvince && selectedProvince.wards) {
            this.wardChoices.clearStore();
            
            const wardOptions = [{
                value: '',
                label: window.translationsVi.select_item.replace(':item', window.translationsVi.ward || 'Phường/Xã') || 'Chọn Phường/Xã',
                selected: !savedWardCode,
                placeholderValue: window.translationsVi.select_item.replace(':item', window.translationsVi.ward || 'Phường/Xã') || 'Chọn Phường/Xã',
            }].concat(selectedProvince.wards.map(ward => ({
                value: ward.ward_code,
                label: ward.name,
                selected: savedWardCode === ward.ward_code
            })));

            this.wardChoices.setChoices(wardOptions, 'value', 'label', true);
        } else {
            this.wardChoices.clearStore();
        }
    }
    
}