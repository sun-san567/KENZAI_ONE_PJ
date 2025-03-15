@extends('layouts.app')

@section('title', '会社・部門管理')

@section('content')
<div class="ml-64 w-[calc(50%-64px)] mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- 会社情報セクション -->
    <h1 class="text-xl font-medium text-gray-800 border-b border-gray-200 pb-4">会社情報管理</h1>

    <!-- メッセージ表示 -->
    @foreach (['success' => 'green', 'error' => 'red', 'warning' => 'yellow'] as $msg => $color)
    @if (session($msg))
    <div class="bg-{{ $color }}-200 text-{{ $color }}-700 p-3 rounded-lg shadow mb-4">
        {{ session($msg) }}
    </div>
    @endif
    @endforeach

    <!-- 会社登録ボタン (会社情報がない場合のみ表示) -->
    @if (!$company)
    <div class="flex justify-end my-4">
        <a href="{{ route('company.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-300 rounded-md text-sm font-medium text-blue-700 hover:bg-blue-100">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            会社情報を登録
        </a>
    </div>
    @endif

    <!-- 会社情報テーブル -->
    @if ($company)
    <div class="mb-8">
        <table class="w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">会社名</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">住所</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">電話番号</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">メール</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50/30 transition-colors">
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $company->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">
                        <div class="truncate max-w-xs" title="{{ $company->address }}">
                            {{ $company->address }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $company->phone }}</td>
                    <td class="px-6 py-4 text-sm text-gray-800">{{ $company->email }}</td>
                    <td class="px-6 py-4 text-right text-sm">
                        <div class="flex justify-end space-x-2">
                            <a href="{{ route('company.edit', $company->id) }}"
                                class="inline-flex items-center p-1.5 border border-gray-300 rounded-md text-gray-600 bg-white hover:bg-gray-50">
                                ✏
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 部門管理セクション -->
    <h2 class="text-xl font-medium text-gray-800 border-b border-gray-200 pb-4 mt-8">部門管理</h2>

    <!-- 部門追加ボタン -->
    <div class="flex justify-end my-4">
        <a href="{{ route('departments.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-300 rounded-md text-sm font-medium text-blue-700 hover:bg-blue-100">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            部門追加
        </a>
    </div>

    <!-- 部門一覧テーブル -->
    <table class="w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">部門名</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">操作</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($departments as $department)
            <tr class="odd:bg-white even:bg-gray-50 hover:bg-blue-50/30 transition-colors">
                <td class="px-6 py-4 text-sm text-gray-800">{{ $department->name }}</td>
                <td class="px-6 py-4 text-right text-sm">
                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('departments.edit', $department->id) }}"
                            class="inline-flex items-center p-1.5 border border-gray-300 rounded-md text-gray-600 bg-white hover:bg-gray-50">
                            ✏
                        </a>
                        <form action="{{ route('departments.destroy', $department->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('この部門を削除しますか？関連する担当者データもすべて削除されます。');"
                                class="inline-flex items-center p-1.5 border border-gray-300 rounded-md text-gray-500 bg-white hover:bg-red-50 hover:text-red-500 hover:border-red-300">
                                🗑
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="px-6 py-4 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center py-6">
                        <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-600 mb-1">部門が登録されていません</p>
                        <p class="text-gray-500 text-sm">「+ 部門追加」ボタンから新しい部門を登録できます</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @else
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded my-6">
        <p>会社情報が登録されていません。「会社情報を登録」ボタンから登録してください。</p>
    </div>
    @endif
</div>
@endsection