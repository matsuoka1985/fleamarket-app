<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class AddressFactory extends Factory
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
            'user_id' => User::inRandomOrder()->first()->id,
            'postal_code' => $this->faker->postcode(),
            'prefecture' => $this->faker->prefecture(),
            'city' => $this->faker->city(),
            'address_line' => $this->faker->streetAddress(),
            'phone_number' => $this->faker->phoneNumber(),
        ];
    }
}
