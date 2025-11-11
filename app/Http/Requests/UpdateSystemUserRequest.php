<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSystemUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('password') && !empty($this->password)) {
            $this->merge([
                'password' => trim($this->password),
            ]);
        }
        if ($this->has('confirm_password') && !empty($this->confirm_password)) {
            $this->merge([
                'confirm_password' => trim($this->confirm_password),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('system_user') ? $this->route('system_user')->id : null;
        $maxFileSize = intval(env('LIMIT_SIZE_FILE')) > 0 ? intval(env('LIMIT_SIZE_FILE')) : 2048;
        return [
            'display_name' => 'required|string|max:255',
            'email' => 'required|email|unique:system_users,email,' . $userId,
            'username' => 'required|string|min:6|max:50|unique:system_users,username,' . $userId,
            'is_active' => 'required|boolean|in:0,1',
            'password' => 'nullable|min:8|regex:/^(?!.*\s)(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[^\s]+$/',
            'confirm_password' => 'required_with:password|same:password',
            'avatar_url' => "nullable|image|mimes:jpeg,png,jpg,gif,webp|max:{$maxFileSize}",
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'Email không hợp lệ',
            'password.regex' => 'Mật khẩu phải bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt, không được chứa khoảng trắng',
            'username.required' => 'Vui lòng nhập tên tài khoản',
            'display_name.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'username.unique' => 'Tên tài khoản đã tồn tại',
            'email.unique' => 'Email đã được sử dụng',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'confirm_password.same' => 'Mật khẩu xác nhận không khớp',
            'avatar_url.image' => 'Tệp tải lên phải là ảnh',
            'avatar_url.mimes' => 'Tệp tải lên phải là jpeg, png, jpg, gif, webp',
            'avatar_url.max' => 'Tệp ảnh tải không vượt quá 2MB.',
            'avatar_url' => 'Ảnh tải lên không đúng định dạng hoặc vượt quá 2MB',
            'is_active.required' => 'Vui lòng nhập trạng thái tài khoản',
            'confirm_password.required_with' => 'Vui lòng nhập mật khẩu xác nhận',
        ];
    }
}
