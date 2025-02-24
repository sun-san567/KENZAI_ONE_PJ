<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Company;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('company')->get();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('departments.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:255|unique:departments,name',
        ]);

        Department::create($request->all());

        return redirect()->route('departments.index')->with('success', '部門が作成されました。');
    }

    // 編集画面
    public function edit(Department $department)
    {
        $companies = Company::all(); // 会社情報を取得
        return view('departments.edit', compact('department', 'companies'));
    }

    // 更新処理
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
        ]);

        $department->update([
            'name' => $request->name,
            'company_id' => $request->company_id,
        ]);

        return redirect()->route('departments.index')->with('success', '部門情報を更新しました！');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success', '部門が削除されました。');
    }
}
