<?php

namespace App\Services;

use App\Models\SystemUser;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthService
{
    protected $authRepo;

    public function __construct(AuthRepositoryInterface $authRepo)
    {
        $this->authRepo = $authRepo;
    }


    public function login(string $username, string $password, string $guard = 'admin', bool $remember = false): bool
    {
        $credentials = [
            'username' => $username,
            'password' => $password,
        ];
        if ($this->authRepo->checkCredentials($username, $password, true)) {
            Auth::guard($guard)->attempt($credentials, $remember);
            session()->regenerate();
            session()->regenerateToken();
            session(['login_time' => now()]);

            Log::channel('system_user')->info('Đăng nhập thành công', [
                'ip' => request()->ip(),
                'route' => '/login',
                'data' => '{' . '"' . Auth::guard($guard)->user()->id . '"' . '}'
            ]);
            return true;
        }
        Log::channel('system_user')->warning('Đăng nhập thất bại', [
            'ip' => request()->ip(),
            'route' => '/login',
            'data' => json_encode(['user' => $username])
        ]);
        return false;
    }

    public function logout(string $guard = 'admin'): void
    {
        $user = Auth::guard($guard)->user();
        Log::channel('system_user')->info('Đăng xuất', [
            'ip' => request()->ip(),
            'route' => '/logout',
            'data' => '{' . '"' . $user->id . '"' . '}'
        ]);
        Auth::guard($guard)->logout();
        session()->invalidate();
        session()->regenerateToken();
        if ($user) {
            SystemUser::where('id', $user->id)->update(['last_login_date' => now()]);
        }
    }
}
