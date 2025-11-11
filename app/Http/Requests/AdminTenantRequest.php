<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminTenantRequest extends FormRequest
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
        $id = $this->route('id');
        $rules = [
            'username' => ['required', 'string', 'min:3', 'max:100', 'unique:m_admin_tenants,username,' . $id],
            'name' => ['required', 'string', 'min:3', 'max:100', 'regex:/^[\p{L}\s\p{Nd}]+$/u'],
            'birthday' => ['nullable', 'date', 'before_or_equal:today'],
            'email' => ['nullable', 'email', 'max:254'],
            'phone' => ['required', 'digits_between:10,11', 'regex:/^0\d{9,10}$/'],
            'province' => ['nullable', 'string'],
            'ward' => ['nullable', 'string'],
            'street' => ['nullable', 'string', 'max:200'],
        ];

        if ($this->isMethod('post')) {
            $rules['password'] = ['required', 'string', 'min:8', 'max:255', 'not_regex:/\s/', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@.#$%^&*()_+\-=\[\]{}|;:,<>?])[A-Za-z\d!@.#$%^&*()_+\-=\[\]{}|;:,<>?]+$/'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8', 'max:255', 'not_regex:/\s/', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@.#$%^&*()_+\-=\[\]{}|;:,<>?])[A-Za-z\d!@.#$%^&*()_+\-=\[\]{}|;:,<>?]+$/'];
            $rules['password-confirmation'] = ['required_with:password', 'same:password'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Vui lòng nhập tên tài khoản.',
            'username.min' => 'Tên tài khoản phải có ít nhất :min ký tự.',
            'username.max' => 'Tên tài khoản có tối đa :max ký tự.',
            'username.unique' => 'Tên tài khoản này đã tồn tại trong hệ thống.',
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.min' => 'Họ tên phải có ít nhất :min ký tự.',
            'name.max' => 'Họ tên không được vượt quá :max ký tự.',
            'name.regex' => 'Họ tên chỉ được chứa chữ cái, chữ số và khoảng trắng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất :min ký tự.',
            'password.max' => 'Mật khẩu có tối đa :max ký tự.',
            'password.regex' => 'Mật khẩu phải bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.',
            'password.not_regex' => 'Mật khẩu không được chứa khoảng trắng.',
            'password-confirmation.required_with' => 'Vui lòng xác nhận mật khẩu.',
            'password-confirmation.same' => 'Mật khẩu xác nhận không khớp.',
            'email.email' => 'Định dạng email không hợp lệ.',
            'email.max' => 'Email có tối đa :max ký tự.',
            'email.unique' => 'Email này đã tồn tại trong hệ thống.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.digits_between' => 'Số điện thoại phải có từ :min đến :max chữ số.',
            'phone.regex' => 'Định dạng số điện thoại không hợp lệ.',
            'phone.unique' => 'Số điện thoại này đã tồn tại trong hệ thống.',
            'birthday.date' => 'Ngày sinh không hợp lệ.',
            'birthday.before_or_equal' => 'Ngày sinh không được lớn hơn ngày hiện tại.',
            'street.max' => 'Địa chỉ có tối đa :max ký tự.',
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
