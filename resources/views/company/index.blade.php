@extends('layouts.app')

@section('title', '会社・部門管理')

@section('content')
<div class="ml-64 w-[calc(100%-64px)] mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- メッセージ表示 -->
    @foreach (['success' => 'green', 'error' => 'red', 'warning' => 'yellow'] as $msg => $color)
    @if (session($msg))
    <div class="bg-{{ $color }}-50 border-l-4 border-{{ $color }}-500 text-{{ $color }}-700 p-3 rounded-md mb-4">
        {{ session($msg) }}
    </div>
    @endif
    @endforeach

    @if (!$company)
    <!-- 会社情報未登録時 -->
    <div class="flex justify-end my-4">
        <a href="{{ route('company.create') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-300 rounded-md text-base font-medium text-blue-700 hover:bg-blue-100">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            会社情報を登録
        </a>
    </div>

    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded my-6">
        <p>会社情報が登録されていません。「会社情報を登録」ボタンから登録してください。</p>
    </div>
    @else
    <!-- 会社情報セクション -->
    <!-- 会社情報セクション -->
    <div class="bg-gray-50 shadow-md rounded-lg border border-gray-200 p-6">
        <h1 class="text-xl font-semibold text-gray-800 flex items-center justify-between border-b border-gray-300 pb-4">
            会社情報管理
        </h1>

        <!-- テーブル -->
        <div class="w-full mt-4">
            <table class="w-full table-fixed divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3 text-left whitespace-nowrap w-[25%]">会社名</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap w-[40%]">住所</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap w-[15%]">電話番号</th>
                        <th class="px-4 py-3 text-left whitespace-nowrap w-[20%]">メール</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase whitespace-nowrap">操作</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 text-sm text-gray-800">
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $company->name }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $company->address }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $company->phone }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $company->email }}
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('company.edit', $company->id) }}"
                                class="inline-flex items-center p-2 border border-gray-300 rounded-md text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <!-- 部門管理セクション -->
    <div class="mt-8 bg-gray-50 shadow-md rounded-lg border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-800 border-b border-gray-300 pb-4">部門管理</h2>

        <!-- 部門追加ボタン -->
        <div class="flex justify-end my-4">
            <a href="{{ route('departments.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-300 rounded-md text-base font-medium text-blue-700 hover:bg-blue-100">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                部門追加
            </a>
        </div>

        <!-- 部門一覧テーブル（スクロール対応） -->
        <div class="bg-white shadow-md rounded-lg border border-gray-200 max-h-96 overflow-y-auto">
            <table class="w-full divide-y divide-gray-200">
                <thead class="bg-gray-100 sticky top-0 shadow">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">部門名</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 uppercase">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @if(isset($departments) && count($departments) > 0)
                    @foreach($departments as $department)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-800">{{ $department->name }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2">

                                <!-- 部門に所属するユーザー一覧リンク -->
                                <a href="{{ route('users.index') }}?department={{ $department->id }}"
                                    class="inline-flex items-center space-x-1 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold px-3 py-1.5 rounded shadow-sm transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>所属ユーザー</span>
                                </a>
                                <!-- 編集ボタン -->
                                <a href="{{ route('departments.edit', $department->id) }}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>

                                <!-- 削除ボタン -->
                                <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('この部門を削除しますか？関連するデータも削除されます。');"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-500 bg-white hover:bg-red-50 hover:text-red-500 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 shadow-md">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>


                    </tr>
                    @endforeach
                    @else
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
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection