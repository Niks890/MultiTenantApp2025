<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class VietnamUnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = 'data/vietnam_units.json';

        if (!Storage::exists($path)) {
            $this->command->error('File dữ liệu không tồn tại tại: ' . storage_path('app/' . $path));
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            Ward::truncate();
            Province::truncate();

            DB::transaction(function () use ($path) {
                $json = Storage::get($path);
                $data = json_decode($json, true);

                if (is_null($data)) {
                    throw new \Exception('Không thể decode file JSON hoặc file JSON trống.');
                }

                $this->command->info('Bắt đầu nhập dữ liệu...');
                foreach ($data as $provinceData) {
                    $province = Province::create([
                        'province_code' => $provinceData['province_code'],
                        'name'          => $provinceData['name'],
                        'short_name'    => $provinceData['short_name'],
                        'code'          => $provinceData['code'],
                        'place_type'    => $provinceData['place_type'],
                    ]);

                    if (!empty($provinceData['wards'])) {
                        $wards = [];
                        foreach ($provinceData['wards'] as $wardData) {
                            $wards[] = [
                                'ward_code'   => $wardData['ward_code'],
                                'name'        => $wardData['name'],
                                'province_id' => $province->id,
                                'created_at'  => now(),
                                'updated_at'  => now(),
                            ];
                        }
                        Ward::insert($wards);
                    }
                }
            });

            $this->command->info('Đã nhập dữ liệu đơn vị hành chính Việt Nam thành công.');
        } catch (Throwable $e) {
            // Rollback
            $this->command->error('Đã xảy ra lỗi trong quá trình nhập dữ liệu. Dữ liệu chèn vào đã được khôi phục.');
            $this->command->error('Lỗi: ' . $e->getMessage());
            $this->command->error('Tại file: ' . $e->getFile() . ' - Dòng: ' . $e->getLine());
            Log::error($e);
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
