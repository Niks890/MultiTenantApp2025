<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.index');
        }
        return view('admin.auth.login');
    }

    public function logout()
    {
        $this->authService->logout('admin');
        return redirect()->route('admin.login');
    }

    public function postLogin(LoginRequest $request)
    {

        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.index')
                ->with('success', 'Bạn đang đăng nhập với tên người dùng ' . Auth::guard('admin')->user()->username);
        }
        $success = $this->authService->login(
            $request->username,
            $request->password,
            'admin',
            $request->boolean('remember')
        );
        if ($success) {
            return redirect()->intended('admin')
                ->with('success', 'Đăng nhập thành công!');
        }
        return back()->withErrors([
            'login_error' => 'Thông tin đăng nhập không đúng hoặc tài khoản bị khóa. Vui lòng thử lại.',
        ])->withInput($request->only('username'));
    }
}
