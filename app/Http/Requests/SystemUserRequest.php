<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SystemUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation() :void
    {
        $this->merge([
            'password' => trim($this->password),
            'confirm_password' => trim($this->confirm_password),
        ]);
    }

    public function rules(): array
    {
        $maxFileSize = intval(env('LIMIT_SIZE_FILE')) > 0 ? intval(env('LIMIT_SIZE_FILE')) : 2048;
        return [
            'username' => 'required|string|min:6|max:50|unique:system_users,username',
            'password' => 'required|string|min:8|max:100|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).+$/',
            'confirm_password' => 'required_with:password|same:password',
            'display_name' => 'required|string|max:100',
            'email' => 'required|email|unique:system_users,email',
            'avatar_url' => "nullable|image|mimes:jpeg,png,jpg,gif,webp|max:{$maxFileSize}",
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.regex' => 'Mật khẩu phải bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt, không được chứa khoảng trắng',
            'display_name.required' => 'Vui lòng nhập họ tên',
            'username.required' => 'Vui lòng nhập tên tài khoản',
            'username.unique' => 'Tên tài khoản đã tồn tại',
            'email.unique' => 'Email đã được sử dụng',
            'email.required' => 'Vui lòng nhập email',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'confirm_password.same' => 'Mật khẩu xác nhận không khớp',
            'avatar_url.image' => 'Tệp tải lên phải là ảnh.',
            'avatar_url.mimes' => 'Tệp tải lên phải là jpeg, png, jpg, gif, webp',
            'avatar_url.max' => 'Tệp ảnh tải không vượt quá 2MB',
            'avatar_url' => 'Ảnh tải lên không đúng định dạng hoặc vượt quá 2MB',
            'confirm_password.required_with' => 'Vui lòng nhập mật khẩu xác nhận',
        ];
    }
}
