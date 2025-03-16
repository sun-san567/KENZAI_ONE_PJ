@extends('layouts.app')

@section('title', '新規会社登録')

@section('content')
<div class="ml-64 w-[calc(50%-64px)] mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-xl font-semibold text-gray-800 border-b border-gray-300 pb-4">新規会社登録</h1>

    <div class="bg-white shadow rounded-lg border border-gray-200 p-6 mt-6">
        <form action="{{ route('companies.store') }}" method="POST">
            @csrf

            <!-- 会社名 -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">会社名 <span class="text-red-500">*</span></label>
                <input type="text" name="name" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- 住所 -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">住所</label>
                <input type="text" name="address" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- 電話番号 -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">電話番号</label>
                <input type="text" name="phone" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- メールアドレス -->
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-1">メールアドレス</label>
                <input type="email" name="email" class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- ボタン -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('companies.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow-md transition">
                    戻る
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition">
                    登録
                </button>
            </div>
        </form>
    </div>
</div>
@endsection