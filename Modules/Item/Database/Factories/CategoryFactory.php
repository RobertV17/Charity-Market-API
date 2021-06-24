<?php

namespace Modules\Item\Database\Factories;

use Modules\Item\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'      => 'Категория ' . $this->faker->unique()->randomDigit,
            'created_at' => $this->faker->dateTime($max = 'now', $timezone = null)
        ];
    }
}
