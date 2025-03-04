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
        $phases = Phase::all();
        $clients = Client::all();
        $categories = Category::all();

        // ✅ `$projects` を `phase_id` をキーにした配列に変換
        $projects = Project::with(['phase', 'categories', 'client'])->get();
        $projectsByPhase = [];
        foreach ($projects as $project) {
            $projectsByPhase[$project->phase_id][] = $project;
        }

        return view('projects.index', compact('phases', 'clients', 'categories', 'projectsByPhase'));
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
