<?php

namespace Tests\Feature\Items;

use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Mockery;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * 「購入する」ボタンを押下すると購入が完了する
     */

    // public function user_can_complete_purchase_and_order_is_saved()
    // {
    //     // Stripeモック
    //     Stripe::setApiKey(config('services.stripe.secret'));
    //     $mock = Mockery::mock('overload:' . StripeSession::class);
    //     $mock->shouldReceive('retrieve')
    //         ->once()
    //         ->andReturn((object)['payment_status' => 'paid']);

    //     // 認証が完了したテストユーザーを作成
    //     $buyer = User::factory()->create(['email_verified_at' => now()]);
    //     Address::factory()->create(['user_id' => $buyer->id]);

    //     // 出品者と商品を作成
    //     $seller = User::factory()->create();
    //     $item = Item::factory()->create([
    //         'user_id' => $seller->id,
    //         'status' => 'on_sale',
    //     ]);

    //     // POSTでStripe Checkoutセッション作成（中継ページ表示）
    //     $this->actingAs($buyer)
    //         ->post(route('orders.checkout', $item->id), [
    //             'payment_method' => 'card',
    //         ])
    //         ->assertStatus(200)
    //         ->assertSee('決済ページへリダイレクトします。', false);

    //     // Stripeのsuccess_urlから遷移する先を模倣
    //     $response = $this
    //         ->actingAs($buyer)
    //         ->get(route('orders.success', ['item' => $item->id]) . '?payment_method=card&session_id=fake_session_id');


    //     // thanksページが表示される
    //     $response->assertStatus(200)
    //         ->assertViewIs('orders.thanks');

    //     // 商品ステータスが「sold」に更新されている
    //     $this->assertDatabaseHas('items', [
    //         'id' => $item->id,
    //         'status' => 'sold',
    //     ]);

    //     // ordersレコードが作成されている
    //     $this->assertDatabaseHas('orders', [
    //         'buyer_id' => $buyer->id,
    //         'item_id' => $item->id,
    //         'payment_method' => 'カード支払い',
    //         'status' => 'pending',
    //     ]);
    // }

    // public function purchased_item_displays_sold_label_on_index()
    // {
    //     // 認証済みユーザー + 住所
    //     $buyer = User::factory()->create(['email_verified_at' => now()]);
    //     Address::factory()->create(['user_id' => $buyer->id]);

    //     // 出品者 + 商品
    //     $seller = User::factory()->create();
    //     $item = Item::factory()->create([
    //         'user_id' => $seller->id,
    //         'status' => 'on_sale',
    //     ]);

    //     // 購入完了処理
    //     $this->actingAs($buyer)
    //         ->get(route('orders.success', ['item' => $item->id]) . '?payment_method=card&session_id=fake_session_id');


    //     // 商品一覧ページにて "sold" のラベルが表示されることを確認
    //     $response = $this->get(route('items.index'));
    //     $response->assertStatus(200)
    //         ->assertSee('sold', false); // Blade上に "sold" 表示が存在するか検証
    // }

    public function test_user_can_complete_purchase_and_order_is_saved()
    {
        // retrieveだけ静的メソッドモック（createはモックしない）
        StripeSession::shouldReceive('retrieve')
            ->once()
            ->with('fake_session_id')
            ->andReturn((object)[
                'payment_status' => 'paid',
            ]);

        $buyer = User::factory()->create(['email_verified_at' => now()]);
        Address::factory()->create(['user_id' => $buyer->id]);

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 'on_sale',
        ]);

        // 決済中継画面
        $this->actingAs($buyer)
            ->post(route('orders.checkout', $item->id), [
                'payment_method' => 'card',
            ])
            ->assertStatus(200)
            ->assertSee('決済ページへリダイレクトします。', false);

        // 決済成功画面
        $this->actingAs($buyer)
            ->get(route('orders.success', ['item' => $item->id]) . '?payment_method=card&session_id=fake_session_id')
            ->assertStatus(200)
            ->assertViewIs('orders.thanks');

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);

        $this->assertDatabaseHas('orders', [
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'status' => 'pending',
        ]);
    }

}
