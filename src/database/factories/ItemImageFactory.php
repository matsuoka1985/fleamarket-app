<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemImageFactory extends Factory
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
            'item_id' => Item::factory(),
            'image_url' => 'images/items/' . $this->faker->randomElement([
                'watch.jpg',
                'hdd.jpg',
                'onions.jpg',
                'shoes.jpg',
                'laptop.jpg',
                'mic.jpg',
                'shoulderbag.jpg',
                'tumbler.jpg',
                'coffeemill.jpg',
                'makeup.jpg'
            ]),
            'sort_order' => 1,
        ];
    }
}
