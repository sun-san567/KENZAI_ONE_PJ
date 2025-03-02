@extends('layouts.app')

@section('title', 'フェーズ管理')

@section('content')
<div class="flex flex-col min-h-screen">
    <div class="container mx-auto p-6 flex-grow">
        <h1 class="text-xl font-bold mb-4">フェーズ管理</h1>

        <!-- 成功メッセージ -->
        @if (session('success'))
        <div class="bg-green-200 text-green-700 p-3 rounded-lg shadow mb-4">
            {{ session('success') }}
        </div>
        @endif

        <!-- エラーメッセージ -->
        @if (session('error'))
        <div class="bg-red-200 text-red-700 p-3 rounded-lg shadow mb-4">
            {{ session('error') }}
        </div>
        @endif

        <!-- 警告メッセージ -->
        @if (session('warning'))
        <div class="bg-yellow-200 text-yellow-700 p-3 rounded-lg shadow mb-4">
            {{ session('warning') }}
        </div>
        @endif

        <!-- ボタンエリア -->
        <div class="flex justify-end mb-4">
            <a href="{{ route('phases.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow">
                + フェーズ追加
            </a>
        </div>

        <!-- フェーズ一覧テーブル -->
        <div class="overflow-auto bg-white rounded-lg shadow p-4">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-300 text-gray-700">
                        <th class="p-3 text-left">フェーズ名</th>
                        <th class="p-3 text-center w-40">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($phases as $phase)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-3">{{ $phase->name }}</td>
                        <td class="p-3 flex justify-center space-x-2">
                            <!-- 編集ボタン -->
                            <a href="{{ route('phases.edit', $phase->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md shadow-md text-sm">
                                編集
                            </a>

                            <!-- 削除ボタン（確認ダイアログ付き） -->
                            <form action="{{ route('phases.destroy', $phase->id) }}" method="POST" onsubmit="return confirm('このフェーズを削除しますか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md shadow-md text-sm">
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
</div>
@endsection