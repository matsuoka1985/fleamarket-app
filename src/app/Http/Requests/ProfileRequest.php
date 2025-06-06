<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            //
            'name'  => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required'   => 'お名前を入力してください',
            'image.image'     => '画像ファイルを選択してください',
            'image.max'       => '画像サイズは2MB以内にしてください',
            'image.mimes'     => 'プロフィール画像はjpegまたはpng形式のみ対応しています',
        ];
    }
}
