<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use App\Models\Comment;



class CommentsTableSeeder extends Seeder
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
            $commenters = $users->where('id', '!=', $item->user_id)->random(rand(1, 3));

            foreach ($commenters as $user) {
                Comment::factory()->create([
                    'item_id' => $item->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
