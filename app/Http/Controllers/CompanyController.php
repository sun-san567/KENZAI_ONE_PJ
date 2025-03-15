<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    /**
     * 会社情報と部門情報を取得
     */
    public function index()
    {
        // ログインユーザーの所属会社を取得
        $user = Auth::user();
        $company = Company::where('id', $user->company_id)->first();

        // 会社が存在しない場合の処理
        if (!$company) {
            return redirect()->route('company.create')->with('error', '会社情報が登録されていません。');
        }

        // 自社に紐づく部門のみを取得
        $departments = Department::where('company_id', $company->id)->orderBy('name')->get();

        return view('company.index', compact('company', 'departments'));
    }

    /**
     * 会社情報作成画面
     */
    public function create()
    {
        return view('company.create');
    }

    /**
     * 会社情報を保存
     */
    public function store(Request $request)
    {
        if (Company::count() > 0) {
            return redirect()->route('company.index')->with('error', '会社情報は1つのみ作成可能です。');
        }

        $request->validate([
            'name' => 'required|unique:companies,name|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $company = Company::create($request->only(['name', 'address', 'phone', 'email']));

        // 作成したユーザーを会社に紐づける
        $user = Auth::user();
        $user->company_id = $company->id;
        $user->save();

        return redirect()->route('company.index')->with('success', '会社情報が登録されました。');
    }

    /**
     * 会社情報編集画面
     */
    public function edit(Company $company)
    {
        // 自社に紐づく部門も取得して編集画面に渡す
        $departments = Department::where('company_id', $company->id)->orderBy('name')->get();

        return view('company.edit', compact('company', 'departments'));
    }

    /**
     * 会社情報を更新
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|max:255|unique:companies,name,' . $company->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $company->update($request->only(['name', 'address', 'phone', 'email']));

        return redirect()->route('company.index')->with('success', '会社情報が更新されました。');
    }

    /**
     * 会社情報は削除不可
     */
    public function destroy(Company $company)
    {
        return redirect()->route('company.index')->with('error', '会社情報は削除できません。');
    }
}
