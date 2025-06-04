<?php

namespace Tests\Feature\Items;

use App\Models\Address;
use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * 出品商品情報登録において商品出品画面にて必要な情報が保存できる（カテゴリ、商品の状態、商品名、商品の説明、販売価格）
     */
    public function user_can_register_item_with_all_required_info()
    {
        // 実在ファイルをコピーしてアップロード用ファイルを作成
        $source = storage_path('app/public/images/items/coffeemill.jpg');
        $target = storage_path('app/public/temp_test_images/test-image.jpg');
        File::ensureDirectoryExists(dirname($target));
        File::copy($source, $target);

        $image = new UploadedFile(
            $target,
            'test-image.jpg',
            'image/jpeg',
            null,
            true
        );

        // ユーザとカテゴリと住所を作成
        $user = User::factory()->create();

        Address::factory()->create([
            'user_id'     => $user->id,
            'postal_code' => '1500001',
            'address'     => '東京都渋谷区神宮前1-1-1',
            'building'    => 'テストビル101'
        ]);

        $categories = Category::factory()->count(2)->create();

        // フォームデータ構成
        $form = [
            'title'        => '出品テスト商品',
            'brand_name'   => 'ブランドX',
            'description'  => 'これはテスト用の商品説明です。',
            'condition'    => '良好',
            'price'        => 1500,
            'category_ids' => $categories->pluck('id')->toArray(),
            'image'        => $image,
        ];

        // POST実行
        $response = $this->actingAs($user)->post(route('items.store'), $form);

        // 保存確認
        $item = Item::first();
        $this->assertNotNull($item, 'Itemが保存されていません');

        // リダイレクト確認
        $response->assertRedirect(route('items.show', ['item_id' => $item->id]));
        $response->assertSessionHas('status', '商品を出品しました');

        // DB確認
        $this->assertDatabaseHas('items', [
            'title'       => '出品テスト商品',
            'brand_name'  => 'ブランドX',
            'description' => 'これはテスト用の商品説明です。',
            'condition'   => '良好',
            'price'       => 1500,
            'user_id'     => $user->id,
            'status'      => 'on_sale',
        ]);

        // 画像確認
        $this->assertCount(1, $item->images);

        // カテゴリ確認
        foreach ($categories as $category) {
            $this->assertTrue($item->categories->contains($category));
        }
    }
}
