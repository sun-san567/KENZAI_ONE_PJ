@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">案件作成</h2>

    @if(session('success'))
    <div class="bg-green-200 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('projects.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf

        <!-- 案件名 -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700">案件名</label>
            <input type="text" id="name" name="name" class="w-full p-2 border rounded" required>
        </div>

        <!-- 顧客選択 -->
        <div class="mb-4">
            <label for="client_id" class="block text-gray-700">顧客</label>
            <select name="client_id" id="client_id" class="w-full p-2 border rounded">
                @foreach ($clients as $client)
                <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- フェーズ選択 -->
        <div class="mb-4">
            <label for="phase_id" class="block text-gray-700">フェーズ</label>
            <select id="phase_id" name="phase_id" class="w-full p-2 border rounded">
                @foreach ($phases as $phase)
                <option value="{{ $phase->id }}">{{ $phase->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- 説明 -->
        <div class="mb-4">
            <label for="description" class="block text-gray-700">説明</label>
            <textarea id="description" name="description" class="w-full p-2 border rounded"></textarea>
        </div>

        <!-- カテゴリ -->
        <div class="mb-4">
            <label for="categories" class="block text-gray-700">カテゴリ</label>
            <select name="category_id[]" id="categories" class="w-full p-2 border rounded" multiple>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- 売上 -->
        <div class="mb-4">
            <label for="revenue" class="block text-gray-700">売上</label>
            <input type="number" id="revenue" name="revenue" class="w-full p-2 border rounded">
        </div>

        <!-- 粗利 -->
        <div class="mb-4">
            <label for="profit" class="block text-gray-700">粗利</label>
            <input type="number" id="profit" name="profit" class="w-full p-2 border rounded">
        </div>

        <!-- ボタン -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('projects.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">戻る</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">作成</button>
        </div>
    </form>
</div>
@endsection