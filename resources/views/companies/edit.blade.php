@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">会社情報の編集</h1>

    @if (session('success'))
    <div class="bg-green-200 p-2 text-green-700 mb-4 rounded">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('companies.update', $company->id) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block font-bold mb-1">会社名</label>
            <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}"
                class="w-full border-gray-300 rounded p-2" required>
        </div>

        <div class="mb-4">
            <label for="address" class="block font-bold mb-1">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $company->address) }}"
                class="w-full border-gray-300 rounded p-2">
        </div>

        <div class="mb-4">
            <label for="phone" class="block font-bold mb-1">電話番号</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $company->phone) }}"
                class="w-full border-gray-300 rounded p-2">
        </div>

        <div class="mb-4">
            <label for="email" class="block font-bold mb-1">メールアドレス</label>
            <input type="email" id="email" name="email" value="{{ old('email', $company->email) }}"
                class="w-full border-gray-300 rounded p-2">
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                更新
            </button>
            <a href="{{ route('companies.index') }}" class="ml-4 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                キャンセル
            </a>
        </div>
    </form>
</div>
@endsection