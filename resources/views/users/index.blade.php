@extends('layouts.app')

@section('content')
<div class="flex flex-col min-h-screen">
    <div class="container mx-auto p-6 flex-grow">
        <h1 class="text-xl font-bold mb-4">ユーザー管理</h1>

        <!-- 成功メッセージ -->
        @if (session('success'))
        <div id="success-message" class="bg-green-200 p-2 text-green-700 mb-4 rounded shadow">
            {{ session('success') }}
        </div>
        @endif

        <!-- CSVアップロード通知 -->
        <div id="upload-message" class="hidden bg-yellow-200 text-yellow-800 p-3 rounded-lg mb-4 shadow">
            CSVのアップロードが完了しました！データをインポート中です...
        </div>

        <!-- インポート完了メッセージ -->
        <div id="import-complete-message" class="hidden bg-green-200 text-green-800 p-3 rounded-lg mb-4 border border-green-400 shadow-md">
            ✅ CSVのインポートが完了しました！
        </div>

        <!-- ボタンエリア -->
        <div class="flex flex-col md:flex-row justify-end items-center space-y-2 md:space-y-0 md:space-x-4 mb-4">
            <!-- ユーザー追加ボタン -->
            <a href="{{ route('users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow h-10 flex items-center">
                ユーザー追加
            </a>

            <!-- CSVインポートフォーム -->
            <form id="csv-upload-form" action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                @csrf
                <label class="relative cursor-pointer bg-white border border-gray-300 rounded-lg shadow-sm px-4 py-2 text-gray-700 hover:bg-gray-100 h-10 flex items-center">
                    📂 ファイルを選択
                    <input type="file" id="csv-file-input" name="csv_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileUpload()">
                </label>
                <button type="submit" id="import-button" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow h-10 flex items-center">
                    CSVインポート
                </button>
            </form>

            <!-- CSVフォーマットダウンロード -->
            <a href="{{ route('users.download_format') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow h-10 flex items-center">
                📥 インポートフォーマットDL
            </a>
        </div>

        <!-- 検索フォーム -->
        <form method="GET" action="{{ route('users.index') }}" class="mb-4 bg-white p-4 rounded-lg shadow-md flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center space-x-4">
                <!-- 部門検索 -->
                <div>
                    <label class="text-gray-700">部門:</label>
                    <select name="department_id" class="border rounded px-3 py-1">
                        <option value="">全て</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- ユーザー名検索 -->
                <div>
                    <label class="text-gray-700">ユーザー名:</label>
                    <input type="text" name="name" class="border rounded px-3 py-1" value="{{ request('name') }}" placeholder="ユーザー名を入力">
                </div>
            </div>

            <!-- 検索ボタン -->
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                検索
            </button>
        </form>

        <!-- ユーザー一覧テーブル -->
        <div class="overflow-auto bg-white rounded-lg shadow p-4">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-300 text-gray-700">
                        <th class="p-3 text-left">ユーザー名</th>
                        <th class="p-3 text-left">部門</th>
                        <th class="p-3 text-left">メール</th>
                        <th class="p-3 text-left">電話番号</th>
                        <th class="p-3 text-center w-40">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-3">{{ $user->name }}</td>
                        <td class="p-3">{{ $user->department?->name }}</td>
                        <td class="p-3">{{ $user->email }}</td>
                        <td class="p-3">{{ $user->phone }}</td>
                        <td class="p-3 flex justify-center space-x-2">
                            <!-- 編集ボタン -->
                            <a href="{{ route('users.edit', $user->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md shadow-md text-sm">
                                編集
                            </a>

                            <!-- 削除ボタン（確認ダイアログ付き） -->
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirmDelete(event)">
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

        <!-- ページネーション -->
        <div class="mt-6 py-4 flex justify-end">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection