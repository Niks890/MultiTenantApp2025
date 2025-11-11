<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckActiveUser
{
    public function handle(Request $request, Closure $next)
    {
        $systemUser = Auth::user();

        if (!$systemUser) {
            return $next($request);
        }

        // Kiểm tra user bị xóa hoặc khóa
        if ($systemUser->delete_flg == 1 || $systemUser->is_active == 0) {
            return $this->forceLogout($request, 'Tài khoản của bạn đã bị vô hiệu hóa!');
        }

        // Kiểm tra password đã thay đổi sau khi login
        $loginTime = session('login_time');
        if ($loginTime && $systemUser->password_changed_at) {
            // So sánh thời gian login với thời gian đổi password
            if ($systemUser->password_changed_at->isAfter($loginTime)) {
                return $this->forceLogout(
                    $request,
                    'Mật khẩu đã được thay đổi. Hãy đăng nhập lại!'
                );
            }
        }

        return $next($request);
    }

    private function forceLogout(Request $request, string $message)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('error', $message);
    }
}
