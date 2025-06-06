<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $items = Item::all();
        $users = User::all();

        foreach ($items as $item) {
            // 出品者以外のユーザから 1〜5 件いいね
            $likers = $users->where('id', '!=', $item->user_id)->random(rand(1, 5));

            foreach ($likers as $user) {
                Like::factory()->create([
                    'item_id' => $item->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
