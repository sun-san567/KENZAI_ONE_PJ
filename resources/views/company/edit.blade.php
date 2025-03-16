@extends('layouts.app')

@section('title', '会社情報編集')

@section('content')
<div class="ml-64 w-[calc(50%-64px)] mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-xl font-semibold text-gray-800 border-b border-gray-300 pb-4">会社情報編集</h1>

    @if (session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 rounded-md mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white shadow rounded-lg border border-gray-200 p-6 mt-6">
        <form action="{{ route('company.update', $company->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- 会社名 -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-1">会社名 <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- 住所 -->
            <div class="mb-4">
                <label for="address" class="block text-gray-700 font-medium mb-1">住所</label>
                <input type="text" id="address" name="address" value="{{ old('address', $company->address) }}"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- 電話番号 -->
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 font-medium mb-1">電話番号</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone', $company->phone) }}"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- メールアドレス -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-1">メール</label>
                <input type="email" id="email" name="email" value="{{ old('email', $company->email) }}"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- ボタン -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('company.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow-md transition">
                    戻る
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition">
                    更新
                </button>
            </div>
        </form>
    </div>
</div>
@endsection