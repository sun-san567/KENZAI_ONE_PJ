@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">フェーズ追加</h1>

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
        <form action="{{ route('phases.store') }}" method="POST" class="p-6">
            @csrf

            <div class="mb-6">
                <label for="name" class="block text-gray-700 font-medium mb-2">フェーズ名</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-gray-700 font-medium mb-2">説明</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
            </div>

            <div class="mb-6">
                <label for="order" class="block text-gray-700 font-medium mb-2">順番</label>
                <input type="number" name="order" id="order" value="{{ old('order', 0) }}"
                    class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- 管理者のみ部門を選択可能 -->
            @if(Auth::user()->role === 'admin' && isset($departments))
            <div class="mb-6">
                <label for="department_id" class="block text-gray-700 font-medium mb-2">部門</label>
                <select name="department_id" id="department_id"
                    class="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="flex justify-end space-x-4">
                <a href="{{ route('phases.index') }}" class="text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 font-medium py-2 px-4 rounded">
                    キャンセル
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                    登録する
                </button>
            </div>
        </form>
    </div>
</div>
@endsection