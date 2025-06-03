<?php

namespace Tests\Feature\Items;

use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;
use App\Models\ItemImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * 商品詳細情報取得処理について、必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（カテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容）
     */
    public function item_detail_page_displays_all_expected_information(): void
    {
        // 出品ユーザー
        $seller = User::factory()->create(['name' => '表示テストユーザー']);

        // 閲覧ユーザー（テストログイン用）
        $viewer = User::factory()->create();

        // カテゴリー
        $category = Category::factory()->create(['label' => 'ファッション']);

        // 商品
        $item = Item::factory()->create([
            'title' => 'Test Product',
            'brand_name' => 'Test Brand',
            'description' => 'This is a test description.',
            'price' => 12345,
            'status' => 'やや傷や汚れあり',
            'condition' => '良好',
            'user_id' => $seller->id,
        ]);

        // 商品とカテゴリの関連付け
        $item->categories()->attach($category->id);

        // 商品画像
        ItemImage::factory()->create([
            'item_id' => $item->id,
            'image_url' => 'images/items/sample.jpg',
            'sort_order' => 1,
        ]);

        // コメント（73件中の最後に特定内容）
        Comment::factory()->count(72)->create(['item_id' => $item->id]);
        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $seller->id,
            'content' => 'This is a test comment.',
        ]);

        // いいね（42件中の1件は閲覧ユーザー）
        Like::factory()->count(41)->create(['item_id' => $item->id]);
        Like::factory()->create([
            'item_id' => $item->id,
            'user_id' => $viewer->id,
        ]);

        // 商品詳細ページにアクセス（閲覧ユーザーとして）
        $response = $this->actingAs($viewer)->get(route('items.show', $item->id));

        // 検証（順序通り）
        $response->assertStatus(200);
        $response->assertSee('images/items/sample.jpg', false); // 商品画像のパス
        $response->assertSee('Test Product'); // 商品名
        $response->assertSee('Test Brand'); // ブランド名
        $response->assertSee('12,345'); // 価格
        $response->assertSee('42'); // いいね数
        $response->assertSee('73'); // コメント数
        $response->assertSee('This is a test description.'); // 商品説明
        $response->assertSee($category->label); // カテゴリ名
        $response->assertSee('良好'); // 商品の状態
        $response->assertSee('表示テストユーザー'); // コメントユーザー名
        $response->assertSee('This is a test comment.'); // コメント本文
    }


    /** @test
     * 商品詳細情報取得処理について、複数選択されたカテゴリが表示されているか
     */
    public function multiple_categories_are_displayed_on_item_detail_page(): void
    {
        // ユーザ作成
        $user = User::factory()->create();

        // 複数カテゴリ作成
        $categories = Category::factory()->count(3)->sequence(
            ['label' => 'ファッション'],
            ['label' => '家電'],
            ['label' => 'インテリア'],
        )->create();

        // 商品作成（ユーザに紐付け）
        $item = Item::factory()->create(['user_id' => $user->id]);

        // 商品とカテゴリを紐付け
        $item->categories()->attach($categories->pluck('id'));

        // 商品詳細ページへアクセス
        $response = $this->actingAs($user)->get(route('items.show', $item->id));

        // ステータス確認
        $response->assertStatus(200);

        // 各カテゴリラベルが表示されていることを検証
        foreach ($categories as $category) {
            $response->assertSee($category->label);
        }
    }
}
