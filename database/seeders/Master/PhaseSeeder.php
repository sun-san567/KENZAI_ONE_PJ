<?php

namespace Database\Seeders\Master;

use Illuminate\Database\Seeder;
use App\Models\Phase;
use App\Models\Department;
use Carbon\Carbon;

class PhaseSeeder extends Seeder
{
    /**
     * 各部門に紐づくフェーズデータを作成
     */
    public function run(): void
    {
        // フェーズテンプレート（並び順含む）
        $phases = [
            ['name' => '営業中', 'description' => '顧客対応中・提案中'],
            ['name' => '契約済', 'description' => '契約が締結された状態'],
            ['name' => '着工',   'description' => '工事開始状態'],
            ['name' => '完了',   'description' => '工事完了・引き渡し済'],
        ];

        $departments = Department::all();

        if ($departments->isEmpty()) {
            $this->command->warn('部門データが存在しません。フェーズの作成をスキップします。');
            return;
        }

        foreach ($departments as $department) {
            foreach ($phases as $index => $phaseData) {
                Phase::create([
                    'department_id' => $department->id,
                    'name'          => $phaseData['name'],
                    'description'   => $phaseData['description'],
                    'order'         => $index + 1,
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ]);
            }

            $this->command->info("✔ 部門「{$department->name}」にフェーズを作成しました。");
        }
    }
}
