import validate from '../common/validateForm.js';

export default class TaxesForm {
    constructor(formId, redirectRoute, mode = 'create', originalData = {}) {
        new validate(formId, redirectRoute);
        this.mode = mode;
        this.originalData = originalData;
        this.inputElement = $('#rate')[0];
        this.autoNumericInstance = null;
        this.init();
    }

    init() {
        this.initPlugins();
    }

    initPlugins() {
        if (this.inputElement) {
            this.autoNumericInstance = new AutoNumeric(this.inputElement, {
                digitGroupSeparator: ',',
                decimalCharacter: '.',
                decimalPlaces: 0,
                minimumValue: '0',
                maximumValue: '100',
                modifyValueOnWheel: false
            });
        }
    }
}