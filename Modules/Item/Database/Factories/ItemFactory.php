<?php

namespace Modules\Item\Database\Factories;

use Modules\Item\Models\Category;
use Modules\Item\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\User;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userIds = User::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        return [
            'title'      => $this->faker->unique()->numerify('Товар ##'),
            'desc'       => $this->faker->numerify('Описание к товару ###'),
            'price'      => $this->faker->randomFloat($nbMaxDecimals = 3, $min = 0, $max = 1000),
            'photo_url'  => $this->faker->unique()->numerify('Cсылка на фото ##'),
            'user_id'    => $this->faker->randomElement($userIds),
            'cat_id'     => $this->faker->randomElement($categoryIds),
            'created_at' => $this->faker->dateTime($max = 'now', $timezone = null)
        ];
    }
}
