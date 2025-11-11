class LocationSelector {

    /**
     * @param {object} options - Các tùy chọn khởi tạo
     * @param {string} options.provinceElementSelector - Selector CSS cho Tỉnh/Thành phố (ví dụ: '#province')
     * @param {string} options.wardElementSelector - Selector CSS cho Phường/Xã (ví dụ: '#ward')
     * @param {Array<object>} options.locationData - Dữ liệu Tỉnh/Thành phố và Phường/Xã
     * @param {object} options.translations - Đối tượng chứa các chuỗi dịch
     * @param {string} [options.mode='new'] - Chế độ ('new' hoặc 'edit')
     * @param {object} [options.originalData={}] - Dữ liệu gốc để tải ở chế độ 'edit'
     */
    constructor(options) {
        this.provinceEl = document.querySelector(options.provinceElementSelector);
        this.wardEl = document.querySelector(options.wardElementSelector);

        if (!this.provinceEl || !this.wardEl) {
            console.error('Không tìm thấy thành phần Tỉnh/Thành phố hoặc Phường/Xã.');
            return;
        }

        this.locationData = options.locationData || [];
        this.translations = options.translations || {};
        this.mode = options.mode || 'new';
        this.originalData = options.originalData || {};

        this.provinceChoices = null;
        this.wardChoices = null;

        // Lưu trữ các hàm xử lý sự kiện đã được bind
        this.boundHandleProvinceChange = this.handleProvinceChange.bind(this);
        this.boundHandleWardChange = this.handleWardChange.bind(this);
        this.boundCleanupLocalStorage = this.cleanupLocalStorage.bind(this);

        this.initChoices();
        this.setupEventListeners();
        this.loadSavedSelections();
    }

    // Lấy chuỗi dịch đã được định dạng
    _t(key, fallback, replacements = {}) {
        let text = (this.translations && this.translations[key] !== undefined)
            ? this.translations[key]
            : fallback;

        if (!text) return '';

        for (const placeholder in replacements) {
            text = text.replace(new RegExp(placeholder, 'g'), replacements[placeholder]);
        }
        return text;
    }

    // Khởi tạo các đối tượng Choices.js
    initChoices() {
        const provinceLabel = this._t('province', 'Tỉnh/Thành phố');

        this.provinceChoices = new Choices(this.provinceEl, {
            searchEnabled: true,
            noResultsText: this._t('noDataFoundMessage', 'Không tìm thấy Tỉnh/Thành phố'),
            itemSelectText: this._t('pressToSelect', 'Nhấn để chọn'),
            allowHTML: false
        });

        this.wardChoices = new Choices(this.wardEl, {
            searchEnabled: true,
            noResultsText: this._t('noDataFoundMessage', 'Không tìm thấy Phường/Xã'),
            itemSelectText: this._t('pressToSelect', 'Nhấn để chọn'),
            noChoicesText: this._t('please_select', 'Vui lòng chọn :item', { ':item': provinceLabel }),
            placeholderValue: this._t('please_select', 'Vui lòng chọn :item', { ':item': provinceLabel }),
            allowHTML: false
        });
    }


    updateWards(provinceCode, savedWardCode = null) {
        if (!provinceCode) {
            this.wardChoices.clearStore();
            this.wardChoices.clearInput();
            return;
        }

        const selectedProvince = this.locationData.find(unit => unit.province_code === provinceCode);
        const wardLabel = this._t('ward', 'Phường/Xã');

        if (selectedProvince && selectedProvince.wards && selectedProvince.wards.length > 0) {
            const wardOptions = [{
                value: '',
                label: this._t('select_item', 'Chọn :item', { ':item': wardLabel }),
                selected: false, // Sẽ được xử lý bởi setValue
            }].concat(selectedProvince.wards.map(ward => ({
                value: ward.ward_code,
                label: ward.name,
                selected: savedWardCode === ward.ward_code
            })));

            this.wardChoices.clearStore();
            this.wardChoices.setChoices(wardOptions, 'value', 'label', true);
            this.wardChoices.enable();

            // Sử dụng setTimeout để đảm bảo Choices.js đã xử lý xong setChoices
            setTimeout(() => {
                if (savedWardCode) {
                    this.wardChoices.setChoiceByValue(savedWardCode);
                }
            }, 0);

        } else {
            this.wardChoices.clearStore();
            this.wardChoices.disable();
        }
    }

    loadSavedSelections() {
        const savedProvince = this.mode === 'edit' ? this.originalData.province : localStorage.getItem('selected_province');
        const savedWard = this.mode === 'edit' ? this.originalData.ward : localStorage.getItem('selected_ward');

        if (savedProvince) {
            localStorage.setItem('selected_province', savedProvince);
            
            this.provinceChoices.setChoiceByValue(savedProvince);
            
            this.updateWards(savedProvince, savedWard);

            if (savedWard) {
                localStorage.setItem('selected_ward', savedWard);
            }
        } else {
            this.updateWards(null);
        }
    }

    setupEventListeners() {
        this.provinceEl.addEventListener('change', this.boundHandleProvinceChange);
        this.wardEl.addEventListener('change', this.boundHandleWardChange);
        window.addEventListener('beforeunload', this.boundCleanupLocalStorage);
    }

    handleProvinceChange(event) {
        const selectedProvinceCode = event.target.value;
        localStorage.setItem('selected_province', selectedProvinceCode);
        localStorage.removeItem('selected_ward');
        this.updateWards(selectedProvinceCode, null);
    }

    handleWardChange(event) {
        const selectedWardCode = event.target.value;
        if (selectedWardCode) {
            localStorage.setItem('selected_ward', selectedWardCode);
        } else {
            // Xóa khỏi localStorage nếu người dùng chọn placeholder
            localStorage.removeItem('selected_ward');
        }
    }

    cleanupLocalStorage() {
        localStorage.removeItem('selected_province');
        localStorage.removeItem('selected_ward');
    }

    destroy() {
        // Hủy các sự kiện
        this.provinceEl.removeEventListener('change', this.boundHandleProvinceChange);
        this.wardEl.removeEventListener('change', this.boundHandleWardChange);
        window.removeEventListener('beforeunload', this.boundCleanupLocalStorage);

        // Hủy đối tượng Choices
        if (this.provinceChoices) {
            this.provinceChoices.destroy();
        }
        if (this.wardChoices) {
            this.wardChoices.destroy();
        }

        // Xóa tham chiếu
        this.provinceEl = null;
        this.wardEl = null;
        this.locationData = null;
        this.translations = null;
    }
}