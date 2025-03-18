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
        // 現在のログインユーザーの情報を取得
        $user = auth()->user();
        $companyId = $user->company_id;
        $userDepartmentId = $user->department_id;

        // ログ出力 - デバッグ情報
        \Log::info('フェーズ一覧取得開始', [
            'user_id' => $user->id,
            'company_id' => $companyId,
            'department_id' => $userDepartmentId,
            'role' => $user->role
        ]);

        // 自社の部門IDを取得
        $departmentIds = Department::where('company_id', $companyId)
            ->pluck('id')
            ->toArray();

        // 部門IDログ
        \Log::info('自社部門ID一覧', ['department_ids' => $departmentIds]);

        // 管理者の場合は会社内の全フェーズ、それ以外は自部門のフェーズのみ
        if ($user->role === 'admin') {
            // 管理者: 自社の全部門のフェーズを表示
            $phases = Phase::whereIn('department_id', $departmentIds)
                ->orderBy('order', 'asc')
                ->get();
        } else {
            // 一般ユーザー: 自部門のフェーズのみ表示
            $phases = Phase::where('department_id', $userDepartmentId)
                ->orderBy('order', 'asc')
                ->get();
        }

        // 結果ログ
        \Log::info('取得フェーズ', [
            'count' => $phases->count(),
            'phase_ids' => $phases->pluck('id')->toArray(),
            'department_ids' => $phases->pluck('department_id')->unique()->toArray()
        ]);

        // 部門名も表示できるようにリレーションを読み込む
        $phases->load('department');

        // 部門と会社情報ログ
        \Log::info('フェーズに関連する部門と会社', [
            'relations' => $phases->map(function ($phase) {
                return [
                    'phase_id' => $phase->id,
                    'phase_name' => $phase->name,
                    'department_id' => $phase->department_id,
                    'department_name' => $phase->department->name ?? 'Unknown',
                    'company_id' => $phase->department->company_id ?? 'Unknown'
                ];
            })->toArray()
        ]);

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
