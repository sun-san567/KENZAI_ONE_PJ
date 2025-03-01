<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BuildingMaterialGenreSeeder extends Seeder
{
    public function run()
    {
        try {
            DB::beginTransaction();

            // 会社データ
            DB::table('companies')->insert([
                [
                    'name' => 'ABC建材株式会社',
                    'address' => '東京都千代田区1-1-1',
                    'phone' => '03-1234-5678',
                    'email' => 'info@abc-kenzai.com',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'name' => 'XYZ建材株式会社',
                    'address' => '大阪府大阪市1-2-3',
                    'phone' => '06-8765-4321',
                    'email' => 'info@xyz-kenzai.com',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);

            // 会社IDを取得
            $company1 = DB::table('companies')->where('name', 'ABC建材株式会社')->first();
            $company2 = DB::table('companies')->where('name', 'XYZ建材株式会社')->first();

            // 部門データ
            DB::table('departments')->insert([
                ['company_id' => $company1->id, 'name' => '営業部', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['company_id' => $company1->id, 'name' => 'マーケティング部', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['company_id' => $company2->id, 'name' => '開発部', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]);

            // 部門IDを取得
            $dept1 = DB::table('departments')->where('name', '営業部')->first();
            $dept2 = DB::table('departments')->where('name', 'マーケティング部')->first();
            $dept3 = DB::table('departments')->where('name', '開発部')->first();

            // フェーズデータ追加（部門ごとに異なるフェーズ）
            DB::table('phases')->insert([
                // 営業部（ABC建材）
                ['department_id' => $dept1->id, 'name' => '企画', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['department_id' => $dept1->id, 'name' => '設計', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['department_id' => $dept1->id, 'name' => '施工', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['department_id' => $dept1->id, 'name' => '検査', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['department_id' => $dept1->id, 'name' => '完了', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

                // マーケティング部（ABC建材）
                ['department_id' => $dept2->id, 'name' => '市場調査', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['department_id' => $dept2->id, 'name' => 'キャンペーン企画', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['department_id' => $dept2->id, 'name' => '広告制作', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],

                // 開発部（XYZ建材）
                ['department_id' => $dept3->id, 'name' => '設計', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['department_id' => $dept3->id, 'name' => '試作', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['department_id' => $dept3->id, 'name' => '品質検査', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]);

            // ユーザーデータ（role カラムを追加）
            DB::table('users')->insert([
                [
                    'name' => '山田 太郎',
                    'email' => 'yamada@example.com',
                    'password' => bcrypt('password'),
                    'company_id' => $company1->id,
                    'department_id' => $dept1->id,
                    'role' => 'admin', // 管理者
                    'phone' => '080-1111-2222',
                    'position' => '営業部長',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'name' => '鈴木 花子',
                    'email' => 'suzuki@example.com',
                    'password' => bcrypt('password'),
                    'company_id' => $company1->id,
                    'department_id' => $dept2->id,
                    'role' => 'manager', // マーケティング責任者
                    'phone' => '080-3333-4444',
                    'position' => 'マーケティング担当',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'name' => '田中 一郎',
                    'email' => 'tanaka@example.com',
                    'password' => bcrypt('password'),
                    'company_id' => $company2->id,
                    'department_id' => $dept3->id,
                    'role' => 'user', // 一般社員
                    'phone' => '090-5555-6666',
                    'position' => 'エンジニア',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);

            // 建材ジャンル
            DB::table('categories')->insert([
                ['name' => 'コンクリート', 'company_id' => $company1->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => '木材', 'company_id' => $company1->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => '金属', 'company_id' => $company2->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'ガラス', 'company_id' => $company2->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            echo "Seeder 実行中にエラーが発生しました: " . $e->getMessage() . "\n";
        }
    }
}
