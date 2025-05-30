<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

//自作クラス
class EmailVerificationController extends Controller
{
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($this->redirectPath($request));
        }

        $request->fulfill();

        return redirect()->intended($this->redirectPath($request))
            ->with('status', 'メール認証が完了しました。プロフィールを設定してください。');
    }


    protected function redirectPath($request): string
    {
        $user = $request->user();

        // addresses テーブルに紐づくレコードがなければ /mypage/profile に飛ばす
        if (!$user->addresses()->exists()) {
            return '/mypage/profile';
        }

        // それ以外は通常のトップページ（変更可）
        return '/';
    }
}
