<?php

namespace Tests\Feature\Orders;

use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Stripe\Stripe;


class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * 「購入する」ボタンを押下すると購入が完了する
     */
    public function user_can_complete_purchase_and_order_is_saved()
    {


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

    /** @test
     * 購入した商品は商品一覧画面にて「sold」と表示される
     */
    public function purchased_item_displays_sold_label_on_index()
    {
        $buyer = User::factory()->create(['email_verified_at' => now()]);
        Address::factory()->create(['user_id' => $buyer->id]);

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 'on_sale',
        ]);

        // 購入処理（成功画面アクセスで状態変更と注文保存）
        $this->actingAs($buyer)
            ->get(route('orders.success', ['item' => $item->id]) . '?payment_method=card&session_id=fake_session_id')
            ->assertStatus(200);

        // 商品一覧で「sold」と表示されることを確認
        $response = $this->get(route('items.index'));
        $response->assertStatus(200)->assertSee('sold', false);
    }


    /** @test
     * 「プロフィール/購入した商品一覧」に追加されている
     */
    public function purchased_item_is_listed_on_profile()
    {
        $buyer = User::factory()->create(['email_verified_at' => now()]);
        Address::factory()->create(['user_id' => $buyer->id]);

        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 'on_sale',
        ]);

        // 決済処理
        $this->actingAs($buyer)
            ->post(route('orders.checkout', $item->id), [
                'payment_method' => 'card',
            ])
            ->assertStatus(200);

        // 購入完了（success URLアクセス）
        $this->actingAs($buyer)
            ->get(route('orders.success', ['item' => $item->id]) . '?payment_method=card&session_id=fake_session_id')
            ->assertStatus(200);

        // プロフィール画面で購入商品が表示されていること
        $response = $this->actingAs($buyer)->get(route('users.show'));
        $response->assertStatus(200)
            ->assertSee($item->title, false); // JSによる非表示あり得るため注意
    }

    
}
