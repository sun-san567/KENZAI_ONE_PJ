@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md mt-10">
    <h2 class="text-xl font-bold mb-4">フェーズ作成</h2>

    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
            <li class="text-sm">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('phases.store') }}" method="POST">
        @csrf

        <!-- フェーズ名 -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">フェーズ名</label>
            <input type="text" name="name" id="name"
                class="mt-1 block w-full p-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                value="{{ old('name') }}" required>
        </div>

        <!-- 説明 -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">説明</label>
            <textarea name="description" id="description" rows="3"
                class="mt-1 block w-full p-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('description') }}</textarea>
        </div>

        <!-- 並び順 -->
        <div class="mb-4">
            <label for="order" class="block text-sm font-medium text-gray-700">順番</label>
            <input type="number" name="order" id="order"
                class="mt-1 block w-full p-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                value="{{ old('order', 0) }}">
        </div>

        <!-- ボタン (右寄せ) -->
        <div class="flex justify-end space-x-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                作成
            </button>
            <a href="{{ route('phases.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
                戻る
            </a>
        </div>
    </form>
</div>
@endsection