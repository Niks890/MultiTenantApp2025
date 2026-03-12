<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */


    public function rules(): array
    {
        $id = $this->route('payment_method');

        return [
            'payment_method_name' => [
                'required',
                'max:100',
                Rule::unique('m_payment_methods', 'name')
                    ->ignore($id)
                    ->where(fn($query) => $query->where('delete_flg', 0)),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method_name.required' => 'Vui lòng nhập tên nhóm cửa hiệu',
            'payment_method_name.unique' => 'Tên nhóm cửa hiệu đã tồn tại',
            'payment_method_name.max' => 'Tên nhóm cửa hiệu không được vượt quá 100 ký tự',
        ];
    }
}
