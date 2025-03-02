@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">カテゴリ編集</h1>

    @if ($errors->any())
    <div class="bg-red-200 text-red-700 p-3 rounded-lg mb-4">
        <ul>
            @foreach ($errors->all() as $error)
            <li>⚠️ {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('categories.update', $category->id) }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-2">カテゴリ名</label>
            <input type="text" name="name" id="name" value="{{ $category->name }}" class="w-full p-2 border rounded-lg" required>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('categories.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow-md">
                🔙 キャンセル
            </a>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md">
                ✅ 更新
            </button>
        </div>
    </form>
</div>
@endsection