@extends('layouts.app')

@section('content')
<div class="ml-64 w-[calc(50%-64px)] mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- ヘッダー -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-gray-800">担当者登録</h1>
    </div>

    <!-- エラーメッセージ -->
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-5 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
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

    <!-- 担当者登録フォーム -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200 p-6">
        <form action="{{ route('clients.contacts.store', $client->id) }}" method="POST">
            @csrf

            <!-- 顧客名（確認用） -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-500">顧客</label>
                <p class="text-gray-700">{{ $client->name }}</p>
            </div>

            <!-- 担当者名 -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">担当者名 <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-md p-2 shadow-sm" value="{{ old('name') }}" required>
            </div>

            <!-- 役職 -->
            <div class="mb-4">
                <label for="position" class="block text-sm font-medium text-gray-700">役職</label>
                <input type="text" name="position" id="position" class="w-full border-gray-300 rounded-md p-2 shadow-sm" value="{{ old('position') }}">
            </div>

            <!-- 電話番号 -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700">電話番号</label>
                <input type="tel" name="phone" id="phone" class="w-full border-gray-300 rounded-md p-2 shadow-sm" value="{{ old('phone') }}">
            </div>

            <!-- メールアドレス -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">メールアドレス</label>
                <input type="email" name="email" id="email" class="w-full border-gray-300 rounded-md p-2 shadow-sm" value="{{ old('email') }}">
            </div>

            <!-- 備考 -->
            <div class="mb-4">
                <label for="note" class="block text-sm font-medium text-gray-700">備考</label>
                <textarea name="note" id="note" rows="3" class="w-full border-gray-300 rounded-md p-2 shadow-sm">{{ old('note') }}</textarea>
            </div>

            <!-- ボタン -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('clients.contacts.index', $client->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-gray-600">
                    キャンセル
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700">
                    登録
                </button>
            </div>
        </form>
    </div>
</div>
@endsection