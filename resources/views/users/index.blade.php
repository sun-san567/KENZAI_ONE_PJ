@extends('layouts.app')

@section('content')
<div class="flex flex-col min-h-screen">
    <div class="container mx-auto p-6 flex-grow">
        <h1 class="text-xl font-bold mb-4 text-gray-800">ユーザー管理</h1>

        <!-- 成功メッセージ -->
        @if (session('success'))
        <div id="success-message" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 mb-4 rounded-md shadow">
            {{ session('success') }}
        </div>
        @endif

        <!-- CSVアップロード通知 -->
        <div id="upload-message" class="hidden bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 p-3 mb-4 rounded-md shadow">
            CSVのアップロードが完了しました！データをインポート中です...
        </div>

        <!-- インポート完了メッセージ -->
        <div id="import-complete-message" class="hidden bg-green-50 border-l-4 border-green-500 text-green-800 p-3 mb-4 rounded-md shadow">
            ✅ CSVのインポートが完了しました！
        </div>

        <!-- ボタンエリア -->
        <div class="flex flex-wrap justify-end items-center gap-3 mb-4">
            <!-- ユーザー追加ボタン -->
            <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 transition">
                ユーザー追加
            </a>

            <!-- CSVインポートフォーム -->
            <form id="csv-upload-form" action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
                @csrf
                <label class="relative cursor-pointer bg-white border border-gray-300 rounded-md shadow-sm px-4 py-2 text-gray-700 hover:bg-gray-100">
                    📂 ファイルを選択
                    <input type="file" id="csv-file-input" name="csv_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileUpload()">
                </label>
                <button type="submit" id="import-button" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md shadow hover:bg-green-700 transition">
                    CSVインポート
                </button>
            </form>

            <!-- CSVフォーマットダウンロード -->
            <a href="{{ route('users.download_format') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md shadow hover:bg-gray-700 transition">
                📥 インポートフォーマットDL
            </a>
        </div>

        <!-- 検索フォーム -->
        <form method="GET" action="{{ route('users.index') }}" class="mb-4 bg-white p-4 rounded-md shadow-md flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center space-x-4">
                <!-- 部門検索 -->
                <div>
                    <label class="text-gray-700 text-sm">部門:</label>
                    <select name="department_id" class="border-gray-300 rounded-md px-3 py-1 text-sm">
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
                    <label class="text-gray-700 text-sm">ユーザー名:</label>
                    <input type="text" name="name" class="border-gray-300 rounded-md px-3 py-1 text-sm" value="{{ request('name') }}" placeholder="ユーザー名を入力">
                </div>
            </div>

            <!-- 検索ボタン -->
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow hover:bg-blue-700 transition">
                検索
            </button>
        </form>

        <!-- ユーザー一覧テーブル -->
        <div class="overflow-x-auto bg-white rounded-md shadow-md p-4">
            <table class="w-full border border-gray-200">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b border-gray-300">
                        <th class="p-3 text-left text-sm font-medium">ユーザー名</th>
                        <th class="p-3 text-left text-sm font-medium">部門</th>
                        <th class="p-3 text-left text-sm font-medium">メール</th>
                        <th class="p-3 text-left text-sm font-medium">電話番号</th>
                        <th class="p-3 text-center w-40 text-sm font-medium">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-3 text-sm text-gray-800">{{ $user->name }}</td>
                        <td class="p-3 text-sm text-gray-800">{{ $user->department?->name }}</td>
                        <td class="p-3 text-sm text-gray-800">{{ $user->email }}</td>
                        <td class="p-3 text-sm text-gray-800">{{ $user->phone }}</td>
                        <td class="px-6 py-4 text-right text-sm">
                            <div class="flex justify-end space-x-2">
                                <!-- 編集ボタン -->
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-gray-600 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>

                                <!-- 削除ボタン -->
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('このユーザーを削除しますか？関連するデータにも影響します');"
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
                </tbody>
            </table>
        </div>

        <!-- ページネーション -->
        <div class="mt-6 py-4 flex justify-end">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
    function confirmDelete(event) {
        if (!confirm("本当に削除しますか？")) {
            event.preventDefault();
        }
    }

    function handleFileUpload() {
        document.getElementById('upload-message').classList.remove('hidden');
    }
</script>
@endsection