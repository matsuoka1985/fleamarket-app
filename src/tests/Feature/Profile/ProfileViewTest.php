<?php

namespace Tests\Feature\Profile;

use App\Models\User;
use App\Models\Item;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Tests\TestCase;

class ProfileViewTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * ユーザー情報取得において、必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
     */
    public function user_profile_displays_user_info_and_items_with_profile_image()
    {

        // public/images/default-user.png を使用
        $imagePath = 'images/default-user.png';

        // ユーザとプロフィール画像
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'image' => $imagePath, // 既存の画像パスを直接設定
            'email_verified_at' => now(),
        ]);

        // 出品商品
        $listedItem = Item::factory()->create([
            'user_id' => $user->id,
            'title' => '出品した商品',
        ]);

        // 購入商品と注文
        $seller = User::factory()->create();
        $purchasedItem = Item::factory()->create([
            'user_id' => $seller->id,
            'title' => '購入した商品',
        ]);
        $address = Address::factory()->create(['user_id' => $user->id]);
        Order::factory()->create([
            'buyer_id' => $user->id,
            'item_id' => $purchasedItem->id,
            'address_id' => $address->id,
        ]);

        // アクセス実行
        $response = $this->actingAs($user)->get(route('users.show'));

        // 検証
        $response->assertStatus(200)
            ->assertSee('テスト太郎')
            ->assertSee('出品した商品')
            ->assertSee('購入した商品')
            ->assertSee('<img src="' . asset($user->image), false);
    }
}
