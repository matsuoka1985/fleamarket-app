<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;


class CategoryItemTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // key => id 対応マップ作成

        $categoryMap = Category::pluck('id', 'key'); //categoryテーブルの主キーと論理名の組。

        $mapping = [ //itemsテーブルのnameカラムの値と、categoryテーブルのkeyカラムの値の対応。
            '腕時計'           => ['fashion', 'mens'],
            'HDD'              => ['appliances'],
            '玉ねぎ3束'        => ['kitchen'],
            '革靴'             => ['fashion', 'mens'],
            'ノートPC'         => ['appliances'],
            'マイク'           => ['appliances'],
            'ショルダーバッグ' => ['fashion', 'ladies'],
            'タンブラー'       => ['kitchen'],
            'コーヒーミル'     => ['kitchen'],
            'メイクセット'     => ['cosmetics'],
        ];

        foreach ($mapping as $title => $categoryKeys) {
            $item = Item::where('title', $title)->first(); //各々のアイテムを取得

            if ($item) {
                $categoryIds = collect($categoryKeys) //各々のアイテムに対応するカテゴリーキーを取得
                    ->map(fn($key) => $categoryMap[$key] ?? null) // $categoryKeysをループさせてCategoryテーブルのidカラムの値を取得
                    ->filter() //null除去
                    ->toArray(); //collectionから元の配列に戻す。

                $item->categories()->sync($categoryIds); //itemテーブルとcategoryテーブルの中間テーブルにデータ挿入
            }
        }
    }
}
