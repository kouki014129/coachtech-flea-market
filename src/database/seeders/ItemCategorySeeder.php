<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    public function run()
    {
        $itemCategories = [
            '腕時計' => ['メンズ', 'ファッション', 'アクセサリー'],
            'HDD' => ['家電'],
            '玉ねぎ3束' => ['キッチン'],
            '革靴' => ['メンズ', 'ファッション'],
            'ノートPC' => ['家電'],
            'マイク' => ['家電'],
            'ショルダーバッグ' => ['レディース', 'ファッション'],
            'タンブラー' => ['キッチン'],
            'コーヒーミル' => ['キッチン', '家電'],
            'メイクセット' => ['コスメ'],
        ];

        foreach ($itemCategories as $itemName => $categoryNames) {
            $item = Item::query()->where('name', $itemName)->first();

            if (!$item) {
                continue;
            }

            $categoryIds = Category::query()
                ->whereIn('name', $categoryNames)
                ->pluck('id');

            $item->categories()->sync($categoryIds);
        }
    }
}
