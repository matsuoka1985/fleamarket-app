<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        // remember チェック付きで認証
        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return Redirect::back()->withErrors([
                'email' => 'ログイン情報が登録されていません',
            ])->withInput($request->only('email'));
        }

        // セッション再生成
        $request->session()->regenerate();

        // 元々行きたかったURL があればそこへ、なければ "/" へ
        return redirect()->intended('/');
    }
}
