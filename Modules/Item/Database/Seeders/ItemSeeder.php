<?php

namespace Modules\Item\Database\Seeders;


use Illuminate\Database\Seeder;
use Modules\User\Models\User;
use Modules\Item\Models\Category;
use Illuminate\Support\Facades\DB;
use \Faker\Factory;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();

        $limit = 25;

        $userIds = User::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        for ($i = 0; $i < $limit; $i++) {
            DB::table('item')->insertOrIgnore([
                [
                    'title'     => $faker->unique()->numerify('Товар ##'),
                    'desc'      => $faker->numerify('Описание к товару ###'),
                    'price'     => $faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 1000),
                    'photo_url' => $faker->unique()->numerify('Cсылка на фото ##'),
                    'user_id'   => $faker->randomElement($userIds),
                    'cat_id'    => $faker->randomElement($categoryIds),
                    'created_at' => $faker->dateTime($max = 'now', $timezone = null)
                ]
            ]);
        }
    }
}
