@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">部門の追加</h1>

    <form action="{{ route('departments.store') }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf

        <div class="mb-4">
            <label for="company_id" class="block font-bold mb-1">会社を選択</label>
            <select id="company_id" name="company_id" class="w-full border-gray-300 rounded p-2">
                @foreach ($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="name" class="block font-bold mb-1">部門名</label>
            <input type="text" id="name" name="name" class="w-full border-gray-300 rounded p-2" required>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg">追加</button>
            <a href="{{ route('departments.index') }}" class="ml-4 bg-gray-500 text-white px-4 py-2 rounded-lg">キャンセル</a>
        </div>
    </form>
</div>
@endsection