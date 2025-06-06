<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    // 自作メソッド。テスト環境でのStripe Checkoutセッションのフェイクを使用するためのセットアップ
    protected function setUp(): void
    {
        parent::setUp();

        // Stripe セッションの静的メソッドをフェイクに置き換え
        if (!class_exists(\Stripe\Checkout\Session::class, false)) {
            class_alias(\Tests\Mocks\FakeStripeSession::class, \Stripe\Checkout\Session::class);
        }
    }
}
