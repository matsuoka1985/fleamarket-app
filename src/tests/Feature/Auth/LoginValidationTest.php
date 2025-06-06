<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginValidationTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     *  メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function login_fails_when_email_is_missing()
    {
        $response = $this->post('/login', [
            'email' => '', // 未入力
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    /** @test
     * パスワードが入力されていない場合、バリデーションエラーメッセージが表示される
     */
    public function login_fails_when_password_is_missing()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'user@example.com',
            'password' => '', // パスワード未入力
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    /** @test
     * メールアドレスが間違っている場合、ログインエラーとなる
     */
    public function login_fails_with_wrong_email()
    {
        // 事前に正しいユーザを登録
        User::factory()->create([
            'email' => 'correct@example.com',
            'password' => bcrypt('validpassword'),
        ]);

        // 存在しないメールアドレスでログイン試行
        $response = $this->from('/login')->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'validpassword',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    /** @test
     * メールアドレスは合っていてもパスワードが間違っている場合、ログインエラーとなる
     */
    public function login_fails_with_wrong_password()
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('correct-password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'user@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    /** @test
     * 正しい情報が入力された場合、ログイン処理が実行される
     */
    public function user_can_login_with_valid_credentials()
    {
        // ユーザー作成
        $user = User::factory()->create([
            'email' => 'valid@example.com',
            'password' => bcrypt('password123'),
        ]);

        //  正しいログイン情報でログイン試行
        $response = $this->post('/login', [
            'email' => 'valid@example.com',
            'password' => 'password123',
        ]);

        // 認証されていて、リダイレクトされていることを確認
        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }
}
