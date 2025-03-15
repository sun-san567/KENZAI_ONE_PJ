@extends('layouts.app')

@section('content')
<div class="ml-64 w-[calc(50%-64px)] mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- ヘッダー -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-gray-800">ユーザー編集</h1>
    </div>

    <!-- エラーメッセージ -->
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-5 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <ul class="text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- ユーザー編集フォーム -->
    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6">
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- 部門選択 -->
            <div class="mb-4">
                <label for="department_id" class="block text-sm font-medium text-gray-700">部門 <span class="text-red-500">*</span></label>
                <select name="department_id" id="department_id" class="w-full border-gray-300 rounded-md p-2 shadow-sm">
                    @foreach ($departments as $department)
                    <option value="{{ $department->id }}" {{ $user->department_id == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- ユーザー名 -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">ユーザー名 <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-md p-2 shadow-sm" value="{{ old('name', $user->name) }}" required>
            </div>

            <!-- メールアドレス -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" class="w-full border-gray-300 rounded-md p-2 shadow-sm" value="{{ old('email', $user->email) }}" required>
            </div>

            <!-- 電話番号 -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">電話番号</label>
                <input type="text" name="phone" id="phone" class="w-full border-gray-300 rounded-md p-2 shadow-sm" value="{{ old('phone', $user->phone) }}">
            </div>

            <!-- ボタン -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow-md">
                    戻る
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md">
                    更新
                </button>
            </div>
        </form>
    </div>
</div>
@endsection