<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BuildingMaterialGenreSeeder extends Seeder
{
    public function run()
    {
        try {
            DB::beginTransaction();

            // 🔹 外部キー制約を無効化し、データをリセット
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('users')->truncate();
            DB::table('companies')->truncate();
            DB::table('departments')->truncate();
            DB::table('phases')->truncate();
            DB::table('clients')->truncate();
            DB::table('projects')->truncate();
            DB::table('categories')->truncate();
            DB::table('project_categories')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // 🔹 会社データを作成
            $company_id = DB::table('companies')->insertGetId([
                'name' => '建材商社株式会社',
                'address' => '東京都中央区日本橋1-1-1',
                'phone' => '03-1234-5678',
                'email' => 'info@kenzai-corp.co.jp',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // 🔹 部門データ
            $departments = ['営業部', '技術部', '管理部'];
            $department_ids = [];
            foreach ($departments as $name) {
                $department_ids[$name] = DB::table('departments')->insertGetId([
                    'company_id' => $company_id,
                    'name' => $name,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            // 🔹 ユーザーを作成（シードデータを適用）
            $users = [
                ['name' => '山田 太郎', 'email' => 'yamada' . uniqid() . '@kenzai-corp.co.jp', 'department' => '営業部', 'role' => 'admin', 'position' => '営業部長'],
                ['name' => '鈴木 一郎', 'email' => 'suzuki' . uniqid() . '@kenzai-corp.co.jp', 'department' => '技術部', 'role' => 'manager', 'position' => '技術部長'],
                ['name' => '佐藤 花子', 'email' => 'sato' . uniqid() . '@kenzai-corp.co.jp', 'department' => '管理部', 'role' => 'user', 'position' => '主任'],
            ];
            $user_ids = [];
            foreach ($users as $user) {
                $user_ids[$user['name']] = DB::table('users')->insertGetId([
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => Hash::make('password123'),
                    'company_id' => $company_id,
                    'department_id' => $department_ids[$user['department']],
                    'role' => $user['role'],
                    'position' => $user['position'],
                    'phone' => '080-' . rand(1000, 9999) . '-' . rand(1000, 9999),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            // 🔹 フェーズデータ
            $phases = [
                ['name' => '初期相談', 'order' => 1, 'department' => '営業部'],
                ['name' => '下見積作成', 'order' => 2, 'department' => '営業部'],
                ['name' => '最終見積提出', 'order' => 3, 'department' => '営業部'],
                ['name' => '契約', 'order' => 4, 'department' => '営業部'],
                ['name' => '着工', 'order' => 5, 'department' => '技術部'],
                ['name' => '施工中', 'order' => 6, 'department' => '技術部'],
                ['name' => '竣工', 'order' => 7, 'department' => '技術部'],
                ['name' => '引き渡し済み', 'order' => 8, 'department' => '管理部'],
            ];
            $phase_ids = [];
            foreach ($phases as $phase) {
                $phase_ids[$phase['name']] = DB::table('phases')->insertGetId([
                    'name' => $phase['name'],
                    'order' => $phase['order'],
                    'department_id' => $department_ids[$phase['department']],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            // 🔹 カテゴリデータ
            $categories = ['新築工事', 'リフォーム', '設備交換', '内装工事', '外装工事', '耐震補強', '省エネ改修'];
            $category_ids = [];
            foreach ($categories as $category) {
                $category_ids[] = DB::table('categories')->insertGetId([
                    'name' => $category,
                    'company_id' => $company_id, // ✅ `company_id` を追加
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            // 🔹 クライアントデータ
            $clients = [];
            for ($i = 1; $i <= 10; $i++) {
                $clients[] = [
                    'company_id' => $company_id,
                    'department_id' => $department_ids['営業部'],
                    'user_id' => $user_ids[array_rand($user_ids)],
                    'name' => "クライアント{$i}",
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            DB::table('clients')->insert($clients);
            $client_ids = DB::table('clients')->pluck('id')->toArray();

            // 🔹 プロジェクトデータ
            for ($i = 1; $i <= 10; $i++) {
                $revenue = rand(1000000, 50000000);
                $profit = (int) ($revenue * (rand(10, 30) / 100));

                $project_id = DB::table('projects')->insertGetId([
                    'name' => "プロジェクト{$i}",
                    'phase_id' => $phase_ids[array_rand($phase_ids)],
                    'client_id' => $client_ids[array_rand($client_ids)],
                    'revenue' => $revenue,
                    'profit' => $profit,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                // 🔹 プロジェクトとカテゴリの紐付け
                $selected_categories = array_rand(array_flip($category_ids), rand(2, 4));
                foreach ((array) $selected_categories as $category_id) {
                    DB::table('project_categories')->insert([
                        'project_id' => $project_id,
                        'category_id' => $category_id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("BuildingMaterialGenreSeeder 実行中にエラー: " . $e->getMessage());
            dd($e->getMessage()); // ✅ デバッグ用
        }
    }
}
