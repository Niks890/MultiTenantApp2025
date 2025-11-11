<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Tạo 3 nhóm ngẫu nhiên
        for ($i = 0; $i < 3; $i++) {
            Group::create([
                'name' => $faker->word . ' Group',
                'description' => $faker->sentence,
                'delete_flg' => false,
            ]);
        }
    }
}
