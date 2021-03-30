<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Faker\Factory;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();

        $limit = 5;

        for ($i = 0; $i < $limit; $i++) {
            DB::table('users')->insertOrIgnore([
                [
                    'login' => $faker->unique()->userName,
                    'email' => $faker->unique()->safeEmail,
                    'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
                ]
            ]);
        }
    }
}
