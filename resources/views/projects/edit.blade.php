@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">案件編集</h2>

    <form action="{{ route('projects.update', $project->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')

        <!-- 案件名 -->
        <div class="mb-4">
            <label for="name" class="block text-gray-700">案件名</label>
            <input type="text" id="name" name="name" class="w-full p-2 border rounded" value="{{ old('name', $project->name) }}" required>
        </div>

        <!-- 顧客選択 -->
        <div class="mb-4">
            <label for="client_id" class="block text-gray-700">顧客</label>
            <select name="client_id" id="client_id" class="w-full p-2 border rounded">
                @foreach ($clients as $client)
                <option value="{{ $client->id }}" {{ $project->client_id == $client->id ? 'selected' : '' }}>
                    {{ $client->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- フェーズ選択 -->
        <div class="mb-4">
            <label for="phase_id" class="block text-gray-700">フェーズ</label>
            <select id="phase_id" name="phase_id" class="w-full p-2 border rounded">
                @foreach ($phases as $phase)
                <option value="{{ $phase->id }}" {{ $project->phase_id == $phase->id ? 'selected' : '' }}>
                    {{ $phase->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- 説明 -->
        <div class="mb-4">
            <label for="description" class="block text-gray-700">説明</label>
            <textarea id="description" name="description" class="w-full p-2 border rounded">{{ old('description', $project->description) }}</textarea>
        </div>

        <!-- カテゴリ -->
        <div class="mb-4">
            <label for="category_id" class="block text-gray-700">カテゴリ</label>
            <select name="category_id[]" id="category_id" class="w-full p-2 border rounded" multiple>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}"
                    {{ $project->categories->contains($category->id) ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- 売上 -->
        <div class="mb-4">
            <label for="revenue" class="block text-gray-700">売上</label>
            <input type="number" id="revenue" name="revenue" class="w-full p-2 border rounded" value="{{ old('revenue', $project->revenue) }}">
        </div>

        <!-- 粗利 -->
        <div class="mb-4">
            <label for="profit" class="block text-gray-700">粗利</label>
            <input type="number" id="profit" name="profit" class="w-full p-2 border rounded" value="{{ old('profit', $project->profit) }}">
        </div>

        <!-- ボタン -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('projects.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">戻る</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">更新</button>
        </div>
    </form>
</div>
@endsection