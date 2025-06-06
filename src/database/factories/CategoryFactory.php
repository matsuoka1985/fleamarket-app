<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'key' => $this->faker->unique()->slug, // シーダーと重複しないように
            'label' => $this->faker->word,
            'sort_order' => $this->faker->unique()->numberBetween(1, 100), // 任意だが存在するなら
        ];
    }
}
