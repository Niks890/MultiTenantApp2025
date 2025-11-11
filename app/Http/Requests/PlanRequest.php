<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PlanRequest extends FormRequest
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
        $id = $this->route('plan');
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                Rule::unique('m_plans')
                    ->where('delete_flg', false)
                    ->ignore($id),
            ],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:255'],
            'cycle' => ['required', 'in:weekly,monthly,yearly'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên gói dịch vụ.',
            'name.min' => 'Tên gói dịch vụ phải có ít nhất :min ký tự.',
            'name.max' => 'Tên gói dịch vụ có tối đa :max ký tự.',
            'name.unique' => 'Tên gói dịch vụ này đã tồn tại trong hệ thống.',
            'price.required' => 'Vui lòng nhập giá gói dịch vụ.',
            'price.numeric' => 'Giá gói dịch vụ phải là một số hợp lệ.',
            'price.min' => 'Giá gói dịch vụ phải lớn hơn hoặc bằng :min.',
            'description.max' => 'Mô tả có tối đa :max ký tự.',
            'cycle.required' => 'Vui lòng chọn chu kỳ gói dịch vụ.',
            'cycle.in' => 'Chu kỳ gói dịch vụ không hợp lệ.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $rules = $this->rules();

        $requiredFields = [];
        foreach ($rules as $field => $fieldRules) {
            $fieldRules = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);

            foreach ($fieldRules as $rule) {
                if ($rule === 'nullable') {
                    continue 2;
                }

                if ($rule === 'required') {
                    $requiredFields[] = $field;
                    break;
                }
            }
        }

        throw new HttpResponseException(
            response()->json([
                'errors' => $validator->errors(),
                'required_fields' => $requiredFields
            ], 422)
        );
    }
}
