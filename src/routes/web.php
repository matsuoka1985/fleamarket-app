<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
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

Route::get('/register', function () {
    return view('auth.register');
})->middleware(['guest'])->name('register');

// トップページ（商品一覧）
Route::get('/', [ItemController::class, 'index'])->name('items.index');

// 商品詳細ページ
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

// 認証が必要な機能（中括り）
Route::middleware(['auth'])->group(function () {

    // 商品出品ページ
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');

    // 商品購入確認・配送先変更
    Route::get('/purchase/{item_id}', [OrderController::class, 'create'])->name('orders.create');
    Route::get('/purchase/address/{item_id}', [OrderController::class, 'editAddress'])->name('orders.editAddress');

    // マイページ関連（購入/出品タブはクエリで）
    Route::get('/mypage', [UserController::class, 'show'])->name('users.show');
    Route::get('/mypage/profile', [UserController::class, 'edit'])->name('users.edit');
});
