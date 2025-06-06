<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'shipped', 'completed', 'cancelled', 'refunded']),
            'payment_method' => $this->faker->randomElement(['コンビニ払い', 'カード支払い']),
        ];
    }
}
