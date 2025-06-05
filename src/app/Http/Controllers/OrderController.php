<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Refund;
use Stripe\Checkout\Session;

use Stripe\Checkout\Session as StripeSession;

class OrderController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($item_id)
    {

        $item = Item::with('images')->findOrFail($item_id);

        // すでに売れていたらアクセス拒否
        if ($item->status === 'sold') {
            return redirect()->route('items.show', $item->id)
                ->with('error', 'この商品はすでに購入されています。');
        }

        $address = auth()->user()->addresses()->latest()->first();
        // $address = auth()->user()->addresses()->orderByDesc('id')->first();

        return view('orders.confirm', compact('item', 'address'));
    }

    public function checkout(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        //自分が出品した商品を自分で買おうとしているユーザーをブロック
        if ($item->user_id === $user->id) {
            abort(403, '自分の商品は購入できません');
        }

        // すでに売れている商品は購入不可
        if ($item->status === 'sold') {
            return redirect()
                ->route('items.show', $item->id)
                ->with('error', 'この商品はすでに購入されています。');
        }

        $paymentMethod = $request->input('payment_method');

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => [$paymentMethod],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => $item->price,
                    'product_data' => [
                        'name' => $item->title,
                        'description' => $item->description,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('orders.success', ['item' => $item->id])
                . '?session_id={CHECKOUT_SESSION_ID}&payment_method=' . urlencode($paymentMethod),
            'cancel_url' => route('orders.cancel', ['item' => $item->id]),
        ]);

        return view('orders.checkout', [
            'session' => $session,
            'publicKey' => config('services.stripe.public_key'),
        ]);
    }


    public function success(Request $request, $item_id)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            abort(403, 'セッションIDが指定されていません');
        }

        // Stripeセッションの検証
        Stripe::setApiKey(config('services.stripe.secret'));
        $session = StripeSession::retrieve($sessionId);

        if ($session->payment_status !== 'paid') {
            abort(403, '未払いまたは不正なセッション');
        }

        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        // すでに売れていたら重複防止
        if ($item->status === 'sold') {
            // セッションIDから決済Intentを取得（session_idはsuccess_urlのクエリパラメータから取得）
            $session = \Stripe\Checkout\Session::retrieve($request->query('session_id'));
            $paymentIntentId = $session->payment_intent;

            // 返金処理
            Refund::create([
                'payment_intent' => $paymentIntentId,
            ]);
            return redirect()->route('items.show', $item_id)->with('error', 'この商品はすでに購入されています。');
        }

        // 商品ステータス更新
        $item->status = 'sold';
        $item->save();

        // 住所情報
        $address = $user->addresses()->latest()->first();

        // 支払い方法（URLから取得）
        $method = $request->query('payment_method');
        if ($method === 'card') {
            $paymentMethod = 'カード支払い';
        } elseif ($method === 'konbini') {
            $paymentMethod = 'コンビニ払い';
        } else {
            $paymentMethod = '不明';
        }

        // ordersレコ登録
        Order::create([
            'buyer_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
            'status' => 'pending',
            'payment_method' => $paymentMethod,
        ]);

        return view('orders.thanks');
    }

    public function cancel($item_id)
    {
        $item = Item::findOrFail($item_id);

        return view('orders.cancel', compact('item'));
    }

    public function editAddress($item_id)
    {
        session(['last_item_id' => $item_id]);
        // $user = auth()->user();
        $address = auth()->user()->addresses()->latest()->first();
        return view('addresses/edit', compact(['address',]));
    }


    public function updateAddress(AddressRequest $request)
    {

        $user = auth()->user();

        $user->addresses()->create([
            'postal_code' => $request->postal_code,
            'address'     => $request->address,
            'building'    => $request->building,
        ]);
        return redirect()->route('orders.create', ['item_id' => session('last_item_id')])
            ->with('status', '住所を更新しました');
    }
}
