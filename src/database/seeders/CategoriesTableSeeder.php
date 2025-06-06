<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $categories = [
            ['key' => 'fashion',     'label' => 'ファッション'],
            ['key' => 'appliances',  'label' => '家電'],
            ['key' => 'interior',    'label' => 'インテリア'],
            ['key' => 'ladies',      'label' => 'レディース'],
            ['key' => 'mens',        'label' => 'メンズ'],
            ['key' => 'cosmetics',   'label' => 'コスメ'],
            ['key' => 'books',       'label' => '本'],
            ['key' => 'games',       'label' => 'ゲーム'],
            ['key' => 'sports',      'label' => 'スポーツ'],
            ['key' => 'kitchen',     'label' => 'キッチン'],
            ['key' => 'handmade',    'label' => 'ハンドメイド'],
            ['key' => 'accessory',   'label' => 'アクセサリー'],
            ['key' => 'toys',        'label' => 'おもちゃ'],
            ['key' => 'baby_kids',   'label' => 'ベビー・キッズ'],
            ['key' => 'clothes',     'label' => '洋服'],
        ];

        foreach ($categories as $i => $category) {
            DB::table('categories')->insert([
                'key' => $category['key'],
                'label' => $category['label'],
                'sort_order' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
