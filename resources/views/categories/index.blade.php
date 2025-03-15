@extends('layouts.app')

@section('content')
<div class="ml-64 w-[calc(50%-64px)] mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-gray-800">カテゴリ管理</h1>
        <a href="{{ route('categories.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-300 rounded-md text-sm font-medium text-blue-700 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            カテゴリ追加
        </a>
    </div>

    @if (session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-3 mb-5 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-sm font-medium text-gray-700">全カテゴリ一覧</h2>
                <span class="text-xs text-gray-500">{{ count($categories) }}件</span>
            </div>
        </div>

        <div class="overflow-hidden">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-3/5">
                            カテゴリ名
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-2/5">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($categories as $category)
                    <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $category->name }}</div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex justify-end space-x-2">
                                <a href="{{ route('categories.edit', $category->id) }}"
                                    class="inline-flex items-center p-1.5 border border-gray-300 rounded-md text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('このカテゴリを削除しますか？関連するデータにも影響します');"
                                        class="inline-flex items-center p-1.5 border border-gray-300 rounded-md text-gray-500 bg-white hover:bg-red-50 hover:text-red-500 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if(count($categories) == 0)
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="mt-2 text-sm text-gray-500">登録されているカテゴリがありません</p>
                <a href="{{ route('categories.create') }}" class="mt-3 inline-flex items-center px-3 py-1.5 text-sm text-blue-600 hover:text-blue-700">
                    + 新しいカテゴリを追加
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection