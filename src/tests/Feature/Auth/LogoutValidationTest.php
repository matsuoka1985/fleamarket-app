<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * ログアウトができる
     */
    public function user_can_logout_successfully()
    {
        //  ユーザ作成、そしてそのユーザでログイン。
        $user = User::factory()->create();
        $this->actingAs($user);

        // POSTメソッドでログアウト
        $response = $this->post('/logout');

        // ログアウトされたことを確認
        $this->assertGuest(); // 認証されていない状態
        $response->assertRedirect('/');
    }
}
