<?php

namespace Tests\Feature\Items;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\ItemImage;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_detail_page_displays_all_expected_information()
    {
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);

        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        $category = Category::firstOrFail();

        $item = Item::factory()->create([
            'user_id' => $otherUser->id,
            'title' => 'Test Product',
            'brand_name' => 'Test Brand',
            'price' => 12345,
            'description' => 'This is a test description.',
            'condition' => 'やや傷や汚れあり',
            'status' => 'on_sale',
        ]);

        $item->categories()->attach($category->id);

        ItemImage::factory()->create([
            'item_id' => $item->id,
            'image_url' => 'images/items/sample.jpg',
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        Comment::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'This is a test comment.',
        ]);

        $response = $this->actingAs($user)->get(route('items.show', $item->id));

        $response->assertStatus(200);
        $response->assertSee('Test Product');
        $response->assertSee('Test Brand');
        $response->assertSee('¥12,345');
        $response->assertSee('This is a test description.');
        $response->assertSee($category->label);
        $response->assertSee('やや傷や汚れあり');
        $response->assertSee('This is a test comment.');
        $response->assertSee($user->name);
        $response->assertSee('<img src="' . asset('storage/images/items/sample.jpg') . '"', false);
        $response->assertSee('⭐');
        $response->assertSee('💬');
    }
}
