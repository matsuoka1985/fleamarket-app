<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $items = [
            ['title' => '腕時計', 'description' => 'スタイリッシュなデザインのメンズ腕時計', 'price' => 15000, 'condition' => '良好'],
            ['title' => 'HDD', 'description' => '高速で信頼性の高いハードディスク', 'price' => 5000, 'condition' => '目立った傷や汚れなし'],
            ['title' => '玉ねぎ3束', 'description' => '新鮮な玉ねぎ3束のセット', 'price' => 300, 'condition' => 'やや傷や汚れあり'],
            ['title' => '革靴', 'description' => 'クラシックなデザインの革靴', 'price' => 4000, 'condition' => '状態が悪い'],
            ['title' => 'ノートPC', 'description' => '高性能なノートパソコン', 'price' => 45000, 'condition' => '良好'],
            ['title' => 'マイク', 'description' => '高音質のレコーディング用マイク', 'price' => 8000, 'condition' => '目立った傷や汚れなし'],
            ['title' => 'ショルダーバッグ', 'description' => 'おしゃれなショルダーバッグ', 'price' => 3500, 'condition' => 'やや傷や汚れあり'],
            ['title' => 'タンブラー', 'description' => '使いやすいタンブラー', 'price' => 500, 'condition' => '状態が悪い'],
            ['title' => 'コーヒーミル', 'description' => '手動のコーヒーミル', 'price' => 4000, 'condition' => '良好'],
            ['title' => 'メイクセット', 'description' => '便利なメイクアップセット', 'price' => 2500, 'condition' => '目立った傷や汚れなし'],
        ];

        foreach ($items as $item) {
            Item::factory()->create(array_merge($item, [
                'status' => 'on_sale',
                'user_id' => User::inRandomOrder()->first()->id,
            ]));
        }
    }
}
