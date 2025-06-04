<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
     * 郵便番号が全角数字やハイフンなしでも入力されてきた場合に、
     * ハイフンありの形式に変換してからバリデーションを行う。
     */
    public function prepareForValidation()
    {
        if ($this->has('postal_code')) {
            $postal = mb_convert_kana($this->input('postal_code'), 'n');
            $postal = preg_replace('/\D+/', '', $postal);

            if (preg_match('/^\d{7}$/', $postal)) {
                $postal = substr($postal, 0, 3) . '-' . substr($postal, 3);
            }

            $this->merge([
                'postal_code' => $postal,
            ]);
        }
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
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'],
            'address'     => ['required', 'string'],
            'building'    => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex'    => '郵便番号は半角数字で「XXX-XXXX」の形式で入力してください',
            'address.required'     => '住所を入力してください',
            'building.required'    => '建物名を入力してください',
        ];
    }
}
