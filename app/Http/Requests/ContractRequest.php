<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'plan_price' => $this->plan_price ? str_replace(',', '', $this->plan_price) : null,
            'vat_price' => $this->vat_price ? str_replace(',', '', $this->vat_price) : null,
            'amount_after_tax' => $this->amount_after_tax ? str_replace(',', '', $this->amount_after_tax) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'tenant_id'        => 'required|exists:t_tenants,id',
            'payment_mode'     => 'required|in:1,2,3,4',
            'plan_id'          => 'required|exists:m_plans,id',
            'plan_price'       => 'required|decimal:0,2',
            'vat_price'        => 'required|decimal:0,2',
            'amount_after_tax' => 'required|decimal:0,2',
            'tax_id'        => 'required|exists:m_taxes,id',
            'start_at'         => 'required|date_format:Y-m-d',
            'end_at'           => 'required|date_format:Y-m-d',
            'due_date'         => 'required|date_format:Y-m-d',
        ];
    }

    public function messages(): array
    {
        return [
            'required'    => ':attribute không được để trống.',
            'exists'      => ':attribute không tồn tại trên hệ thống.',
            'in'          => ':attribute đã chọn không hợp lệ.',
            'date_format' => ':attribute không đúng định dạng ngày tháng.',
            'decimal'     => ':attribute phải là số và có tối đa 2 chữ số thập phân.',
        ];
    }

    public function attributes(): array
    {
        return [
            'tenant_id'        => 'Cửa hiệu',
            'payment_mode'     => 'Hình thức thanh toán',
            'plan_id'          => 'Gói dịch vụ',
            'plan_price'       => 'Giá gói',
            'vat_price'        => 'Thuế VAT',
            'amount_after_tax' => 'Tổng tiền sau thuế',
            'tax_id'           => 'Mã thuế',
            'start_at'         => 'Ngày bắt đầu',
            'end_at'           => 'Ngày kết thúc',
            'due_date'         => 'Ngày đến hạn',
        ];
    }
}
