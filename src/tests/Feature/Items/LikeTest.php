<?php

namespace Tests\Feature\Items;

use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * いいねアイコンを押下することによって、いいねした商品として登録することができる。
     */
    public function user_can_like_item_and_like_count_increments(): void
    {
        // ユーザーを作成し、メールアドレスを検証済みに設定
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        //  ユーザーに関連する住所を作成。以上により、いいねを付けるための前提条件を満たす。
        Address::factory()->create(['user_id' => $user->id]);

        $item = Item::factory()->create();

        $response = $this
            ->actingAs($user)
            ->json('POST', route('likes.toggle', $item->id));

        $response->assertStatus(200)
            ->assertJson([
                'liked' => true,
                'count' => 1,
            ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }



    /** @test
     * 再度いいねアイコンを押下することによって、いいねを解除することができる。
     */
    public function user_can_unlike_item_and_like_count_decrements(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        Address::factory()->create(['user_id' => $user->id]);

        $item = Item::factory()->create();

        // まず最初にいいねを付ける
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->json('POST', route('likes.toggle', $item->id));

        $response->assertStatus(200)
            ->assertJson([
                'liked' => false,
                'count' => 0,
            ]);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }


    /** @test
     * いいねを押すとアイコンの表示が変化する。
     */
    public function liked_icon_has_larger_size_class()
    {
        // 準備：ユーザと商品、いいね済み状態を作成
        $user = User::factory()->create();
        $item = Item::factory()->create();
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 商品詳細ページへアクセス
        $response = $this->actingAs($user)->get(route('items.show', $item->id));

        // いいね済みの場合は text-3xl クラスが付与されている
        $response->assertSee('text-3xl', false);
        $response->assertSee('⭐', false);
    }


    /** @test
     * いいねを解除するとアイコンの表示が変化する。
     */
    public function unliked_icon_has_smaller_size_class()
    {
        // 準備：ユーザと商品（いいねしてない状態）
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // 実行：商品詳細ページへアクセス
        $response = $this->actingAs($user)->get(route('items.show', $item->id));

        // 検証：いいねしてない場合は text-xl クラスが付与されている
        $response->assertSee('text-xl', false);
        $response->assertSee('☆', false);
    }
}
