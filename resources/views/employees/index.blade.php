@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">担当者管理</h1>

    <!-- 成功メッセージ -->
    @if (session('success'))
    <div class="bg-green-200 p-2 text-green-700 mb-4 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- CSVアップロード通知 -->
    <div id="upload-message" class="hidden bg-yellow-200 text-yellow-800 p-3 rounded-lg mb-4">
        CSVのアップロードが完了しました！データをインポート中です...
    </div>

    <!-- ローディングメッセージ -->
    <div id="loading-message" class="hidden text-center text-gray-600 mt-4">
        <span class="animate-spin border-4 border-gray-300 border-t-blue-500 rounded-full w-6 h-6 inline-block"></span>
        インポート処理中...
    </div>

    <!-- ボタン＆CSVインポートを横並びで配置 -->
    <div class="flex items-center space-x-4 mb-4">
        <!-- 担当者追加ボタン -->
        <a href="{{ route('employees.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            担当者追加
        </a>

        <!-- CSVインポートフォーム -->
        <form id="csv-upload-form" action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-2">
            @csrf
            <button type="submit" id="import-button" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                CSVインポート
            </button>
            <label class="relative cursor-pointer bg-white border border-gray-300 rounded-lg shadow-sm px-4 py-2 text-gray-700 hover:bg-gray-100">
                📂 ファイルを選択
                <input type="file" id="csv-file-input" name="csv_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="handleFileUpload()">
            </label>

        </form>
    </div>

    <table class="w-full bg-white shadow rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3">担当者名</th>
                <th class="p-3">部門</th>
                <th class="p-3">メール</th>
                <th class="p-3">電話番号</th>
                <th class="p-3">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
            <tr class="border-b">
                <td class="p-3">{{ $employee->name }}</td>
                <td class="p-3">{{ $employee->department->name }}</td>
                <td class="p-3">{{ $employee->email }}</td>
                <td class="p-3">{{ $employee->phone }}</td>
                <td class="p-3">
                    <a href="{{ route('employees.edit', $employee->id) }}" class="text-blue-500">編集</a>
                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 ml-2">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- JavaScript -->
<script>
    function handleFileUpload() {
        let fileInput = document.getElementById('csv-file-input');
        let uploadMessage = document.getElementById('upload-message');
        let loadingMessage = document.getElementById('loading-message');

        if (fileInput.files.length > 0) {
            uploadMessage.classList.remove('hidden'); // アップロード完了メッセージ表示
            loadingMessage.classList.remove('hidden'); // ローディング表示
        }
    }
</script>
@endsection