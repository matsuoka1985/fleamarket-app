<?php

namespace Tests\Feature\Items;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * ログイン済みのユーザーはコメントを送信できる
     */
    public function user_can_post_comment_and_it_increases_comment_count(): void
    {
        // ログインユーザーと住所を作成し、認証完了
        $user = User::factory()->create(['email_verified_at' => now()]);
        Address::factory()->create(['user_id' => $user->id]);

        // 商品を作成（初期コメント数 0）
        $item = Item::factory()->create();
        $this->assertCount(0, $item->comments);

        // コメントをPOST
        $response = $this
            ->actingAs($user)
            ->post(route('comments.store', ['item_id' => $item->id]), [
                'comment' => 'これはテストコメントです。',
            ]);

        // リダイレクト確認
        $response
            ->assertRedirect(route('items.show', $item->id));

        // コメントが保存されているか確認
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'これはテストコメントです。',
        ]);

        // コメント数が1件増えているか確認
        $this->assertEquals(1, $item->comments()->count());
    }

    /** @test
     * ログインしていないユーザーはコメントを投稿できない
     */
    public function guest_cannot_post_comment(): void
    {
        $user = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->post(route('comments.store', ['item_id' => $item->id]), [
            'comment' => 'ゲストのコメント',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'content' => 'ゲストのコメント',
        ]);
    }

    public function test_comment_validation_error_when_comment_is_empty(): void
    {
        $user = \App\Models\User::factory()->create(['email_verified_at' => now()]);
        \App\Models\Address::factory()->create(['user_id' => $user->id]);

        $item = \App\Models\Item::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)
            ->from(route('items.show', $item->id)) // エラー時のリダイレクト元指定
            ->post(route('comments.store', ['item_id' => $item->id]), [
                'comment' => '', // 空で送信
            ]);

        // バリデーションエラーでリダイレクトされることを確認
        $response->assertRedirect(route('items.show', $item->id));

        // セッションにエラーメッセージが含まれていることを確認
        $response->assertSessionHasErrors([
            'comment' => '商品コメントを入力してください'
        ]);

        // コメントが保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'content' => '',
        ]);
    }

    /** @test
     *  コメントが255字以上の場合、バリデーションメッセージが表示される
     */
    public function comment_exceeding_255_characters_triggers_validation_error(): void
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        Address::factory()->create(['user_id' => $user->id]);
        $item = Item::factory()->create(['user_id' => $user->id]);

        $longComment = str_repeat('あ', 256); // 256文字のコメントを生成

        $response = $this->actingAs($user)->post(route('comments.store', $item->id), [
            'comment' => $longComment,
        ]);

        $response->assertSessionHasErrors([
            'comment' => '商品コメントは255文字以内で入力してください',
        ]);
    }
}
