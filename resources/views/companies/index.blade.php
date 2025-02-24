@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">会社情報</h1>

    @if (session('success'))
    <div class="bg-green-200 p-2 text-green-700 mb-4 rounded">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-200 p-2 text-red-700 mb-4 rounded">
        {{ session('error') }}
    </div>
    @endif

    <table class="w-full bg-white shadow rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3">会社名</th>
                <th class="p-3">住所</th>
                <th class="p-3">電話番号</th>
                <th class="p-3">メール</th>
                <th class="p-3">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companies as $company)
            <tr class="border-b">
                <td class="p-3">{{ $company->name }}</td>
                <td class="p-3">{{ $company->address }}</td>
                <td class="p-3">{{ $company->phone }}</td>
                <td class="p-3">{{ $company->email }}</td>
                <td class="p-3">
                    <a href="{{ route('companies.edit', $company->id) }}" class="text-blue-500">編集</a>
                    {{-- 削除ボタンは不要なため非表示 --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection