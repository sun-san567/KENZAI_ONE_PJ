<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;


class EmployeeController extends Controller
{


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
    // CSVフォーマットをダウンロード
    public function downloadFormat()
    {
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=employee_format.csv"
        ];

        $columns = ["部門名", "担当者名", "メール", "電話番号"];

        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show($id)
    {
        $employee = Employee::with('department')->find($id);

        if (!$employee) {
            return redirect()->route('employees.index')->with('error', '担当者が見つかりませんでした。');
        }

        return view('employees.show', compact('employee'));
    }

    public function index(Request $request)
    {
        // 部門一覧取得（検索用）
        $departments = Department::all();

        // 検索条件を適用
        $query = Employee::with('department');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // 担当者データ取得（20件ごとにページネーション）
        $employees = $query->paginate(20);

        return view('employees.index', compact('employees', 'departments'));
    }
}
