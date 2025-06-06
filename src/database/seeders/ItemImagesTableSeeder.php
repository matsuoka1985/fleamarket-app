<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\ItemImage;
class ItemImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $imagePaths = [
            'watch.jpg',
            'hdd.jpg',
            'onions.jpg',
            'shoes.jpg',
            'laptop.jpg',
            'mic.jpg',
            'shoulderbag.jpg',
            'tumbler.jpg',
            'coffeemill.jpg',
            'makeup.jpg',
        ];

        $items = Item::all();

        foreach ($items as $index => $item) {
            ItemImage::create([
                'item_id' => $item->id,
                'image_url' => 'images/items/' . $imagePaths[$index],
                'sort_order' => 1,
            ]);
        }
    }
}
