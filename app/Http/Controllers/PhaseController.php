<?php

namespace App\Http\Controllers;

use App\Models\Phase;

use Illuminate\Http\Request;

class PhaseController extends Controller
{

    public function index()
    {
        $phases = Phase::orderBy('order', 'asc')->get();
        return view('phases.index', compact('phases'));
    }

    // フェーズ作成フォームを表示
    public function create()
    {
        return view('phases.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:phases,name',
            'description' => 'nullable',
            'order' => 'nullable|integer',
        ]);

        Phase::create($request->all());

        return redirect()->route('phases.index')->with('success', 'フェーズを作成しました');
    }


    public function edit(Phase $phase)
    {
        return view('phases.edit', compact('phase'));
    }

    public function update(Request $request, Phase $phase)
    {
        $request->validate([
            'name' => 'required|unique:phases,name,' . $phase->id,
            'description' => 'nullable',
            'order' => 'nullable|integer',
        ]);

        $phase->update($request->all());

        return redirect()->route('phases.index')->with('success', 'フェーズを更新しました');
    }

    
    public function destroy(Phase $phase)
    {
        $phase->delete();
        return redirect()->route('phases.index')->with('success', 'フェーズを削除しました');
    }
}
