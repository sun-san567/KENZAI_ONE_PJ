@extends('layouts.app')

@section('title', '部門の追加')

@section('content')
<div class="ml-64 w-[calc(50%-64px)] mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-xl font-semibold text-gray-800 border-b border-gray-300 pb-4">部門の追加</h1>

    <div class="bg-white shadow rounded-lg border border-gray-200 p-6 mt-6">
        <form action="{{ route('departments.store') }}" method="POST">
            @csrf

            <!-- 会社を選択 -->
            <div class="mb-4">
                <label for="company_id" class="block text-sm font-medium text-gray-700">会社</label>
                <input type="hidden" name="company_id" value="{{ $company->id }}">
                <div class="mt-1 p-3 bg-gray-100 rounded-md">
                    {{ $company->name }} <!-- 会社名を表示するだけ -->
                </div>
                <p class="text-xs text-gray-500 mt-1">※ 所属会社は変更できません</p>
            </div>

            <!-- 部門名 -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-1">部門名 <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- ボタン -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('company.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow-md transition">
                    キャンセル
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition">
                    追加
                </button>
            </div>
        </form>
    </div>
</div>
@endsection