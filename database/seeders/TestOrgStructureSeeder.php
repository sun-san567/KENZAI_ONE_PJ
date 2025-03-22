<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TestOrgStructureSeeder extends Seeder
{
    /**
     * テスト用の会社・部門・ユーザーを作成するシーダー
     */
    public function run(): void
    {
        // 1. テスト会社の作成
        $company = Company::create([
            'name' => 'テスト建設株式会社',
            'address' => '東京都千代田区丸の内1-1-1',
            'phone' => '03-1234-5678',
            'email' => 'info@test-construction.example.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // 2. 部門の作成（実際のカラム構造に合わせて修正）
        $departmentNames = [
            '営業部',
            '技術部',
            '施工管理部',
            '管理部'
        ];

        $createdDepartments = [];
        foreach ($departmentNames as $deptName) {
            $dept = Department::create([
                'company_id' => $company->id,
                'name' => $deptName,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            $createdDepartments[$dept->name] = $dept;
        }

        // 3. ユーザーの作成
        $users = [
            [
                'name' => '山田太郎',
                'email' => 'yamada@example.com',
                'password' => Hash::make('password123'),
                'department_id' => $createdDepartments['管理部']->id,
                'role' => 'admin',
                'position' => '部長',
                'phone' => '080-1234-5678'
            ],
            [
                'name' => '鈴木一郎',
                'email' => 'suzuki@example.com',
                'password' => Hash::make('password123'),
                'department_id' => $createdDepartments['技術部']->id,
                'role' => 'manager',
                'position' => '課長',
                'phone' => '080-2345-6789'
            ],
            [
                'name' => '佐藤花子',
                'email' => 'sato@example.com',
                'password' => Hash::make('password123'),
                'department_id' => $createdDepartments['管理部']->id,
                'role' => 'member',
                'position' => '主任',
                'phone' => '080-3456-7890'
            ],
            [
                'name' => '田中勇気',
                'email' => 'tanaka@example.com',
                'password' => Hash::make('password123'),
                'department_id' => $createdDepartments['営業部']->id,
                'role' => 'member',
                'position' => '担当',
                'phone' => '080-4567-8901'
            ],
            [
                'name' => '佐々木健太',
                'email' => 'sasaki@example.com',
                'password' => Hash::make('password123'),
                'department_id' => $createdDepartments['技術部']->id,
                'role' => 'member',
                'position' => '担当',
                'phone' => '080-5678-9012'
            ],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'email_verified_at' => Carbon::now(),
                'password' => $userData['password'],
                'remember_token' => Str::random(10),
                'company_id' => $company->id,
                'department_id' => $userData['department_id'],
                'role' => $userData['role'],
                'position' => $userData['position'],
                'phone' => $userData['phone'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }

        $this->command->info('テスト組織構成データを作成しました');
    }
}
