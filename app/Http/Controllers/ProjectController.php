<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Phase;
use App\Models\Category;
use App\Models\Project;
use App\Models\Department;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * プロジェクト一覧を表示
     */
    public function index(Request $request)
    {
        // 現在のログインユーザーの情報
        $user = auth()->user();
        $companyId = $user->company_id;
        $userDepartmentId = $user->department_id;
        $isAdmin = $user->role === 'admin';

        // 基本クエリ - 自社のクライアントに紐づくプロジェクトのみ
        $query = Project::with(['client', 'phase'])
            ->whereHas('client', function ($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });

        // 検索条件適用
        if ($request->filled('phase_id')) {
            $query->where('phase_id', $request->phase_id);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $projects = $query->orderBy('updated_at', 'desc')->paginate(10);

        // カテゴリを自社のものだけに制限
        $categories = Category::where('company_id', $companyId)->get();

        // フェーズの制限：管理者なら全社、一般ユーザーなら自部門のみ
        if ($isAdmin) {
            // 管理者: 会社の全フェーズを表示
            $departmentIds = Department::where('company_id', $companyId)->pluck('id');
            $phases = Phase::whereIn('department_id', $departmentIds)
                ->with(['projects' => function ($query) use ($companyId) {
                    $query->whereHas('client', function ($q) use ($companyId) {
                        $q->where('company_id', $companyId);
                    });
                }])
                ->orderBy('order')
                ->get();
        } else {
            // 一般ユーザー: 自部門のフェーズのみ表示
            $phases = Phase::where('department_id', $userDepartmentId)
                ->with(['projects' => function ($query) use ($companyId) {
                    $query->whereHas('client', function ($q) use ($companyId) {
                        $q->where('company_id', $companyId);
                    });
                }])
                ->orderBy('order')
                ->get();
        }

        // クライアントも自社のみ
        $clients = Client::where('company_id', $companyId)->get();

        // プロジェクトごとのカテゴリ情報をロード
        foreach ($projects as $project) {
            $project->load(['categories' => function ($query) use ($companyId) {
                $query->where('company_id', $companyId);
            }]);
        }

        return view('projects.index', compact('projects', 'categories', 'phases', 'clients', 'isAdmin'));
    }

    /**
     * 案件作成ページ
     */
    public function create()
    {
        $phases = Phase::orderBy('order')->get();
        $clients = Client::all();
        $categories = Category::all();

        return view('projects.create', compact('phases', 'clients', 'categories'));
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
            'category_id' => 'nullable|array',
            'category_id.*' => 'exists:categories,id',
            'revenue' => 'nullable|numeric|min:0',
            'profit' => 'nullable|numeric|min:0',
        ]);

        $project = Project::create([
            'name' => $validated['name'],
            'phase_id' => $validated['phase_id'],
            'client_id' => $validated['client_id'],
            'revenue' => $validated['revenue'] ?? 0,
            'profit' => $validated['profit'] ?? 0,
        ]);

        if (!empty($validated['category_id'])) {
            $project->categories()->sync($validated['category_id']);
        } else {
            $project->categories()->detach();
        }

        return redirect()->route('projects.index')->with('success', '案件が作成されました。');
    }

    /**
     * 案件編集ページ
     */
    public function edit(Project $project)
    {
        $phases = Phase::orderBy('order')->get();
        $clients = Client::all();
        $categories = Category::all();

        return view('projects.edit', compact('project', 'phases', 'clients', 'categories'));
    }

    /**
     * 案件更新処理
     */
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'phase_id' => 'required|exists:phases,id',
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'revenue' => 'nullable|numeric|min:0',
            'profit' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|array',
            'category_id.*' => 'exists:categories,id',
        ]);

        $project->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'phase_id' => $validated['phase_id'],
            'client_id' => $validated['client_id'],
            'revenue' => $validated['revenue'] ?? 0,
            'profit' => $validated['profit'] ?? 0,
        ]);

        if (!empty($validated['category_id'])) {
            $project->categories()->sync($validated['category_id']);
        } else {
            $project->categories()->detach();
        }

        return redirect()->route('projects.index')->with('success', '案件が更新されました！');
    }
}
