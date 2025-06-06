<?php

namespace Tests\Feature\Profile;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileEditViewTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * ユーザー情報変更において変更項目が初期値として過去設定されている（プロフィール画像、ユーザー名、郵便番号、住所）
     */
    public function user_profile_edit_view_shows_initial_values()
    {
        // ユーザーにデフォルト画像パスをセット
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'image' => 'images/default-user.png', // public配下の相対パス
        ]);

        // 住所データも作成
        $address = Address::factory()->create([
            'user_id'     => $user->id,
            'postal_code' => '123-4567',
            'address'     => '東京都港区六本木1-1-1',
            'building'    => '六本木ヒルズ101',
        ]);

        // プロフィール編集ページにアクセス
        $response = $this->actingAs($user)->get(route('users.edit'));

        // ステータスコードと初期表示内容の確認
        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee('123-4567');
        $response->assertSee('東京都港区六本木1-1-1');
        $response->assertSee('六本木ヒルズ101');
        $response->assertSee(asset('images/default-user.png'));
    }
}
