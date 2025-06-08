<?php

namespace Tests\Feature\Orders;

use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateAddressTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     * 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
     */
    public function user_can_update_address_and_it_redirects_to_purchase_page()
    {
        // メール認証済みユーザを作成（既存住所はあってもOK）
        $user = User::factory()->create(['email_verified_at' => now()]);
        // 既存の住所を作成（10秒前に作成）。過去指定しないとこのfactoryで作成したものが最新扱いされてしまう。
        Address::factory()->create(['user_id' => $user->id, 'created_at' => now()->subSeconds(10),]);

        $item = Item::factory()->create();

        // 住所を登録（POST）
        $this->actingAs($user)
            ->withSession(['last_item_id' => $item->id])
            ->post(route('orders.updateAddress'), [
                'postal_code' => '150-0001',
                'address'     => '東京都渋谷区神宮前1-1-1',
                'building'    => '原宿ビル301',
            ]);

        // 確実に反映後のページを取得（GET）
        $response = $this->actingAs($user)
            ->get(route('orders.create', ['item_id' => $item->id]));

        // Bladeに新住所が表示されていることを検証
        $response->assertSee('150-0001');
        $response->assertSee('東京都渋谷区神宮前1-1-1');
        $response->assertSee('原宿ビル301');
    }

    /**
     * @test
     * 購入した商品に送付先住所が紐づいて登録される
     */
    public function shipping_address_is_linked_to_purchased_item()
    {
        // ユーザ・住所・商品を準備
        $buyer = User::factory()->create(['email_verified_at' => now()]);
        $address = Address::factory()->create([
            'user_id' => $buyer->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'テストビル101',
            'created_at' => now()->subSeconds(10), // 10秒前に作成
        ]);

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 'on_sale',
            'price' => 1000
        ]);

        $this->actingAs($buyer)
            ->withSession(['last_item_id' => $item->id])
            ->post(route('orders.updateAddress'), [
                'postal_code' => '150-0001',
                'address'     => '東京都渋谷区神宮前1-1-1',
                'building'    => '原宿ビル301',
            ]);
        $latestAddress = $buyer->addresses()->latest()->first();


        // Stripeのセッションモック
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'success_url' => route('orders.success', ['item' => $item->id]) . '?payment_method=card&session_id=fake_session_id',
            'cancel_url' => route('orders.cancel', ['item' => $item->id]),
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->title],
                    'unit_amount' => $item->price * 100,
                ],
                'quantity' => 1,
            ]],
        ]);

        // 決済中継を実行
        $this->actingAs($buyer)
            ->post(route('orders.checkout', $item->id), [
                'payment_method' => 'card',
            ])
            ->assertStatus(200);

        // 決済成功処理
        $this->actingAs($buyer)
            ->get(route('orders.success', ['item' => $item->id]) . '?payment_method=card&session_id=fake_session_id')
            ->assertStatus(200);

        // 注文情報の確認
        $this->assertDatabaseHas('orders', [
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
            'address_id' => $latestAddress->id,
        ]);
    }
}
