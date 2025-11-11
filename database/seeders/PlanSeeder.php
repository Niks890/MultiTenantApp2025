<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        for ($i = 0; $i < 5; $i++) {
            Plan::create([
                'name' => $faker->word . ' Plan',
                'description' => $faker->sentence,
                'price' => $faker->randomFloat(2, 1000, 999999999999999),
                'cycle' => $faker->randomElement(['weekly', 'monthly', 'yearly']),
                'is_active' => $faker->boolean(80),
                'delete_flg' => false,
            ]);
        }
    }
}
