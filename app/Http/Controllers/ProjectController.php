<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Phase;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;


class ProjectController extends Controller
{
    /**
     * 案件一覧を表示
     */
    public function index()
    {
        // フェーズ、顧客、カテゴリ、プロジェクト情報を取得
        $phases = Phase::all();
        $clients = Client::all();
        $categories = Category::all();
        $projects = Project::with(['phase', 'categories'])->get()->groupBy('phase_id');

        return view('projects.index', compact('phases', 'clients', 'categories', 'projects'));
    }

    /**
     * 案件作成ページ
     */
    public function create()
    {
        $phases = Phase::orderBy('order')->get();
        return view('projects.create', compact('phases'));
    }

    /**
     * 案件保存処理
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phase_id' => 'required|exists:phases,id',
            'client_id' => 'required|exists:clients,id',
            'category_id' => 'array', // 複数カテゴリ選択可能
            'category_id.*' => 'exists:categories,id', // 各カテゴリが存在することをチェック
            'revenue' => 'nullable|numeric|min:0',
            'profit' => 'nullable|numeric|min:0',
        ]);

        // プロジェクト作成
        $project = Project::create([
            'name' => $validated['name'],
            'phase_id' => $validated['phase_id'],
            'client_id' => $validated['client_id'],
            'revenue' => $validated['revenue'] ?? 0,
            'profit' => $validated['profit'] ?? 0,
        ]);

        // ✅ カテゴリを紐付け
        if (!empty($validated['category_id'])) {
            $project->categories()->sync($validated['category_id']);
        }

        return redirect()->route('projects.index')->with('success', '案件が作成されました。');
    }

    /**
     * 案件編集ページ
     */
    public function edit(Project $project)
    {
        $phases = Phase::orderBy('order')->get();
        return view('projects.edit', compact('project', 'phases'));
    }

    /**
     * 案件更新処理
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'phase_id' => 'required|exists:phases,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'revenue' => 'nullable|numeric',
            'profit' => 'nullable|numeric',
            'category_id' => 'array',
            'category_id.*' => 'exists:categories,id',
        ]);

        $project->update($validated);

        // ✅ カテゴリを更新
        if (!empty($validated['category_id'])) {
            $project->categories()->sync($validated['category_id']);
        }

        return redirect()->route('projects.index')->with('success', '案件が更新されました！');
    }
}
