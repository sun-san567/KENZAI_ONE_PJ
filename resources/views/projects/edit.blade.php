@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-xl font-bold mb-4">案件管理</h2>

    <!-- モーダルを開くボタン -->
    <button @click="openModal = true" class="bg-blue-600 text-white px-4 py-2 rounded">案件編集</button>

    <!-- 案件編集モーダル -->
    <div x-show="openModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50"
        x-transition.opacity
        @click.self="openModal = false"
        x-data="{ activeTab: 'edit' }"
        x-cloak>
        <div class="bg-white p-6 rounded-lg shadow-lg w-96" @click.stop>
            <h2 class="text-lg font-bold mb-4">案件編集</h2>

            <!-- タブ切り替え -->
            <div class="flex mb-4 border-b">
                <button @click="activeTab = 'edit'"
                    class="px-4 py-2"
                    :class="activeTab === 'edit' ? 'border-b-2 border-blue-500' : 'text-gray-500'">
                    案件編集
                </button>
                <button @click="activeTab = 'files'"
                    class="px-4 py-2"
                    :class="activeTab === 'files' ? 'border-b-2 border-blue-500' : 'text-gray-500'">
                    ファイル管理
                </button>
            </div>

            <!-- 編集タブ -->
            <div x-show="activeTab === 'edit'">
                <form action="{{ route('projects.update', $project->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <label for="name" class="block">案件名</label>
                    <input type="text" id="name" name="name" class="w-full p-2 border rounded" value="{{ $project->name }}">

                    <label for="client_id" class="block mt-3">顧客</label>
                    <select id="client_id" name="client_id" class="w-full p-2 border rounded">
                        @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{ $project->client_id == $client->id ? 'selected' : '' }}>
                            {{ $client->name }}
                        </option>
                        @endforeach
                    </select>

                    <label for="phase_id" class="block mt-3">フェーズ</label>
                    <select id="phase_id" name="phase_id" class="w-full p-2 border rounded">
                        @foreach ($phases as $phase)
                        <option value="{{ $phase->id }}" {{ $project->phase_id == $phase->id ? 'selected' : '' }}>
                            {{ $phase->name }}
                        </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-4">更新</button>
                </form>
            </div>

            <!-- ファイル管理タブ -->
            <div x-show="activeTab === 'files'">
                <h3 class="text-lg font-bold mb-3">ファイルアップロード</h3>
                <form action="{{ route('projects.files.upload', $project->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="files[]" multiple class="block w-full p-2 border rounded">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">アップロード</button>
                </form>

                <h3 class="text-lg font-bold mt-4 mb-2">アップロード済みファイル</h3>
                <ul>
                    @foreach ($project->files as $file)
                    <li class="flex justify-between p-2 border rounded mt-2">
                        <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ $file->file_name }}</a>
                        <span class="text-gray-600 text-sm">{{ $file->file_type }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- 閉じるボタン -->
            <button @click="openModal = false" class="mt-4 w-full text-center text-gray-600">キャンセル</button>
        </div>
    </div>
</div>
@endsection