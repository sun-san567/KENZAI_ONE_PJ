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
        <div class="mb-4">
            <label for="company_id" class="block text-sm font-medium text-gray-700">会社</label>
            <select name="company_id" id="company_id" class="w-full border rounded-lg p-2">
                <option value="">会社を選択</option>
                @foreach ($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">クライアント名</label>
            <input type="text" name="name" id="name" class="w-full border rounded-lg p-2" value="{{ old('name') }}" required>
        </div>

        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">電話番号</label>
            <input type="text" name="phone" id="phone" class="w-full border rounded-lg p-2" value="{{ old('phone') }}">
        </div>

        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700">住所</label>
            <textarea name="address" id="address" class="w-full border rounded-lg p-2">{{ old('address') }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg shadow-md transition">
                登録
            </button>
        </div>
    </form>
</div>
@endsection