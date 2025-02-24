@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">部門編集</h1>

    @if ($errors->any())
    <div class="bg-red-100 text-red-600 p-4 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('departments.update', $department->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block font-semibold">部門名:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $department->name) }}" required
                class="w-full p-2 border border-gray-300 rounded">
        </div>

        <div class="mb-4">
            <label for="company_id" class="block font-semibold">所属会社:</label>
            <select id="company_id" name="company_id" class="w-full p-2 border border-gray-300 rounded">
                @foreach ($companies as $company)
                <option value="{{ $company->id }}"
                    {{ $department->company_id == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="flex justify-end space-x-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">更新</button>
            <a href="{{ route('departments.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">キャンセル</a>
        </div>
    </form>
</div>
@endsection