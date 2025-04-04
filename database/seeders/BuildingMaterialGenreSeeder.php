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
        echo "✅ BuildingMaterialGenreSeeder 開始\n";
        // 初期化（テーブルクリア）
        $this->cleanTables();

        // 1. 会社データを作成
        $company_ids = $this->createCompanies();

        // 2. 部門データを作成（会社ごと）
        $department_ids = $this->createDepartments($company_ids);

        // 3. フェーズデータを作成
        $phase_ids = $this->createPhases($department_ids);

        // 4. ユーザーデータを作成（会社ごと）
        $user_ids = $this->createUsers($company_ids, $department_ids);

        // 5. カテゴリデータを作成（会社ごと）
        $category_ids = $this->createCategories($company_ids);

        // 6. クライアントとプロジェクトデータを作成（会社ごと）
        $this->createClientsAndProjects($company_ids, $department_ids, $user_ids, $phase_ids, $category_ids);

        // 最終確認
        $this->verifyResults($company_ids);
    }

    private function cleanTables()
    {
        \Log::info("テーブルクリア開始");

        // 外部キー制約の無効化
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // テーブルのクリア
        DB::table('project_categories')->truncate();
        DB::table('projects')->truncate();
        DB::table('clients')->truncate();
        DB::table('users')->truncate();
        DB::table('categories')->truncate();
        DB::table('phases')->truncate();
        DB::table('departments')->truncate();
        DB::table('companies')->truncate();

        // 外部キー制約の有効化
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        \Log::info("テーブルクリア完了");
    }

    private function createCompanies()
    {
        \Log::info("会社データ作成開始");

        $company_names = [
            '株式会社建材商事',
            '株式会社東京建材',
            '大阪建材株式会社',
            '名古屋建材株式会社',
            '福岡建材株式会社'
        ];

        $company_ids = [];

        foreach ($company_names as $index => $name) {
            $id = DB::table('companies')->insertGetId([
                'name' => $name,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $company_ids[] = $id;
            \Log::info("会社作成成功: ID={$id}, 名前={$name}");
        }

        \Log::info("会社データ作成完了: " . count($company_ids) . "件");
        \Log::info("会社ID: " . implode(', ', $company_ids));

        return $company_ids;
    }

    private function createDepartments($company_ids)
    {
        \Log::info("部門データ作成開始");
        $departments = ['営業部', '技術部', '管理部', '企画部', '総務部'];
        $department_ids = [];

        foreach ($company_ids as $company_id) {
            $department_ids[$company_id] = [];
            \Log::info("会社ID={$company_id} の部門作成開始");

            // 各会社には全ての部門を作成（会社IDを付加して一意にする）
            foreach ($departments as $dept_name) {
                // 会社ごとに一意の部門名を作成（例: 営業部-1, 営業部-2）
                $unique_dept_name = $dept_name . '-' . $company_id;

                try {
                    $dept_id = DB::table('departments')->insertGetId([
                        'company_id' => $company_id,
                        'name' => $unique_dept_name, // 一意の部門名
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    // インデックスは元の部門名で管理（後続処理で使用）
                    $department_ids[$company_id][$dept_name] = $dept_id;
                    \Log::info("部門作成: 会社ID={$company_id}, 部門名={$unique_dept_name}, ID={$dept_id}");
                } catch (\Exception $e) {
                    \Log::error("部門作成エラー: {$e->getMessage()}");
                }
            }

            // 部門作成の検証
            $created_count = DB::table('departments')->where('company_id', $company_id)->count();
            \Log::info("会社ID={$company_id} の部門作成結果: {$created_count}件");
        }

        return $department_ids;
    }

    private function createPhases($department_ids)
    {
        \Log::info("フェーズデータ作成開始");

        // フェーズ定義（会社ID=1の部門IDを使用）
        $company_id_for_phases = 1;
        $phases = [
            ['name' => '初期相談', 'order' => 1, 'department_name' => '営業部', 'description' => '顧客との初回打ち合わせ'],
            ['name' => '下見積作成', 'order' => 2, 'department_name' => '営業部', 'description' => '概算見積の作成'],
            ['name' => '本見積作成', 'order' => 3, 'department_name' => '営業部', 'description' => '詳細見積の作成'],
            ['name' => '発注', 'order' => 4, 'department_name' => '営業部', 'description' => '顧客からの発注受付'],
            ['name' => '受注処理', 'order' => 5, 'department_name' => '営業部', 'description' => '受注内容の確認と社内手続き'],
            ['name' => '納品', 'order' => 6, 'department_name' => '技術部', 'description' => '製品の納品と設置'],
            ['name' => '請求', 'order' => 7, 'department_name' => '管理部', 'description' => '顧客への請求処理'],
            ['name' => '入金確認', 'order' => 8, 'department_name' => '管理部', 'description' => '入金状況の確認'],
            ['name' => 'アフターフォロー', 'order' => 9, 'department_name' => '営業部', 'description' => '納品後のフォローアップ'],
            ['name' => '完了', 'order' => 10, 'department_name' => '営業部', 'description' => 'プロジェクト完了処理'],
        ];

        $phase_ids = [];

        // すでに作成されているフェーズを確認
        $existing_phases = DB::table('phases')->get();
        if ($existing_phases->count() > 0) {
            \Log::info("既存のフェーズデータが見つかりました: {$existing_phases->count()}件");

            // 既存データを使用
            foreach ($existing_phases as $phase) {
                $phase_ids[$phase->name] = $phase->id;
                \Log::info("既存フェーズ使用: 名前={$phase->name}, ID={$phase->id}");
            }

            return $phase_ids;
        }

        // 新規作成
        foreach ($phases as $phase) {
            try {
                // 部門IDの取得
                if (!isset($department_ids[$company_id_for_phases][$phase['department_name']])) {
                    \Log::warning("フェーズ「{$phase['name']}」の作成: 会社ID={$company_id_for_phases}の部門「{$phase['department_name']}」が見つかりません");
                    // 続行するために会社1の最初の部門を使用
                    $dept_id = array_values($department_ids[$company_id_for_phases])[0] ?? null;
                    if (!$dept_id) {
                        \Log::error("代替部門も見つかりません。フェーズ作成をスキップします");
                        continue;
                    }
                    \Log::info("代替部門を使用: ID={$dept_id}");
                } else {
                    $dept_id = $department_ids[$company_id_for_phases][$phase['department_name']];
                }

                $phase_id = DB::table('phases')->insertGetId([
                    'name' => $phase['name'],
                    'description' => $phase['description'] ?? null,
                    'order' => $phase['order'],
                    'department_id' => $dept_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $phase_ids[$phase['name']] = $phase_id;
                \Log::info("フェーズ作成: 名前={$phase['name']}, 部門ID={$dept_id}, ID={$phase_id}");
            } catch (\Exception $e) {
                \Log::error("フェーズ作成エラー: {$e->getMessage()}");
            }
        }

        return $phase_ids;
    }

    private function createUsers($company_ids, $department_ids)
    {
        \Log::info("ユーザーデータ作成開始");

        // ユーザーテンプレート
        $user_templates = [
            [
                'name' => '山田太郎',
                'email_prefix' => 'yamada',
                'department' => '営業部',
                'role' => 'admin',
                'position' => '部長'
            ],
            [
                'name' => '鈴木一郎',
                'email_prefix' => 'suzuki',
                'department' => '技術部',
                'role' => 'manager',
                'position' => '課長'
            ],
            [
                'name' => '佐藤花子',
                'email_prefix' => 'sato',
                'department' => '管理部',
                'role' => 'member',
                'position' => '主任'
            ],
            [
                'name' => '田中勇気',
                'email_prefix' => 'tanaka',
                'department' => '営業部',
                'role' => 'member',
                'position' => '担当'
            ],
            [
                'name' => '佐々木健太',
                'email_prefix' => 'sasaki',
                'department' => '技術部',
                'role' => 'member',
                'position' => '担当'
            ]
        ];

        $user_ids = [];

        foreach ($company_ids as $company_id) {
            $user_ids[$company_id] = [];
            \Log::info("会社ID={$company_id} のユーザー作成開始");

            if (empty($department_ids[$company_id])) {
                \Log::error("会社ID={$company_id} の部門が見つかりません");
                continue;
            }

            foreach ($user_templates as $index => $template) {
                try {
                    // 部門IDの取得
                    if (!isset($department_ids[$company_id][$template['department']])) {
                        \Log::warning("会社ID={$company_id} に部門「{$template['department']}」が見つかりません");
                        continue;
                    }

                    $department_id = $department_ids[$company_id][$template['department']];

                    // 会社ごとに一意のメールアドレスを生成
                    $email = $template['email_prefix'] . $company_id . '@example.com';

                    // メールアドレスの重複チェック
                    $exists = DB::table('users')->where('email', $email)->exists();
                    if ($exists) {
                        $email = $template['email_prefix'] . $company_id . '_' . uniqid() . '@example.com';
                    }

                    $user_id = DB::table('users')->insertGetId([
                        'name' => $template['name'] . '-' . $company_id,
                        'email' => $email,
                        'password' => Hash::make('password123'),
                        'company_id' => $company_id,
                        'department_id' => $department_id,
                        'role' => $template['role'],
                        'position' => $template['position'],
                        'phone' => sprintf('080-%04d-%04d', rand(1000, 9999), rand(1000, 9999)),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $user_ids[$company_id][$template['name']] = $user_id;
                    \Log::info("ユーザー作成: 会社ID={$company_id}, 名前={$template['name']}-{$company_id}, ID={$user_id}");
                } catch (\Exception $e) {
                    \Log::error("ユーザー作成エラー: 会社ID={$company_id}, 名前={$template['name']}, エラー: {$e->getMessage()}");
                    // エラーのスタックトレースを出力
                    \Log::error($e->getTraceAsString());
                }
            }

            // ユーザー作成結果の検証
            $created_count = DB::table('users')->where('company_id', $company_id)->count();
            \Log::info("会社ID={$company_id} のユーザー作成結果: {$created_count}件");
        }

        \Log::info("ユーザーデータ作成完了");
        return $user_ids;
    }

    private function createCategories($company_ids)
    {
        \Log::info("カテゴリデータ作成開始");

        $category_names = [
            '住宅設備',
            '内装材',
            '外装材',
            '構造材',
            '基礎材',
            '屋根材',
            '断熱材',
            '防水材',
            '建具',
            '電気設備'
        ];

        $category_ids = [];

        foreach ($company_ids as $company_id) {
            $category_ids[$company_id] = [];
            \Log::info("会社ID={$company_id} のカテゴリ作成開始");

            // 会社ごとにランダムなカテゴリを5つ選択
            $selected_categories = array_rand(array_flip($category_names), 5);

            foreach ($selected_categories as $cat_name) {
                try {
                    // 会社ごとに一意のカテゴリ名を生成
                    $unique_cat_name = $cat_name . '-' . $company_id;

                    $cat_id = DB::table('categories')->insertGetId([
                        'name' => $unique_cat_name,
                        'company_id' => $company_id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    $category_ids[$company_id][] = $cat_id;
                    \Log::info("カテゴリ作成: 会社ID={$company_id}, 名前={$unique_cat_name}, ID={$cat_id}");
                } catch (\Exception $e) {
                    \Log::error("カテゴリ作成エラー: {$e->getMessage()}");
                }
            }

            // カテゴリ作成結果の検証
            $created_count = DB::table('categories')->where('company_id', $company_id)->count();
            \Log::info("会社ID={$company_id} のカテゴリ作成結果: {$created_count}件");
        }

        \Log::info("カテゴリデータ作成完了");
        return $category_ids;
    }

    private function createClientsAndProjects($company_ids, $department_ids, $user_ids, $phase_ids, $category_ids)
    {
        \Log::info("クライアント・プロジェクト作成開始");

        // フェーズID確認
        if (empty($phase_ids)) {
            // DBから直接フェーズを取得
            $db_phases = DB::table('phases')->get();
            if ($db_phases->count() > 0) {
                foreach ($db_phases as $phase) {
                    $phase_ids[$phase->name] = $phase->id;
                }
                \Log::info("DB から取得したフェーズID: " . json_encode($phase_ids));
            } else {
                \Log::error("有効なフェーズデータがありません。プロジェクト作成をスキップします。");
                return;
            }
        }

        \Log::info("使用するフェーズID: " . json_encode($phase_ids));

        // 各会社に対してクライアントとプロジェクトを作成
        foreach ($company_ids as $company_id) {
            \Log::info("会社ID={$company_id} のクライアント・プロジェクト作成開始");

            // ユーザー存在確認（営業部のユーザーが担当者に）
            if (empty($user_ids[$company_id])) {
                \Log::warning("会社ID={$company_id} にユーザーがありません。ダミーユーザーを作成します。");

                // ダミーユーザー作成
                try {
                    $dummy_dept_id = DB::table('departments')
                        ->where('company_id', $company_id)
                        ->value('id');

                    if ($dummy_dept_id) {
                        $dummy_user_id = DB::table('users')->insertGetId([
                            'name' => "ダミーユーザー-{$company_id}",
                            'email' => "dummy.{$company_id}@example.com",
                            'password' => Hash::make('password'),
                            'company_id' => $company_id,
                            'department_id' => $dummy_dept_id,
                            'role' => 'manager',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);

                        $user_ids[$company_id] = [$dummy_user_id];
                        \Log::info("ダミーユーザー作成: ID={$dummy_user_id}");
                    } else {
                        \Log::error("会社ID={$company_id} に有効な部門がありません。クライアント作成をスキップします。");
                        continue;
                    }
                } catch (\Exception $e) {
                    \Log::error("ダミーユーザー作成エラー: {$e->getMessage()}");
                    continue;
                }
            }

            // クライアント作成（各会社に3つ）
            for ($i = 1; $i <= 3; $i++) {
                try {
                    // ユーザーIDを取得（ランダム or 先頭）
                    $selected_user_id = is_array($user_ids[$company_id])
                        ? array_values($user_ids[$company_id])[array_rand($user_ids[$company_id])]
                        : $user_ids[$company_id];

                    $client_name = "クライアント{$company_id}-{$i}";
                    $client_id = DB::table('clients')->insertGetId([
                        'name' => $client_name,
                        'company_id' => $company_id,
                        'user_id' => $selected_user_id, // ✅ ここをシンプルに修正
                        'address' => '東京都港区芝浦' . rand(1, 5) . '-' . rand(1, 20) . '-' . rand(1, 30),
                        'phone' => sprintf('03-%04d-%04d', rand(1000, 9999), rand(1000, 9999)),
                        'email' => 'client' . $i . '-' . $company_id . '@example.com',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);

                    \Log::info("クライアント作成: 会社ID={$company_id}, 名前={$client_name}, ID={$client_id}");

                    // プロジェクト作成（各クライアントに1つ）
                    try {
                        // フェーズIDをランダムに選択
                        $random_phase_key = array_rand($phase_ids);
                        $random_phase_id = $phase_ids[$random_phase_key];

                        \Log::info("選択されたフェーズ: {$random_phase_key}, ID={$random_phase_id}");

                        // 必要なデータ準備
                        $revenue = rand(1000000, 50000000);
                        $profit = (int) ($revenue * (rand(10, 30) / 100));

                        $project_name = "プロジェクト{$company_id}-{$i}";

                        // 日付フォーマット
                        $now = Carbon::now();
                        $estimate_deadline = $now->copy()->addDays(rand(5, 30));
                        $start_date = $now->copy()->addDays(rand(1, 10));
                        $end_date = $start_date->copy()->addDays(rand(30, 120));

                        $project_data = [
                            'name' => $project_name,
                            'phase_id' => $random_phase_id,
                            'client_id' => $client_id,
                            'revenue' => $revenue,
                            'profit' => $profit,
                            'estimate_deadline' => $estimate_deadline,
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];

                        $project_id = DB::table('projects')->insertGetId($project_data);

                        \Log::info("プロジェクト作成成功: 名前={$project_name}, ID={$project_id}");
                    } catch (\Exception $e) {
                        \Log::error("プロジェクト作成エラー: 会社ID={$company_id}, クライアントID={$client_id}");
                        \Log::error("エラー詳細: " . $e->getMessage());
                    }
                } catch (\Exception $e) {
                    \Log::error("クライアント作成エラー: 会社ID={$company_id}, インデックス={$i}");
                    \Log::error("エラー詳細: " . $e->getMessage());
                }
            }

            // 作成結果確認
            $client_count = DB::table('clients')->where('company_id', $company_id)->count();
            $project_count = DB::table('projects')
                ->join('clients', 'projects.client_id', '=', 'clients.id')
                ->where('clients.company_id', $company_id)
                ->count();

            \Log::info("会社ID={$company_id} の作成結果: クライアント {$client_count}件, プロジェクト {$project_count}件");
        }

        \Log::info("クライアント・プロジェクト作成完了");
    }

    private function verifyResults($company_ids)
    {
        \Log::info("=============== 最終データ確認 ===============");

        \Log::info("会社: " . DB::table('companies')->count() . "件");
        \Log::info("部門: " . DB::table('departments')->count() . "件");
        \Log::info("ユーザー: " . DB::table('users')->count() . "件");
        \Log::info("フェーズ: " . DB::table('phases')->count() . "件");
        \Log::info("カテゴリ: " . DB::table('categories')->count() . "件");
        \Log::info("クライアント: " . DB::table('clients')->count() . "件");
        \Log::info("プロジェクト: " . DB::table('projects')->count() . "件");

        foreach ($company_ids as $company_id) {
            \Log::info("---- 会社ID={$company_id} の詳細 ----");
            \Log::info("  部門: " . DB::table('departments')->where('company_id', $company_id)->count() . "件");
            \Log::info("  ユーザー: " . DB::table('users')->where('company_id', $company_id)->count() . "件");
            \Log::info("  カテゴリ: " . DB::table('categories')->where('company_id', $company_id)->count() . "件");
            \Log::info("  クライアント: " . DB::table('clients')->where('company_id', $company_id)->count() . "件");

            $project_count = DB::table('projects')
                ->join('clients', 'projects.client_id', '=', 'clients.id')
                ->where('clients.company_id', $company_id)
                ->count();

            \Log::info("  プロジェクト: {$project_count}件");
        }

        \Log::info("=============== シーダー実行完了 ===============");
    }
}
