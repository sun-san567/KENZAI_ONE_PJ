<?php

namespace Database\Seeders\Master;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Company;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categoryNames = [
            'システムキッチン',
            'ユニットバス',
            'トイレ',
            '洗面台',
            'エアコン',
            '換気扇',
            '床暖房',
            '照明器具',
            'スイッチ・コンセント',
            '分電盤',
            '給湯器',
            'インターホン',
            '防犯設備',
            '床材',
            'クロス',
            '建具',
            '収納',
            'ダイニングセット',
            '造作家具',
            '収納棚',
            'フェンス',
            'カーポート',
            'ポスト',
            '玄関アプローチ',
            'インテリア',
            'カーテン',
            '家電',
            'IoT機器'
        ];

        $companies = Company::all();

        foreach ($companies as $company) {
            foreach ($categoryNames as $name) {
                Category::create([
                    'company_id' => $company->id,
                    'name'       => $name,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $this->command->info("✔ 提案カテゴリを会社「{$company->name}」に登録しました。");
        }
    }
}
