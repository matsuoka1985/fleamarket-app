<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterValidationTest extends TestCase
{
    use RefreshDatabase;


    /** @test
     * 名前が入力されていない場合、バリデーションメッセージが表示される
     */
    public function registration_fails_when_name_is_missing()
    {
        $response = $this->post('/register', [
            // 'name' を送らないことでバリデーションさせる
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください',
        ]);
    }

    /** @test
     * メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function registration_fails_when_email_is_missing()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            // 'email' を省略
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    /** @test
     * パスワードが未入力の場合、バリデーションメッセージが表示される
     */
    public function registration_fails_when_password_is_missing()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    /** @test
     * パスワードが7文字以下の場合、バリデーションメッセージが表示される
     */
    public function registration_fails_when_password_is_too_short()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'shortpass@example.com',
            'password' => 'short77', // 7文字
            'password_confirmation' => 'short77',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください',
        ]);
    }

    /** @test
     * パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
     */
    public function registration_fails_when_passwords_do_not_match()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'mismatch@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different123',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードと一致しません',
        ]);
    }

    /** @test
     * 正常な情報が入力された場合、ユーザーが登録され、送信されたメールアドレスを確認するよう促すページにリダイレクトされる
     */
    public function user_can_register_with_valid_input()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'validuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // 登録成功後のリダイレクト先
        $response->assertRedirect('/email/verify');

        // dbにユーザが登録されていることを確認
        $this->assertDatabaseHas('users', [
            'name' => 'テストユーザー',
            'email' => 'validuser@example.com',
        ]);
    }
}
