<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class DefaultPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $this->command->info('Đang tạo gói dịch vụ mặc định...');
            Plan::updateOrCreate(
                ['id' => Plan::DEFAULT_FREE_PLAN_ID],
                [
                    'name' => 'Dịch vụ miễn phí',
                    'description' => 'Đây là gói dịch vụ miễn phí mặc định của hệ thống.',
                    'price' => 0,
                    'cycle' => 'monthly',
                ]
            );
            $this->command->info('Gói dịch vụ mặc định đã được tạo thành công.');
        } catch (\Throwable $e) {
            $this->command->error('Đã xảy ra lỗi trong quá trình tạo gói dịch vụ mặc định.');
            $this->command->error('Lỗi: ' . $e->getMessage());
        }
    }
}
