<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Phase;
use App\Models\Category;
use App\Models\Project;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
     * 案件保存処理 (デバッグ付き)
     */
    public function store(Request $request)
    {
        try {
            // 1️⃣ ユーザー情報を確認
            $user = auth()->user();
            \Log::info("ユーザー情報: ", [
                'user_id' => $user->id,
                'company_id' => $user->company_id,
                'department_id' => $user->department_id
            ]);

            // 2️⃣ リクエストデータを確認
            \Log::info("受信データ: ", $request->all());

            // 3️⃣ バリデーション（より厳密なルールに修正）
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phase_id' => 'required|exists:phases,id',
                'client_id' => 'required|exists:clients,id',
                'description' => 'nullable|string',
                'category_id' => 'nullable|array',
                'category_id.*' => 'exists:categories,id',
                'revenue' => 'nullable|numeric|min:0',
                'profit' => 'nullable|numeric|min:0',
                'estimate_deadline' => 'nullable|date',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date',
            ]);

            // バリデーション後のデータを確認（デバッグ用）
            \Log::info("バリデーション後: ", $validated);
            
            // 日付データの確認
            \Log::info("日付データ: ", [
                'estimate_deadline' => $validated['estimate_deadline'] ?? 'なし',
                'start_date' => $validated['start_date'] ?? 'なし',
                'end_date' => $validated['end_date'] ?? 'なし',
            ]);

            // 4️⃣ プロジェクト作成（Carbon変換追加）
            $project = Project::create([
                'name' => $validated['name'],
                'phase_id' => $validated['phase_id'],
                'client_id' => $validated['client_id'],
                'description' => $validated['description'] ?? null,
                'revenue' => $validated['revenue'] ?? 0,
                'profit' => $validated['profit'] ?? 0,
                'company_id' => $user->company_id,
                'department_id' => $user->department_id,
                'user_id' => $user->id,
                'estimate_deadline' => isset($validated['estimate_deadline']) ? Carbon::parse($validated['estimate_deadline'])->format('Y-m-d') : null,
                'start_date' => isset($validated['start_date']) ? Carbon::parse($validated['start_date'])->format('Y-m-d') : null,
                'end_date' => isset($validated['end_date']) ? Carbon::parse($validated['end_date'])->format('Y-m-d') : null,
            ]);

            // 作成後のプロジェクトデータを確認
            \Log::info("作成後のプロジェクト: ", $project->toArray());

            // 5️⃣ カテゴリの関連付け
            if (!empty($validated['category_id'])) {
                $project->categories()->sync($validated['category_id']);
            }

            // 6️⃣ リダイレクト処理
            return redirect()->route('projects.index')->with('success', '案件が作成されました。');
        } catch (\Exception $e) {
            // エラーログ記録
            \Log::error("プロジェクト作成エラー: " . $e->getMessage());
            \Log::error("エラートレース: " . $e->getTraceAsString());

            return back()->withInput()->with('error', '案件の作成に失敗しました。');
        }
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
