@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">部門管理</h1>

    @if (session('success'))
    <div class="bg-green-200 p-2 text-green-700 mb-4 rounded">
        {{ session('success') }}
    </div>
    @endif

    <a href="{{ route('departments.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
        部門追加
    </a>

    <table class="w-full bg-white shadow rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-gray-200">
                <th class="p-3">会社名</th>
                <th class="p-3">部門名</th>
                <th class="p-3">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($departments as $department)
            <tr class="border-b">
                <td class="p-3">{{ $department->company->name }}</td>
                <td class="p-3">{{ $department->name }}</td>
                <td class="p-3">
                    <a href="{{ route('departments.edit', $department->id) }}" class="text-blue-500">編集</a>
                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 ml-2">削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection