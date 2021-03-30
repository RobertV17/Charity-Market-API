<?php

namespace Modules\Item\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use \Faker\Factory;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();

        $limit = 5;

        for ($i = 0; $i < $limit; $i++) {
            DB::table('category')->insertOrIgnore([
                [
                    'title'      => 'Категория ' . $i,
                    'created_at' => $faker->dateTime($max = 'now', $timezone = null)
                ]
            ]);
        }
    }
}
