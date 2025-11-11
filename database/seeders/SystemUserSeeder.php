<?php

namespace Database\Seeders;

use App\Models\SystemUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $username = 'superadmin';
            $email = 'superadmin@gmail.vn';
            $password = '123456@aB';
            $displayName = 'Super Administrator';

            SystemUser::create([
                'username' => $username,
                'password' => bcrypt($password),
                'display_name' => $displayName,
                'email' => $email,
                'email_verified_at' => now(),
                'is_active' => true,
                'last_login_date' => now(),
                'delete_flg' => false,
                'is_super_admin' => true
            ]);

            $this->command->info('Tài khoản Superadmin đã được tạo thành công.');
        } catch (\Exception $e) {
            $this->command->error('Lỗi khi tạo tài khoản Superadmin: ' . $e->getMessage());
        }
    }
}
