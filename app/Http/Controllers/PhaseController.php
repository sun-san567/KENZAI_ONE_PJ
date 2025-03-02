<?php

namespace App\Http\Controllers;

use App\Models\Phase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;

class PhaseController extends Controller
{
    /**
     * フェーズ一覧
     */
    public function index()
    {
        $phases = Phase::orderBy('order', 'asc')->get();
        return view('phases.index', compact('phases'));
    }

    /**
     * フェーズ作成フォームを表示 (管理者・部門管理者のみ)
     */
    public function create()
    {
        if (!in_array(Auth::user()->role, ['manager', 'admin'])) {
            return redirect()->route('phases.index')->with('error', '権限がありません');
        }

        // 🔹 管理者が選択できるよう、部門一覧を取得
        $departments = Department::all();

        return view('phases.create', compact('departments'));
    }
    /**
     * フェーズを作成
     */
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['manager', 'admin'])) {
            return redirect()->route('phases.index')->with('error', '権限がありません');
        }

        $request->validate([
            'name' => 'required|unique:phases,name',
            'description' => 'nullable',
            'order' => 'nullable|integer',
            'department_id' => Auth::user()->role === 'admin' ? 'required|exists:departments,id' : '',
        ]);

        $department_id = Auth::user()->department_id;

        // 🔹 管理者は部門を選択できる
        if (is_null($department_id) && Auth::user()->role === 'admin') {
            $department_id = $request->input('department_id');
        }

        // 🔴 `department_id` が NULL のままならエラー
        if (is_null($department_id)) {
            return redirect()->route('phases.index')->with('error', '部門情報が取得できませんでした。管理者に問い合わせてください。');
        }

        Phase::create([
            'name' => $request->name,
            'description' => $request->description,
            'order' => $request->order ?? 0,
            'department_id' => $department_id,
        ]);

        return redirect()->route('phases.index')->with('success', 'フェーズを作成しました');
    }





    /**
     * フェーズ編集フォームを表示 (管理者・部門管理者のみ)
     */
    public function edit(Phase $phase)
    {
        if (!in_array(Auth::user()->role, ['manager', 'admin'])) {
            return redirect()->route('phases.index')->with('error', '権限がありません');
        }

        return view('phases.edit', compact('phase'));
    }

    /**
     * フェーズ更新
     */
    public function update(Request $request, Phase $phase)
    {
        if (!in_array(Auth::user()->role, ['manager', 'admin'])) {
            return redirect()->route('phases.index')->with('error', '権限がありません');
        }

        $request->validate([
            'name' => 'required|unique:phases,name,' . $phase->id,
            'description' => 'nullable',
            'order' => 'nullable|integer',
        ]);

        $phase->update([
            'name' => $request->name,
            'description' => $request->description,
            'order' => $request->order ?? 0,
        ]);

        return redirect()->route('phases.index')->with('success', 'フェーズを更新しました');
    }

    /**
     * フェーズ削除 (管理者・部門管理者のみ)
     */
    public function destroy(Phase $phase)
    {
        if (!in_array(Auth::user()->role, ['manager', 'admin'])) {
            return redirect()->route('phases.index')->with('error', '権限がありません');
        }

        $phase->delete();
        return redirect()->route('phases.index')->with('success', 'フェーズを削除しました');
    }
}
