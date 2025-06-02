<?php

namespace Tests\Feature\Items;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * 商品一覧取得について全商品を取得できる
     */
    public function it_displays_all_items_on_index_page()
    {
        //ユーザーを作成
        $user = User::factory()->create();

        //  ダミー商品をそのユーザに紐付けて3件作成
        $items = Item::factory()->count(3)->for($user)->create();

        //  商品一覧ページへアクセス
        $response = $this->get('/');

        // 全商品のタイトルが表示されていることを確認
        foreach ($items as $item) {
            $response->assertSee($item->title);
        }

        $response->assertOk();
    }

    /** @test
     * 商品一覧取得において購入済み商品は「sold」と表示される
     */
    public function sold_items_display_sold_label_on_index_page()
    {
        // ユーザ作成
        $user = User::factory()->create();

        // statusが'sold'の商品を作成
        $item = Item::factory()->for($user)->create([
            'status' => 'sold',
        ]);

        //  一覧ページにアクセス
        $response = $this->get('/');

        // 「sold」のラベルが表示されているか確認
        $response->assertSee('sold');
        $response->assertSee($item->title); // 商品タイトルも確認しておくと安心
    }

    /** @test
     * 商品一覧取得において自分が出品した商品は表示されない
     */
    public function user_does_not_see_their_own_items_on_item_index()
    {
        //  ユーザAを作成
        $user = User::factory()->create();

        // ユーザAが出品した商品
        $ownItem = Item::factory()->for($user)->create([
            'title' => 'ユーザAの出品商品',
        ]);

        // 他人が出品した商品
        $otherUser = User::factory()->create();
        $otherItem = Item::factory()->for($otherUser)->create([
            'title' => '他人の出品商品',
        ]);

        // ユーザAでログインして商品一覧ページへアクセス
        $response = $this->actingAs($user)->get('/');

        // 自分の商品は表示されない
        $response->assertDontSee($ownItem->title);

        // 他人の商品は表示される
        $response->assertSee($otherItem->title);
    }
}
