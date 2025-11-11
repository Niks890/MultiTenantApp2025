import validate from '../common/validateForm.js';
import convertNumberToVietnameseWords  from '../utils/numberToText.js';

export default class PlansForm {
    constructor(formId, redirectRoute, mode = 'create', originalData = {}) {
        new validate(formId, redirectRoute);
        this.mode = mode;
        this.originalData = originalData;
        this.inputElement = $('#price')[0];
        this.textDisplay = $('#price-in-words')[0];
        this.hiddenElement = $('#price-value')[0];
        this.autoNumericInstance = null;
        this.init();
    }

    init() {
        this.handleEvents();
        this.initPlugins();
        this.updatePriceText();
    }

    initPlugins() {
        if (this.inputElement) {
            this.autoNumericInstance = new AutoNumeric(this.inputElement, {
                digitGroupSeparator: ',',
                decimalCharacter: '.',
                decimalPlaces: 0,
                minimumValue: '0',
                maximumValue: '999999999999999',
                modifyValueOnWheel: false
            });
        }
    }

    handleEvents() {
        if (this.inputElement) {
            this.inputElement.addEventListener('input', () => this.updatePriceText());
        }
    }

    updatePriceText() {
        if (!this.inputElement || !this.textDisplay || !this.hiddenElement) {
            return;
        }

        let currentValue = '';

        if (this.autoNumericInstance) {
            currentValue = this.autoNumericInstance.getNumericString();
        } else {
            currentValue = this.inputElement.value.replace(/[^\d]/g, '');
        }

        this.hiddenElement.value = currentValue;

        let text = '';
        if (currentValue === '') {
            text = notEnteredText || 'Chưa nhập';
        } else {
            text = convertNumberToVietnameseWords(currentValue);
        }
        this.textDisplay.textContent = text;
    }
}