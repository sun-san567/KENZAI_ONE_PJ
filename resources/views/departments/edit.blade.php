@extends('layouts.app')

@section('title', '部門編集')

@section('content')
<div class="ml-64 w-[calc(50%-64px)] mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-xl font-semibold text-gray-800 border-b border-gray-300 pb-4">部門編集</h1>

    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-sm mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white shadow rounded-lg border border-gray-200 p-6 mt-6">
        <form action="{{ route('departments.update', $department->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- 部門名 -->
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-medium mb-1">部門名 <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name', $department->name) }}"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- 所属会社 -->
            <div class="mb-4">
                <label for="company_id" class="block text-gray-700 font-medium mb-1">所属会社 <span class="text-red-500">*</span></label>
                <select id="company_id" name="company_id"
                    class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
                    <option value="{{ auth()->user()->company_id }}" selected>
                        {{ auth()->user()->company->name }}
                    </option>
                </select>
            </div>


            <!-- ボタン -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('company.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow-md transition">
                    キャンセル
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-md transition">
                    更新
                </button>
            </div>
        </form>
    </div>
</div>
@endsection