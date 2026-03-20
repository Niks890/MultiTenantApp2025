<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class DefaultPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $this->command->info('Đang tạo phương thức thanh toán mặc định...');
            PaymentMethod::updateOrCreate(
                ['id' => 9999],
                [
                    'name' => 'Tiền mặt',
                    'is_default' => true,
                ]
            );
            $this->command->info('Phương thức thanh toán mặc định đã được tạo thành công.');
        } catch (\Throwable $e) {
            $this->command->error('Đã xảy ra lỗi trong quá trình tạo phương thức thanh toán mặc định.');
            $this->command->error('Lỗi: ' . $e->getMessage());
        }
    }
}
