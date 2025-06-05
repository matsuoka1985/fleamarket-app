<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules;


class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],

        ];

    }

    public function messages(): array
    {
        return [
            'name.required' => 'aお名前を入力してください',
            'email.required' => 'aメールアドレスを入力してください',
            'email.email' => 'a有効なメールアドレスを入力してください',
            'password.required' => 'aパスワードを入力してください',
            'password.min' => 'aパスワードは8文字以上で入力してください',
            'password.confirmed' => 'aパスワードと一致しません',
        ];
    }
}
