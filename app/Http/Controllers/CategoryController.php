<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        // ログインユーザーの所属会社のカテゴリのみ取得
        $categories = Category::where('company_id', Auth::user()->company_id)->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            'company_id' => Auth::user()->company_id,
        ]);

        return redirect()->route('categories.index')->with('success', 'カテゴリが作成されました');
    }

    public function edit(Category $category)
    {
        // 他社のカテゴリは編集できないように制限
        if ($category->company_id !== Auth::user()->company_id) {
            return redirect()->route('categories.index')->with('error', '他社のカテゴリは編集できません');
        }

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        if ($category->company_id !== Auth::user()->company_id) {
            return redirect()->route('categories.index')->with('error', '他社のカテゴリは更新できません');
        }

        $request->validate([
            'name' => 'required|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update(['name' => $request->name]);

        return redirect()->route('categories.index')->with('success', 'カテゴリが更新されました');
    }

    public function destroy(Category $category)
    {
        if ($category->company_id !== Auth::user()->company_id) {
            return redirect()->route('categories.index')->with('error', '他社のカテゴリは削除できません');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'カテゴリが削除されました');
    }
}
