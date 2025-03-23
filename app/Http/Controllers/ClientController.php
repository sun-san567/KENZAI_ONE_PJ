<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Company;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientsImport;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::where('company_id', auth()->user()->company_id)->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $departments = Department::where('company_id', auth()->user()->company_id)->get();
        $users = User::where('company_id', auth()->user()->company_id)->get();

        return view('clients.create', compact('departments', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:clients,name',
            'department_id' => 'nullable|exists:departments,id',
            'user_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->department_id) {
                        $user = User::find($value);
                        if (!$user || $user->department_id != $request->department_id) {
                            $fail('選択された担当者は指定した部門に属していません。');
                        }
                    }
                }
            ],
        ]);

        Client::create([
            'company_id' => auth()->user()->company_id,
            'department_id' => $request->department_id,
            'user_id' => $request->user_id,
            'name' => $request->name,
        ]);

        return redirect()->route('clients.index')->with('success', 'クライアントを登録しました');
    }

    public function edit(Client $client)
    {
        $departments = Department::where('company_id', auth()->user()->company_id)->get();
        $users = User::where('company_id', auth()->user()->company_id)->get();

        return view('clients.edit', compact('client', 'departments', 'users'));
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('success', 'クライアントを削除しました');
    }
    public function update(Request $request, Client $client)
    {
        // バリデーション
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        // クライアント情報の更新
        $client->update([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'user_id' => $request->user_id,
        ]);

        // 成功メッセージを設定し、クライアント一覧ページへリダイレクト
        return redirect()->route('clients.index')->with('success', 'クライアント情報を更新しました。');
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }
}
