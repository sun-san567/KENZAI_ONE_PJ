@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">部門管理</h1>

    @if (session('success'))
    <div class="bg-green-200 p-2 text-green-700 mb-4 rounded">
        {{ session('success') }}
    </div>
    @endif

    <!-- ボタンエリア（会社情報のレイアウトと統一） -->
    <div class="flex justify-end mb-6">
        <a href="{{ route('departments.create') }}"
            class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2 rounded-lg shadow-md transition">
            ➕ <span>部門追加</span>
        </a>
    </div>

    <!-- テーブル -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-gray-300 text-gray-700 font-semibold">
                    <th class="p-4 text-left border-b w-4/5">部門名</th>
                    <th class="p-4 text-center border-b w-1/5">操作</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach ($departments as $department)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="p-4">{{ $department->name }}</td>
                    <td class="p-4 text-center">
                        <div class="inline-flex space-x-2">
                            <!-- 編集ボタン（青） -->
                            <a href="{{ route('departments.edit', $department->id) }}"
                                class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md transition">
                                ✏️ <span>編集</span>
                            </a>

                            <!-- 削除ボタン（赤 & ポップアップ表示） -->
                            <button onclick="confirmDelete({{ $department->id }})"
                                class="inline-flex items-center space-x-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md transition">
                                🗑️ <span>削除</span>
                            </button>

                            <!-- 削除用フォーム（非表示） -->
                            <form id="delete-form-{{ $department->id }}" action="{{ route('departments.destroy', $department->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- 削除確認モーダル -->
<div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-lg font-bold text-gray-800 mb-4">⚠️ 部門を削除しますか？</h2>
        <p class="text-gray-600 text-sm mb-4">この部門を削除すると、関連する担当者データもすべて削除されます。</p>
        <div class="flex justify-end space-x-4">
            <button onclick="closeModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                キャンセル
            </button>
            <button id="confirmDeleteBtn" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                削除する
            </button>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    function confirmDelete(departmentId) {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('confirmDeleteBtn').onclick = function() {
            document.getElementById('delete-form-' + departmentId).submit();
        };
    }

    function closeModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection