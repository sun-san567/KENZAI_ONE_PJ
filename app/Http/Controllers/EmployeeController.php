<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;


class EmployeeController extends Controller
{
    public function index()
    {
        // 20件ずつ表示する
        $employees = Employee::with('department')->paginate(20);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        return view('employees.create', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')->with('success', '担当者を追加しました！');
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'departments'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', '担当者情報を更新しました！');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', '担当者を削除しました！');
    }
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file, "r");
        $header = fgetcsv($handle); // ヘッダー行をスキップ

        $errors = [];
        $successCount = 0;

        while ($row = fgetcsv($handle)) {
            // 部門が見つからなければスキップ（エラーメッセージに追加）
            $department = Department::where('name', $row[0])->first();
            if (!$department) {
                $errors[] = "部門 '{$row[0]}' が見つかりませんでした。";
                continue;
            }

            // 担当者を作成
            Employee::create([
                'department_id' => $department->id,
                'name' => $row[1],
                'email' => $row[2],
                'phone' => $row[3] ?? null,
            ]);

            $successCount++;
        }

        fclose($handle);

        // インポート成功・エラーメッセージを表示
        if (!empty($errors)) {
            return redirect()->route('employees.index')->with([
                'success' => "$successCount 件の担当者をインポートしました。",
                'errors' => $errors
            ]);
        }

        return redirect()->route('employees.index')->with('success', "$successCount 件の担当者をインポートしました！");
    }
}
