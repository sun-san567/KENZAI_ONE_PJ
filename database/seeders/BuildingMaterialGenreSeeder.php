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

            // 🔹 外部キー制約を一時的に無効化し、テーブルをクリア
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('users')->truncate();
            DB::table('companies')->truncate();
            DB::table('departments')->truncate();
            DB::table('phases')->truncate();
            DB::table('categories')->truncate();
            DB::table('clients')->truncate();
            DB::table('projects')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // 🔹 会社データを挿入
            $company1_id = DB::table('companies')->insertGetId([
                'name' => 'ABC建材株式会社',
                'address' => '東京都千代田区1-1-1',
                'phone' => '03-1234-5678',
                'email' => 'info@abc-kenzai.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $company2_id = DB::table('companies')->insertGetId([
                'name' => 'XYZ建材株式会社',
                'address' => '大阪府大阪市1-2-3',
                'phone' => '06-8765-4321',
                'email' => 'info@xyz-kenzai.com',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // 🔹 部門データを挿入
            $dept1_id = DB::table('departments')->insertGetId([
                'company_id' => $company1_id,
                'name' => '営業部',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $dept2_id = DB::table('departments')->insertGetId([
                'company_id' => $company1_id,
                'name' => 'マーケティング部',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $dept3_id = DB::table('departments')->insertGetId([
                'company_id' => $company2_id,
                'name' => '開発部',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // 🔹 フェーズデータを挿入（新しいフェーズ）
            $phases = ['下見積', '最終NET', '着工', '竣工', '引き渡し済み'];
            $phase_ids = [];

            foreach ($phases as $phase_name) {
                $phase_ids[$phase_name] = DB::table('phases')->insertGetId([
                    'department_id' => $dept1_id, // 仮に営業部と関連付け
                    'name' => $phase_name,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            // 🔹 ユーザーデータを挿入
            $user1_id = DB::table('users')->insertGetId([
                'name' => '山田 太郎',
                'email' => 'yamada' . uniqid() . '@example.com', // ユニーク化
                'password' => Hash::make('password'),
                'company_id' => $company1_id,
                'department_id' => $dept1_id,
                'role' => 'admin',
                'phone' => '080-1111-2222',
                'position' => '営業部長',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $user2_id = DB::table('users')->insertGetId([
                'name' => '鈴木 花子',
                'email' => 'suzuki' . uniqid() . '@example.com',
                'password' => Hash::make('password'),
                'company_id' => $company1_id,
                'department_id' => $dept2_id,
                'role' => 'manager',
                'phone' => '080-3333-4444',
                'position' => 'マーケティング担当',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $user3_id = DB::table('users')->insertGetId([
                'name' => '田中 一郎',
                'email' => 'tanaka' . uniqid() . '@example.com',
                'password' => Hash::make('password'),
                'company_id' => $company2_id,
                'department_id' => $dept3_id,
                'role' => 'user',
                'phone' => '090-5555-6666',
                'position' => 'エンジニア',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // 🔹 クライアントデータを挿入
            $client1_id = DB::table('clients')->insertGetId([
                'company_id' => $company1_id,
                'department_id' => $dept1_id,
                'user_id' => $user1_id,
                'name' => 'クライアントA',
                'phone' => '080-1234-5678',
                'address' => '東京都港区1-2-3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $client2_id = DB::table('clients')->insertGetId([
                'company_id' => $company2_id,
                'department_id' => $dept3_id,
                'user_id' => $user3_id,
                'name' => 'クライアントB',
                'phone' => '06-9876-5432',
                'address' => '大阪府大阪市4-5-6',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // 🔹 プロジェクトデータを挿入
            DB::table('projects')->insert([
                [
                    'company_id' => $company1_id,
                    'phase_id' => $phase_ids['下見積'],
                    'client_id' => $client1_id,
                    'name' => 'プロジェクトX',
                    'description' => 'ABC建材の下見積プロジェクト',
                    'revenue' => 1000000,
                    'profit' => 200000,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'company_id' => $company2_id,
                    'phase_id' => $phase_ids['着工'],
                    'client_id' => $client2_id,
                    'name' => 'プロジェクトY',
                    'description' => 'XYZ建材の施工プロジェクト',
                    'revenue' => 5000000,
                    'profit' => 800000,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Seeder 実行中にエラーが発生しました: " . $e->getMessage());
        }
    }
}
