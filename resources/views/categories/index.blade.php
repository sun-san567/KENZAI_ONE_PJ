@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">カテゴリ管理</h1>

    @if (session('success'))
    <div class="bg-green-200 p-2 text-green-700 mb-4 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="flex justify-end mb-6">
        <a href="{{ route('categories.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md">
            ➕ カテゴリ追加
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-300 text-gray-700">
                    <th class="p-4 text-left">カテゴリ名</th>
                    <th class="p-4 text-center">操作</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach ($categories as $category)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-4">{{ $category->name }}</td>
                    <td class="p-4 text-center">
                        <a href="{{ route('categories.edit', $category->id) }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md">
                            編集
                        </a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                            class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md"
                                onclick="return confirm('削除しますか？');">
                                削除
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection