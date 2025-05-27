<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = Item::inRandomOrder()->take(5)->get();

        foreach ($items as $item) {
            $buyer = User::where('id', '!=', $item->user_id)->inRandomOrder()->first();
            $address = Address::where('user_id', $buyer->id)->inRandomOrder()->first();

            if ($buyer && $address) {
                Order::factory()->create([
                    'buyer_id' => $buyer->id,
                    'item_id' => $item->id,
                    'address_id' => $address->id,
                ]);
            }
        }
    }
}
