@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">担当者追加</h1>

    @if ($errors->any())
    <div class="bg-red-200 p-2 text-red-700 mb-4 rounded">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('employees.store') }}" method="POST" class="bg-white p-6 shadow rounded-lg">
        @csrf

        <div class="mb-4">
            <label for="department_id" class="block font-bold">部門</label>
            <select name="department_id" id="department_id" class="w-full p-2 border rounded">
                <option value="">部門を選択</option>
                @foreach ($departments as $department)
                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="name" class="block font-bold">担当者名</label>
            <input type="text" name="name" id="name" class="w-full p-2 border rounded" value="{{ old('name') }}" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block font-bold">メールアドレス</label>
            <input type="email" name="email" id="email" class="w-full p-2 border rounded" value="{{ old('email') }}" required>
        </div>

        <div class="mb-4">
            <label for="phone" class="block font-bold">電話番号</label>
            <input type="text" name="phone" id="phone" class="w-full p-2 border rounded" value="{{ old('phone') }}">
        </div>

        <div class="flex justify-end space-x-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                追加
            </button>
            <a href="{{ route('employees.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                戻る
            </a>
        </div>
    </form>
</div>
@endsection