<?php

namespace Tests\Mocks;
//自作クラス。StipeのCheckout\Sessionを模倣するためのフェイククラス
class FakeStripeSession
{
    public static function retrieve($sessionId)
    {
        return (object)[
            'payment_status' => 'paid',
        ];
    }

    public static function create(array $params)
    {
        return (object)[
            'url' => 'http://localhost/fake-checkout',
            'id' => 'fake_session_id',
        ];
    }
}
