@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">顧客登録</h1>

    <!-- 成功メッセージ -->
    @if (session('success'))
    <div id="success-message" class="bg-green-200 p-2 text-green-700 mb-4 rounded shadow">
        {{ session('success') }}
    </div>
    @endif

    <!-- 顧客追加ボタン -->
    <div class="flex justify-end mb-4">
        <a href="{{ route('clients.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            顧客を追加
        </a>
    </div>

    <!-- 顧客情報一覧 -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-300 text-gray-700">
                    <th class="p-3 text-left">顧客名</th>
                    <th class="p-3 text-left">電話番号</th>
                    <th class="p-3 text-left">住所</th>
                    <th class="p-3 text-center w-40">操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clients as $client)
                <tr class="border-b hover:bg-gray-50 transition">
                    <td class="p-3">{{ $client->name }}</td>
                    <td class="p-3">{{ $client->phone }}</td>
                    <td class="p-3">{{ $client->address }}</td>
                    <td class="p-3 flex justify-center space-x-2">
                        <!-- 編集ボタン -->
                        <a href="{{ route('clients.edit', $client->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md shadow">
                            編集
                        </a>

                        <!-- 削除ボタン（確認ダイアログ付き） -->
                        <form action="{{ route('clients.destroy', $client->id) }}" method="POST" onsubmit="return confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md shadow">
                                削除
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ページネーション -->
    <div class="mt-6 py-4 flex justify-end">
        {{ $clients->links() }}
    </div>
</div>

<script>
    function confirmDelete(event) {
        if (!confirm("本当に削除しますか？")) {
            event.preventDefault();
        }
    }
</script>
@endsection