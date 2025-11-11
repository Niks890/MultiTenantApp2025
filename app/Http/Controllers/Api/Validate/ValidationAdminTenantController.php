<?php

namespace App\Http\Controllers\Api\Validate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidationAdminTenantController extends Controller
{
    public function checkUsername(Request $request)
    {
        $id_to_ignore = $request->input('id');

        $unique_rule = 'unique:m_admin_tenants,username';

        if ($id_to_ignore) {
            $unique_rule .= ',' . $id_to_ignore;
        }

        $rules = [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:100',
                $unique_rule,
            ],
        ];

        $messages = [
            'username.required' => 'Vui lòng nhập tên tài khoản.',
            'username.min' => 'Tên tài khoản phải có ít nhất :min ký tự.',
            'username.max' => 'Tên tài khoản có tối đa :max ký tự.',
            'username.unique' => 'Tên tài khoản này đã tồn tại trong hệ thống.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Tên tài khoản hợp lệ.',
        ], 200);
    }

    public function checkEmail(Request $request)
    {
        $id_to_ignore = $request->input('id');

        $unique_rule = 'unique:m_admin_tenants,email';

        if ($id_to_ignore) {
            $unique_rule .= ',' . $id_to_ignore;
        }
        $rules = [
            'email' => [
                'email',
                'max:254',
                $unique_rule,
            ],
        ];

        $messages = [
            'email.email' => 'Định dạng email không hợp lệ.',
            'email.max' => 'Email có tối đa :max ký tự.',
            'email.unique' => 'Email này đã tồn tại trong hệ thống.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Email hợp lệ.',
        ], 200);
    }

    public function checkPhone(Request $request)
    {
        $id_to_ignore = $request->input('id');

        $unique_rule = 'unique:m_admin_tenants,phone_number';

        if ($id_to_ignore) {
            $unique_rule .= ',' . $id_to_ignore;
        }

        $rules = [
            'phone' => [
                'required',
                'digits_between:10,11',
                'regex:/^0\d{9,10}$/',
                $unique_rule,
            ],
        ];

        $messages = [
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.digits_between' => 'Số điện thoại phải có từ :min đến :max chữ số.',
            'phone.regex' => 'Định dạng số điện thoại không hợp lệ.',
            'phone.unique' => 'Số điện thoại này đã tồn tại trong hệ thống.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Số điện thoại hợp lệ.',
        ], 200);
    }
}
