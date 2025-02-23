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

    <table class="min-w-full bg-white border border-gray-300">
        <thead>
            <tr>
                <th class="py-2 px-4 border">会社名</th>
                <th class="py-2 px-4 border">住所</th>
                <th class="py-2 px-4 border">電話番号</th>
                <th class="py-2 px-4 border">メール</th>
                <th class="py-2 px-4 border">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($companies as $company)
            <tr class="border">
                <td class="py-2 px-4">{{ $company->name }}</td>
                <td class="py-2 px-4">{{ $company->address }}</td>
                <td class="py-2 px-4">{{ $company->phone }}</td>
                <td class="py-2 px-4">{{ $company->email }}</td>
                <td class="py-2 px-4">
                    <a href="{{ route('companies.edit', $company->id) }}" class="text-blue-500">編集</a>
                    {{-- 削除ボタンは表示しない --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection