<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Profiler\Profile;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\ProfileRequest;




class UserController extends Controller
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
    public function create()
    {
        //
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
    public function show()
    {
        $user = auth()->user();

        // 出品した商品（例: status問わず全て）
        $listedItems = $user->items()->with('images')->get();

        // 購入した商品（orders 経由で item を取得）
        $purchasedItems = $user->orders()
            ->with(['item.images']) // itemとその画像を取得
            ->get()
            ->pluck('item');

        return view('profile.show', [
            'user' => $user,
            'listedItems' => $listedItems,
            'purchasedItems' => $purchasedItems,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
        $user = Auth::user();
        $address = $user->addresses()->first();

        if (!$address) {
            // 初回登録（createフォームとして利用）
            return view('profile.edit', [
                'user' => $user,
                'address' => null,
            ]);
        }

        return view('profile.edit', [
            'user' => $user,
            'address' => $address,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequest $profileRequest, AddressRequest $addressRequest)
    {
        $user = Auth::user();

        $user->name = $profileRequest->input('name');

        if ($profileRequest->hasFile('image')) {
            $path = $profileRequest->file('image')->store('public/images/users');
            $user->image = Storage::url($path);
        }

        $user->save();

        $addressData = $addressRequest->only(['postal_code', 'address', 'building']);
        $address = $user->addresses()->first();

        if ($address) {
            $address->update($addressData);
        } else {
            $user->addresses()->create($addressData);
        }

        return redirect()->route('users.show')->with('status', 'プロフィールを更新しました');
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
