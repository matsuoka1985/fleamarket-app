<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

//自作クラス
class RegisteredUserController extends Controller
{
    public function store(RegisterRequest $request)
    {
        // バリデーション
        // $request->validate(
        //     [
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
        // ]
    // );

        // ユーザー作成
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // イベント発火（メール認証リンクの送信）
        event(new Registered($user));

        // 自動ログイン
        auth()->login($user);

        // 登録後のリダイレクト先を変更
        return redirect('/email/verify');
    }
}
