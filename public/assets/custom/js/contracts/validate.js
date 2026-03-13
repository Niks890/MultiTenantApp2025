import validate from '../common/validateForm.js';

$(function () {
    new validate('createContractForm', contractsIndexUrl);

    $(document).ready(function () {
        let taxRate = (typeof GLOBAL_TAX_RATE !== 'undefined') ? GLOBAL_TAX_RATE : 10;

        const tenantChoices = new Choices('#tenant_id', { searchEnabled: true, itemSelectText: '', allowHTML: true });
        const planChoices = new Choices('#plan_id', { searchEnabled: true, itemSelectText: '', allowHTML: true });
        const paymentModeChoices = new Choices('#payment_mode', { searchEnabled: false, itemSelectText: '', allowHTML: true });

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


        const startPicker = flatpickr("#start_at", {
            allowInput: true,
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            locale: "vn",
        });
        const readonlyFlatpickrConfig = {
            allowInput: false,
            clickOpens: false,
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            locale: "vn",
        };

        const endPicker = flatpickr("#end_at", readonlyFlatpickrConfig);
        const duePicker = flatpickr("#due_date", readonlyFlatpickrConfig);
        $('#tenant_id').on('change', function () {
            const tenantId = $(this).val();
            const fieldsToReset = [
                '#tenant_name',
                '#admin_tenant_name',
                '#admin_tenant_address',
                '#admin_tenant_phone',
                '#admin_tenant_email',
                '#admin_tenant_date_of_birth'
            ];
            fieldsToReset.forEach(selector => $(selector).val(''));

            if (!tenantId) return;
            $.get(`/admin/tenant/detail/${tenantId}`, function (res) {
                if (res.success) {
                    const d = res.data;
                    $('#tenant_name').val(d.name);
                    $('#admin_tenant_name').val(d.admin_tenant.display_name || '');
                    $('#admin_tenant_address').val(d.address || '');
                    $('#admin_tenant_phone').val(d.admin_tenant.phone_number || '');
                    $('#admin_tenant_email').val(d.admin_tenant.email || '');
                    if (d.admin_tenant.date_of_birth) {
                        const dateStr = d.admin_tenant.date_of_birth;
                        const dateObj = new Date(dateStr);
                        const day = String(dateObj.getDate()).padStart(2, '0');
                        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                        const year = dateObj.getFullYear();

                        $('#admin_tenant_date_of_birth').val(`${day}/${month}/${year}`);
                    }

                    showToast('success', 'Đã tự điền thông tin cửa hiệu');
                }
            });
        });

        $('#plan_id').on('change', function () {
            const selectedData = planChoices.getValue();
            if (selectedData && selectedData.customProperties) {
                let props = selectedData.customProperties;
                if (typeof props === 'string') {
                    try { props = JSON.parse(props); } catch (e) { return; }
                }
                $('#plan_price').val(props.price);
                $('#cycle_plan').data('unit', props.cycle);
                $('#cycle_plan').val(translateCycle(props.cycle));
                calculateMoney();
                calculateDates();
            }
        });

        function translateCycle(cycle) {
            const map = { 'weekly': '1 Tuần', 'monthly': '1 Tháng', 'yearly': '1 Năm' };
            return map[cycle] || cycle;
        }

        function calculateDates() {
            const startDate = startPicker.selectedDates[0];
            const cycleType = $('#cycle_plan').data('unit');

            if (startDate && cycleType) {
                duePicker.setDate(startDate);
                let endDate = new Date(startDate);

                switch (cycleType) {
                    case 'weekly':
                        endDate.setDate(endDate.getDate() + 7);
                        break;
                    case 'monthly':
                        endDate.setMonth(endDate.getMonth() + 1);
                        break;
                    case 'yearly':
                        endDate.setFullYear(endDate.getFullYear() + 1);
                        break;
                    default:
                        let months = parseInt(cycleType) || 1;
                        endDate.setMonth(endDate.getMonth() + months);
                }
                endPicker.setDate(endDate);
            }
        }

        function calculateMoney() {
            let priceRaw = $('#plan_price').val() || "0";
            let cleanPrice = priceRaw.toString().replace(/[^0-9.]/g, "");
            const price = parseFloat(cleanPrice) || 0;
            const vatAmount = price * (taxRate / 100);
            const total = price + vatAmount;

            const formatter = new Intl.NumberFormat('en-US', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });

            $('#plan_price').val(formatter.format(price));
            $('#vat_price').val(formatter.format(vatAmount));
            $('#amount_after_tax').val(formatter.format(total));
        }

        $('#start_at').on('change', calculateDates);
    });
});
