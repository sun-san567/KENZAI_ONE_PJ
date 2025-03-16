@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">フェーズ編集</h1>

        <a href="{{ route('phases.index') }}" class="text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 font-medium py-2 px-4 rounded">
            ← 一覧に戻る
        </a>
    </div>

    @if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg mb-6">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <form action="{{ route('phases.update', $phase->id) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-medium mb-2">フェーズ名</label>
                <input type="text" name="name" id="name" value="{{ old('name', $phase->name) }}"
                    class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-gray-700 font-medium mb-2">説明</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $phase->description) }}</textarea>
            </div>

            <div class="mb-6">
                <label for="order" class="block text-gray-700 font-medium mb-2">順番</label>
                <input type="number" name="order" id="order" value="{{ old('order', $phase->order) }}"
                    class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('phases.index') }}" class="text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 font-medium py-2 px-4 rounded">
                    キャンセル
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                    更新する
                </button>
            </div>
        </form>
    </div>
</div>
@endsection