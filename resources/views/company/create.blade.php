@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">新規会社登録</h1>

    <form action="{{ route('companies.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="block font-bold">会社名</label>
            <input type="text" name="name" class="w-full border p-2" required>
        </div>

        <div class="mb-3">
            <label class="block font-bold">住所</label>
            <input type="text" name="address" class="w-full border p-2">
        </div>

        <div class="mb-3">
            <label class="block font-bold">電話番号</label>
            <input type="text" name="phone" class="w-full border p-2">
        </div>

        <div class="mb-3">
            <label class="block font-bold">メールアドレス</label>
            <input type="email" name="email" class="w-full border p-2">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">登録</button>
        <a href="{{ route('companies.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">戻る</a>
    </form>
</div>
@endsection