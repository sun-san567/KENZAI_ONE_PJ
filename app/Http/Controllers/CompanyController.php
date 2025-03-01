<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    public function index()
    {
        // ログインユーザーの所属会社のみ取得
        $user = Auth::user();
        $companies = Company::where('id', $user->company_id)->get();

        // 会社が存在しない場合の処理
        if ($companies->isEmpty()) {
            return redirect()->route('companies.create')->with('error', '会社情報が登録されていません。');
        }

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        if (Company::exists()) {
            return redirect()->route('companies.index')->with('error', '会社情報は1つのみ作成可能です。');
        }

        $request->validate([
            'name' => 'required|unique:companies,name|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        Company::create($request->all());

        return redirect()->route('companies.index')->with('success', '会社情報が登録されました。');
    }


    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|max:255|unique:companies,name,' . $company->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $company->update($request->all());

        return redirect()->route('companies.index')->with('success', '会社情報が更新されました。');
    }


    public function destroy(Company $company)
    {
        return redirect()->route('companies.index')->with('error', '会社情報は削除できません。');
    }
}
