<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'title'         => ['required', 'string'],
            'description'   => ['required', 'string', 'max:255'],
            'brand_name'    => ['required', 'string'],
            'image'         => ['required', 'image', 'mimes:jpeg,png'],
            'category_ids'  => ['required', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'condition'     => ['required', 'string'],
            'price'         => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'title.required'        => '商品名を入力してください',
            'brand_name.required'   => 'ブランド名を入力してください',
            'description.required'  => '商品説明を入力してください',
            'description.max'       => '商品説明は255文字以内で入力してください',
            'image.required'        => '商品画像を選択してください',
            'image.image'           => 'アップロードされたファイルは画像ではありません',
            'image.mimes'           => '画像はjpegまたはpng形式で指定してください',
            'category_ids.required' => 'カテゴリーを1つ以上選択してください',
            'category_ids.*.exists' => '不正なカテゴリーが含まれています',
            'condition.required'    => '商品の状態を選択してください',
            'price.required'        => '販売価格を入力してください',
            'price.numeric'         => '販売価格は数値で入力してください',
            'price.min'             => '販売価格は0円以上で入力してください',
        ];
    }
}
