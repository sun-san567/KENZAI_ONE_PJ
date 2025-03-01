@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4 text-gray-800">クライアント登録</h1>

    @if ($errors->any())
    <div class="bg-red-200 text-red-700 p-3 rounded-lg mb-4 shadow">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('clients.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-6">
        @csrf

        <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">

        <!-- クライアント名 -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">クライアント名 <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" class="w-full border rounded-lg p-2" value="{{ old('name') }}" required>
        </div>

        <!-- 部門選択 -->
        <div class="mb-4">
            <label for="department_id" class="block text-sm font-medium text-gray-700">部門</label>
            <select name="department_id" id="department_id" class="w-full border rounded-lg p-2">
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
            <select name="user_id" id="user_id" class="w-full border rounded-lg p-2">
                <option value="">担当者を選択</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}" data-department="{{ $user->department_id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md">
                登録
            </button>
        </div>
    </form>
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