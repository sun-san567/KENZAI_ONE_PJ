<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Phase;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * 案件一覧を表示
     */
    public function index()
    {
        $phases = Phase::orderBy('order')->get();
        $projects = Project::all()->groupBy('phase_id');

        return view('projects.index', compact('phases', 'projects'));
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
        $request->validate([
            'phase_id' => 'required|exists:phases,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'revenue' => 'nullable|numeric',
            'profit' => 'nullable|numeric',
        ]);

        Project::create($request->all());

        return redirect()->route('projects.create')->with('success', '案件が作成されました！');
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
        $request->validate([
            'phase_id' => 'required|exists:phases,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'revenue' => 'nullable|numeric',
            'profit' => 'nullable|numeric',
        ]);

        $project->update($request->all());

        return redirect()->route('projects.index')->with('success', '案件が更新されました！');
    }
}
