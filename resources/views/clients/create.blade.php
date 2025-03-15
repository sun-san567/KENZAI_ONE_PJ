@extends('layouts.app')

@section('content')
<div class="ml-64 w-[calc(50%-64px)] mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- ヘッダー -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold text-gray-800">クライアント登録</h1>
    </div>

    <!-- エラーメッセージ -->
    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-5 rounded-md">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-3">
                <ul class="text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- クライアント登録フォーム -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden border border-gray-200 p-6">
        <form action="{{ route('clients.store') }}" method="POST">
            @csrf

            <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">

            <!-- クライアント名 -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">クライアント名 <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" class="w-full border-gray-300 rounded-md p-2 shadow-sm" value="{{ old('name') }}" required>
            </div>

            <!-- 部門選択 -->
            <div class="mb-4">
                <label for="department_id" class="block text-sm font-medium text-gray-700">部門</label>
                <select name="department_id" id="department_id" class="w-full border-gray-300 rounded-md p-2 shadow-sm">
                    <option value="">なし</option>
                    @foreach ($departments as $department)
                    <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- 担当者選択（部門に応じて変更） -->
            <div class="mb-4">
                <label for="user_id" class="block text-sm font-medium text-gray-700">担当者</label>
                <select name="user_id" id="user_id" class="w-full border-gray-300 rounded-md p-2 shadow-sm">
                    <option value="">担当者を選択</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}" data-department="{{ $user->department_id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- ボタン -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('clients.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md shadow-sm hover:bg-gray-600">
                    キャンセル
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700">
                    登録
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('department_id').addEventListener('change', function() {
        let selectedDept = this.value;
        let userOptions = document.querySelectorAll('#user_id option');
        userOptions.forEach(option => {
            let deptId = option.getAttribute('data-department');
            option.style.display = (deptId === selectedDept || option.value === "") ? 'block' : 'none';
        });
    });
</script>
@endsection