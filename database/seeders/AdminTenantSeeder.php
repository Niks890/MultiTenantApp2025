<?php

namespace Database\Seeders;

use App\Models\AdminTenant;
use App\Models\Group;
use App\Models\Tenant;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('vi_VN');

        $groupIds = [null, ...Group::pluck('id')->toArray()];

        for ($i = 0; $i < 20; $i++) {
            $adminTenant = AdminTenant::create([
                'username' => $faker->unique()->userName,
                'password' => Hash::make('Password123'),
                'display_name' => $faker->name,
                'date_of_birth' => $faker->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
                'address' => $faker->address,
                'phone_number' => $faker->unique()->numerify('09########'),
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => $faker->optional()->dateTimeThisYear,
                'delete_flg' => false,
            ]);

            $numberOfTenants = $faker->numberBetween(0, 3);
            for ($j = 0; $j < $numberOfTenants; $j++) {
                $tenant = Tenant::create([
                    // 'id' => Str::uuid()->toString(),
                    'admin_tenant_id' => $adminTenant->id,
                    'name' => $faker->company,
                    'access_key' => Str::random(32),
                    'hash_code' => $faker->unique()->sha1,
                    'is_active' => $faker->boolean(80),
                    'db_connection' => 'mysql',
                    'db_host' => '127.0.0.1',
                    'db_port' => 3306,
                    'db_name' => 'tenant_' . Str::slug($faker->unique()->word) . '_' . $faker->unique()->numberBetween(1000, 9999),
                    'db_username' => 'tenant_user_' . $faker->unique()->numberBetween(1000, 9999),
                    'db_password' => Hash::make('TenantPass123'),
                    'group_id' => $faker->randomElement($groupIds),
                    'delete_flg' => false,
                ]);

                $numberOfDomains = $faker->numberBetween(1, 4);
                for ($k = 0; $k < $numberOfDomains; $k++) {
                    $tenant->domains()->create([
                        'domain' => $faker->unique()->domainName,
                        'is_active' => $faker->boolean(90),
                        'delete_flg' => false,
                    ]);
                }
            }
        }
    }
}
