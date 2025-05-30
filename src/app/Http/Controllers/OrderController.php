<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;

use Stripe\Checkout\Session as StripeSession;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($item_id)
    {
        $item = Item::with('images')->findOrFail($item_id);
        $address = auth()->user()->addresses()->latest()->first();
        return view('orders.confirm', compact('item', 'address'));
    }

    public function checkout(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $paymentMethod = $request->input('payment_method');

        if (!in_array($paymentMethod, ['card', 'konbini'])) {
            abort(400, '不正な支払い方法です');
        }
        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => [$paymentMethod], // 今回は card 前提
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
            'success_url' => route('orders.success', ['item' => $item->id]) . '?payment_method=' . urlencode($paymentMethod),
            'cancel_url' => route('orders.cancel', ['item' => $item->id]),
        ]);

        return view('orders.checkout', [
            'session' => $session,
            'publicKey' => config('services.stripe.public_key'),
        ]);
    }

    public function success(Request $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        // すでに売れていたら重複防止
        if ($item->status === 'sold') {
            return redirect()->route('items.index')->with('error', 'この商品はすでに購入されています。');
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
        $user = auth()->user();
        $address = $user->addresses()->latest()->first();
        $item = Item::findOrFail($item_id);

        return view('orders.edit_address', compact('item', 'address'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
