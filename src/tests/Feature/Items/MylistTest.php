<?php

namespace Tests\Feature\Items;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MylistTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * マイリスト一覧取得処理において、いいねした商品だけが表示される
     */
    public function only_liked_items_are_shown_on_mylist_tab()
    {
        // テスト用のユーザを作成
        $user = User::factory()->create();

        // 別のユーザが出品した商品（いいね済）
        $likedItem = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'title' => 'Liked Item',
        ]);

        // 別のユーザが出品した商品（いいねなし）
        $unlikedItem = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'title' => 'Unliked Item',
        ]);

        // いいね登録
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($user)->get(route('items.index', ['tab' => 'mylist']));

        $response->assertSee('Liked Item');
        $response->assertDontSee('Unliked Item');
    }

    /** @test
     * マイリスト一覧取得処理において購入済み商品は「sold」と表示される
     */
    public function purchased_items_show_sold_label_in_mylist_tab()
    {
        // ユーザーを作成
        $user = User::factory()->create();

        // 別ユーザが出品した商品を作成
        $purchasedItem = Item::factory()->create([
            'user_id' => User::factory()->create()->id,
            'title' => 'Purchased Item',
            'status' => 'sold', // 購入済み
        ]);

        // その商品に「いいね」をしてマイリストに載るようにする
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
        ]);

        // マイリストページ（?tab=mylist）へアクセス
        $response = $this->actingAs($user)->get(route('items.index', ['tab' => 'mylist']));

        // 商品タイトルが表示されていること
        $response->assertSee('Purchased Item');

        // 「sold」のラベルが表示されていること
        $response->assertSee('sold');
    }

    /** @test
     * マイリスト一覧取得処理において、自分が出品した商品は表示されない
     */
    public function my_own_items_are_not_displayed_on_mylist_tab()
    {
        // ログインユーザを作成
        $user = User::factory()->create();

        // このユーザ自身が出品し、かついいねした商品
        $ownItem = Item::factory()->create([
            'user_id' => $user->id,
            'title' => 'Self Posted Item',
        ]);

        // いいねを付与（マイリストに載る）
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $ownItem->id,
        ]);

        // マイリストページにアクセス
        $response = $this->actingAs($user)->get(route('items.index', ['tab' => 'mylist']));

        // 自分が出品した商品は表示されない（タイトルが含まれないことを検証）
        $response->assertDontSee('Self Posted Item');
    }

    /** @test
     * 未ログイン状態でマイリストタブにアクセスした場合、ログイン画面にリダイレクトされる
     */
    public function guests_are_redirected_when_accessing_mylist_tab()
    {
        // 未ログイン状態で ?tab=mylist にアクセス
        $response = $this->get(route('items.index', ['tab' => 'mylist']));

        // ログイン画面にリダイレクトされることを確認
        $response->assertRedirect(route('login'));
    }
}
