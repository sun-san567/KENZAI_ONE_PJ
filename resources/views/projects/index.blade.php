@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6" x-data="{ openModal: false, selectedProject: null }">

    <!-- ヘッダー部分（案件追加ボタン） -->
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold text-gray-800">プロジェクト管理</h1>
        <button @click="openModal = true; selectedProject = null" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md">
            + 案件追加
        </button>
    </div>

    <!-- フェーズ一覧 -->
    <div class="flex space-x-4 overflow-x-auto pb-4">
        @foreach ($phases as $phase)
        <div class="w-1/5 bg-gray-200 p-4 rounded-lg shadow">
            <h2 class="text-lg font-bold">{{ $phase->name }}</h2>

            <!-- フェーズ内の案件一覧 -->
            <div class="mt-4 space-y-2">
                @foreach ($projects[$phase->id] ?? [] as $project)
                <!-- 🖊 チケット全体をクリック可能にする -->
                <div class="bg-white p-3 rounded-lg shadow cursor-pointer" 
                     @click="openModal = true; selectedProject = {{ json_encode($project) }}">
                    <h3 class="font-semibold">{{ $project->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $project->description }}</p>
                    <p class="text-sm font-bold text-blue-600">売上: ¥{{ number_format($project->revenue ?? 0) }}</p>
                    <p class="text-sm font-bold text-green-600">粗利: ¥{{ number_format($project->profit ?? 0) }}</p>

                    <!-- カテゴリ表示 -->
                    <div class="flex flex-wrap mt-2">
                        @foreach ($project->categories as $category)
                        <span class="bg-blue-200 text-blue-800 text-xs font-semibold px-2 py-1 rounded mr-2 mb-1">
                            {{ $category->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <!-- 案件追加・編集モーダル -->
    <div x-show="openModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 transition-opacity"
        x-transition.opacity
        x-cloak
        @click.self="openModal = false">

        <!-- モーダルコンテンツ -->
        <div class="bg-white p-6 rounded-lg shadow-lg w-96" @click.stop>
            <h2 class="text-lg font-bold mb-4" x-text="selectedProject ? '案件を編集' : '案件を追加'"></h2>

            <form method="POST" :action="selectedProject ? `/projects/${selectedProject.id}` : '{{ route('projects.store') }}'">
                @csrf
                <template x-if="selectedProject">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="mb-3">
                    <label class="block font-bold mb-1">案件名</label>
                    <input type="text" name="name" class="w-full border p-2 rounded"
                           x-model="selectedProject ? selectedProject.name : ''" required>
                </div>

                <div class="mb-3">
                    <label class="block font-bold mb-1">顧客</label>
                    <select name="client_id" class="w-full border p-2 rounded">
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" 
                                x-bind:selected="selectedProject && selectedProject.client_id == {{ $client->id }}">
                            {{ $client->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block font-bold mb-1">フェーズ選択</label>
                    <select name="phase_id" class="w-full border p-2 rounded">
                        @foreach($phases as $phase)
                        <option value="{{ $phase->id }}" 
                                x-bind:selected="selectedProject && selectedProject.phase_id == {{ $phase->id }}">
                            {{ $phase->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- カテゴリ選択 -->
                <div class="mb-3">
                    <label class="block font-bold mb-1">カテゴリ</label>
                    <select name="category_id[]" class="w-full border p-2 rounded" multiple>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}"
                                x-bind:selected="selectedProject && selectedProject.categories.find(c => c.id == {{ $category->id }})">
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="block font-bold mb-1">売上</label>
                    <input type="number" name="revenue" class="w-full border p-2 rounded"
                           x-model="selectedProject ? selectedProject.revenue : 0">
                </div>

                <div class="mb-3">
                    <label class="block font-bold mb-1">粗利</label>
                    <input type="number" name="profit" class="w-full border p-2 rounded"
                           x-model="selectedProject ? selectedProject.profit : 0">
                </div>

                <button type="submit" 
                        class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md">
                    <span x-text="selectedProject ? '更新' : '追加'"></span>
                </button>
            </form>

            <button @click="openModal = false" class="mt-4 w-full text-center text-gray-600">
                キャンセル
            </button>
        </div>
    </div>

</div>
@endsection
