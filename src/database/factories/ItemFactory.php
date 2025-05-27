<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'title' => $this->faker->word(), // ダミー
            'description' => $this->faker->realText(80),
            'price' => $this->faker->numberBetween(100, 10000),
            'status' => 'on_sale',
            'condition' => '良好',
            'user_id' => optional(User::inRandomOrder()->first())->id ?? 1,

        ];
    }
}
