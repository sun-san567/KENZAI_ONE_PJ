<?php

namespace Database\Seeders\Domain;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::with(['departments', 'users'])->get();

        if ($companies->isEmpty()) {
            $this->command->warn('⚠ 会社が存在しません。ClientSeederをスキップします。');
            return;
        }

        foreach ($companies as $company) {
            $departments = $company->departments;
            $users = $company->users;

            foreach (range(1, 10) as $i) {
                // ランダムな部門とその部門に属するユーザーを選択
                $department = $departments->random();
                $user = $users->where('department_id', $department->id)->random();

                Client::create([
                    'company_id'    => $company->id,
                    'department_id' => $department->id,
                    'user_id'       => $user->id,
                    'name'          => "クライアント{$i}_{$company->id}",
                    'phone'         => '03-' . rand(1000, 9999) . '-' . rand(1000, 9999),
                    'address'       => '東京都〇〇区' . rand(1, 100) . '丁目',
                    'created_at'    => Carbon::now(),
                    'updated_at'    => Carbon::now(),
                ]);
            }

            $this->command->info("✔ クライアントを会社「{$company->name}」に10件作成しました。");
        }
    }
}
