<?php

namespace App\Repositories\Eloquent;

use App\Models\SystemUser as SystemUser;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{

    public function findByLogin(string $username)
    {
        return SystemUser::where('username', $username)->where('delete_flg', 0)->first();
    }

    public function checkCredentials(string $username, string $password, bool $isActive): bool
    {
        $user = $this->findByLogin($username);

        return $user && Hash::check($password, $user->password) && $user->is_active == $isActive;
    }
}
