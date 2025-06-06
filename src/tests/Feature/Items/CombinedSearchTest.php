<?php

namespace Tests\Feature\Items;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CombinedSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * マイリストタブでの検索において、いいねした商品かつキーワードにマッチする商品という複合条件での検索ができること
     */
    public function search_keyword_is_retained_in_mylist_tab()
    {
        // テストユーザ作成
        $user = User::factory()->create();

        // 「Camera」というキーワードにマッチし、かついいね済の商品（表示されるべき）
        $likedMatchedItem = Item::factory()->create([
            'title' => 'Liked Camera',
            'user_id' => User::factory()->create()->id,
        ]);
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedMatchedItem->id,
        ]);

        // キーワードにマッチしないが、いいね済の商品（表示されないべき）
        $likedUnmatchedItem = Item::factory()->create([
            'title' => 'Liked Book',
            'user_id' => User::factory()->create()->id,
        ]);
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedUnmatchedItem->id,
        ]);

        // キーワードにマッチするが、いいねしていない商品（表示されないべき）
        $unlikedMatchedItem = Item::factory()->create([
            'title' => 'Unliked Camera',
            'user_id' => User::factory()->create()->id,
        ]);

        $response = $this->actingAs($user)->get(route('items.index', [
            'page' => 'mylist',
            'keyword' => 'Camera',
        ]));

        $response->assertSee('Liked Camera');
        $response->assertDontSee('Liked Book');
        $response->assertDontSee('Unliked Camera');
    }
}
