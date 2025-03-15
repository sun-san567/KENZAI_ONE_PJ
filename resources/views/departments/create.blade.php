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
                <label for="company_id" class="block text-gray-700 font-medium mb-1">会社を選択 <span class="text-red-500">*</span></label>
                <select id="company_id" name="company_id"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                    @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
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