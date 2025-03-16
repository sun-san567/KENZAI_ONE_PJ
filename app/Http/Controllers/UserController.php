<?php

namespace App\Http\Controllers;

use App\Models\User; // Employee を User に変更
use App\Models\Department;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function create()
    {
        $departments = Department::all();
        return view('users.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
        ]);

        $authUser = auth()->user(); // ログインユーザーを取得

        // 確認用ログ
        \Log::info('ログインユーザー:', ['auth_user' => $authUser]);

        $user = User::create([
            'company_id' => $authUser->company_id, // 会社に紐づける
            'department_id' => $request->department_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'position' => $request->position ?? '', // NULL回避
            'password' => bcrypt('defaultpassword'), // 仮パスワード
        ]);

        // 追加されたユーザー情報をログに記録
        \Log::info('追加されたユーザー:', ['user' => $user]);

        return redirect()->route('users.index')->with('success', 'ユーザーを追加しました！');
    }



    public function edit(User $user)
    {
        $departments = Department::all();
        return view('users.edit', compact('user', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
        ]);

        $user->update($request->all());

        return redirect()->route('users.index')->with('success', '担当者情報を更新しました！');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', '担当者を削除しました！');
    }

    public function index(Request $request)
    {
        // 現在のログインユーザーの会社IDを取得
        $companyId = auth()->user()->company_id;

        // 自社の部門のみを取得
        $departments = Department::where('company_id', $companyId)->get();

        // 自社のユーザーのみに制限
        $query = User::with('department')
            ->where('company_id', $companyId);

        // 部門による絞り込み（自社の部門IDかチェック）
        if ($request->filled('department_id')) {
            // 部門IDが自社のものか確認
            $departmentBelongsToCompany = Department::where('id', $request->department_id)
                ->where('company_id', $companyId)
                ->exists();

            if ($departmentBelongsToCompany) {
                $query->where('department_id', $request->department_id);
            }
        }

        // 名前による検索
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $users = $query->paginate(20);

        return view('users.index', compact('users', 'departments'));
    }
}
