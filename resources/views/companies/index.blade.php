@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold text-gray-800">会社情報</h1>
        @if ($companies->isEmpty())
        <a href="{{ route('companies.create') }}"
            class="inline-flex items-center space-x-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md transition">
            <span>➕</span>
            <span>会社を登録</span>
        </a>
        @endif
    </div>

    @if (session('success'))
    <div class="bg-green-200 text-green-700 p-3 rounded-lg mb-4 shadow">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="bg-red-200 text-red-700 p-3 rounded-lg mb-4 shadow">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <table class="w-full table-fixed border-collapse">
            <thead>
                <tr class="bg-gray-300 text-gray-700">
                    <th class="p-4 text-left border-b w-1/5">会社名</th>
                    <th class="p-4 text-left border-b w-2/5">住所</th>
                    <th class="p-4 text-left border-b w-1/5">電話番号</th>
                    <th class="p-4 text-left border-b w-1/5">メール</th>
                    <th class="p-4 text-center border-b w-1/6">操作</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach ($companies as $company)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="p-4">{{ $company->name }}</td>
                    <td class="p-4">{{ $company->address }}</td>
                    <td class="p-4">{{ $company->phone }}</td>
                    <td class="p-4">{{ $company->email }}</td>
                    <td class="p-4 text-center">
                        <div class="inline-flex space-x-2">
                            <!-- 編集ボタン -->
                            <a href="{{ route('companies.edit', $company->id) }}"
                                class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md transition">
                                <span>✏️</span>
                                <span>編集</span>
                            </a>

                            <!-- 削除ボタン（非表示） -->
                            {{-- <form action="{{ route('companies.destroy', $company->id) }}" method="POST"
                            onsubmit="return confirm('本当に削除しますか？');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center space-x-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md transition">
                                <span>🗑️</span>
                                <span>削除</span>
                            </button>
                            </form> --}}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection