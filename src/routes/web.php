<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\Auth\EmailVerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/test', function () {
    return view('test');
});



Route::get('/home', function () {
    return redirect('/'); // トップページへリダイレクト
})->name('home');


Route::get('/testroute', function () {
    return view('test');
})->middleware(['auth', 'verified']); //テスト用のルーティング。最後に削除しても問題ない。

Route::get('/register', function () {
    return view('auth.register');
})->middleware(['guest'])->name('register');

//自作上書きルート
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->middleware(['guest'])
    ->name('register');

//自作上書きルート
Route::get('/email/verify/{id}/{hash}', EmailVerificationController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');


// トップページ（商品一覧）
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// 商品詳細ページ
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');


Route::middleware(['auth', 'verified'])->group(
    function () {
        //メール認証まで終わったユーザーが最初にリダイレクトされるページここで必須項目を入力してようやく完全なサインアップ完了。メール認証が済んでいてもここで必須項目を入力していないユーザーはここにリダイレクトされる。また、ユーザーがプロフィール編集する際もこのページを利用する。
        Route::get('/mypage/profile', [UserController::class, 'edit'])->name('users.edit');

        //上の画面におけるバックエンド処理。
        Route::put('/mypage/profile', [UserController::class, 'update'])->name('profile.update');
    }
);



// 認証が必要な機能
Route::middleware(['auth', 'verified', 'require.address'])->group(function () {

    // 商品出品ページ
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    // 商品出品処理
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');

    // 商品購入確認ページ
    Route::get('/purchase/{item_id}', [OrderController::class, 'create'])->name('orders.create');

    //決済中継ページ。ここでStripeのCheckout Sessionを作成して、フロントエンドにリダイレクトする。
    Route::post('/checkout/{item_id}', [OrderController::class, 'checkout'])->name('orders.checkout');

    //決済成功後の処理。ここで注文情報を保存する。最後にユーザーにthanksページを表示する。
    Route::get('/checkout/success/{item}', [OrderController::class, 'success'])->name('orders.success');

    // キャンセル時
    Route::get('/checkout/cancel/{item}', [OrderController::class, 'cancel'])->name('orders.cancel'); //決済キャンセル時の処理。


    //購入時の配送先変更ページ。ここで住所を編集できる。
    Route::get('/purchase/address/{item_id}', [OrderController::class, 'editAddress'])->name('orders.editAddress');


    //購入時の配送先更新処理。
    Route::post('/purchase/address/update', [OrderController::class, 'updateAddress'])
        ->name('orders.updateAddress');

    //ユーザープロフィールページ。ここで出品した商品と購入した商品をタブで切り分けて表示
    Route::get('/mypage', [UserController::class, 'show'])->name('users.show');


    //いいね機能
    Route::post('/likes/{item}', [LikeController::class, 'toggle'])->name('likes.toggle');


    //コメント投稿機能。
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comments.store');
});
