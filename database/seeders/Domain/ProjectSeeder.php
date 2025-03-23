<?php

namespace Database\Seeders\Domain;

use Illuminate\Database\Seeder;
use App\Models\Project;
use App\Models\Client;
use App\Models\Phase;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::all();
        $phases = Phase::all();
        $users = User::all();
        $categories = Category::all();

        if ($clients->isEmpty() || $phases->isEmpty() || $users->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('⚠ 必須データが不足しています（Client, Phase, User, Category）');
            return;
        }

        foreach ($clients as $client) {
            $companyUsers = $users->where('company_id', $client->company_id);
            if ($companyUsers->isEmpty()) continue;

            $user = $companyUsers->where('department_id', $client->department_id)->random();
            $phase = $phases->where('department_id', $user->department_id)->random();

            foreach (range(1, 3) as $i) {
                $project = Project::create([
                    'client_id'         => $client->id,
                    'phase_id'          => $phase->id,
                    'name'              => "案件_{$client->name}_{$i}",
                    'description'       => "これはクライアント {$client->name} の案件 {$i} です。",
                    'revenue'           => rand(2_000_000, 10_000_000),
                    'profit'            => rand(300_000, 5_000_000),
                    'start_date'        => Carbon::now()->subDays(rand(10, 60)),
                    'end_date'          => Carbon::now()->addDays(rand(30, 90)),
                    'estimate_deadline' => Carbon::now()->addDays(rand(3, 20)),
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                ]);

                // ✅ 同じ会社のカテゴリから 2〜5件 ランダムで紐付け
                $companyCategories = $categories->where('company_id', $client->company_id);
                $selectedCategories = $companyCategories->random(rand(2, 5))->pluck('id')->toArray();

                $project->categories()->sync($selectedCategories);

                $this->command->info("✔ プロジェクト「{$project->name}」にカテゴリを紐付けました。");
            }
        }
    }
}
