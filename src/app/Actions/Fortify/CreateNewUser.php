<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Requests\RegisterRequest;

class CreateNewUser implements CreatesNewUsers
{
    /**
     * バリデーションとユーザ作成処理
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        // RegisterRequest を手動でインスタンス化してバリデーションを実行
        app(RegisterRequest::class)->merge($input)->validate();

        return User::create([
            'name'     => $input['name'],
            'email'    => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
