<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TaxRequest extends FormRequest
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
        return [
            'rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'rate.required' => 'Vui lòng nhập thuế suất.',
            'rate.numeric' => 'Thuế suất phải là một số hợp lệ.',
            'rate.min' => 'Thuế suất phải lớn hơn hoặc bằng :min.',
            'rate.max' => 'Thuế suất phải nhỏ hơn hoặc bằng :max.',
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
