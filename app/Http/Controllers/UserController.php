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
        $departments = Department::all();
        $query = User::with('department');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $users = $query->paginate(20);

        return view('users.index', compact('users', 'departments'));
    }
}
