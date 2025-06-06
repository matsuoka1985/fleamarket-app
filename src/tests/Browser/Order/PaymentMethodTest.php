<?php

namespace Tests\Browser\Order;

use App\Models\Address;
use App\Models\Item;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PaymentMethodTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function user_can_select_payment_method_and_it_reflects_in_summary()
    {
        $user = User::factory()->create(['email_verified_at' => now()]);
        Address::factory()->create(['user_id' => $user->id]);

        $item = Item::factory()->create([
            'status' => 'on_sale',
        ]);

        $this->browse(function (Browser $browser) use ($user, $item) {
            $browser->loginAs($user)
                ->visit(route('orders.create', $item->id))
                ->assertSee('支払い方法')
                ->select('#payment-method', 'card')
                ->pause(300) // JavaScriptの反映待ち（ミリ秒）
                ->assertSeeIn('#selected-payment-method', 'クレジットカード')

                ->select('#payment-method', 'konbini')
                ->pause(300)
                ->assertSeeIn('#selected-payment-method', 'コンビニ払い');
        });
    }
}
