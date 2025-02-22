@extends('layouts.app')

@section('title', 'フェーズ管理')

@section('content')
<div class="bg-white p-6 rounded shadow">
    <h1 class="text-xl font-bold mb-4">フェーズ管理</h1>

    <!-- フェーズ作成ボタン -->
    <a href="{{ route('phases.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
        新規フェーズ作成
    </a>

    <!-- フェーズ一覧テーブル -->
    <table class="w-full mt-4 border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">フェーズ名</th>
                <th class="border px-4 py-2">アクション</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($phases as $phase)
            <tr class="border">
                <td class="border px-4 py-2">{{ $phase->id }}</td>
                <td class="border px-4 py-2">{{ $phase->name }}</td>
                <td class="border px-4 py-2 flex space-x-2">
                    <a href="{{ route('phases.edit', $phase->id) }}" class="text-blue-500">編集</a>
                    <form action="{{ route('phases.destroy', $phase->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection