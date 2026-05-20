<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExhibitionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required'],
            'brand_name' => ['nullable', 'string'],
            'description' => ['required', 'max:255'],
            'image' => [
                'required',
                'file',
                'mimes:jpeg,png',
                function ($attribute, $value, $fail) {
                    if (!in_array(strtolower($value->getClientOriginalExtension()), ['jpeg', 'png'], true)) {
                        $fail('商品画像はjpegまたはpng形式でアップロードしてください');
                    }
                },
            ],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
            'condition' => [
                'required',
                Rule::in([
                    '良好',
                    '目立った傷や汚れなし',
                    'やや傷や汚れあり',
                    '状態が悪い',
                ]),
            ],
            'price' => ['required', 'integer', 'min:0', 'max:2147483647'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',
            'image.required' => '商品画像をアップロードしてください',
            'image.file' => '商品画像をアップロードしてください',
            'image.mimes' => '商品画像はjpegまたはpng形式でアップロードしてください',
            'categories.required' => '商品のカテゴリーを選択してください',
            'categories.array' => '商品のカテゴリーを選択してください',
            'categories.min' => '商品のカテゴリーを選択してください',
            'categories.*.exists' => '商品のカテゴリーを正しく選択してください',
            'condition.required' => '商品の状態を選択してください',
            'condition.in' => '商品の状態を正しく選択してください',
            'price.required' => '商品価格を入力してください',
            'price.integer' => '商品価格は整数で入力してください',
            'price.min' => '商品価格は0円以上で入力してください',
            'price.max' => '商品価格は2147483647円以下で入力してください',
        ];
    }
}