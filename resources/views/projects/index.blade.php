@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6" x-data="{
    openModal: false,
    selectedProject: null,
    activeTab: 'edit',
    toggleCategory(categoryId) {
        if (this.selectedProject.categories.some(c => c.id === categoryId)) {
            this.selectedProject.categories = this.selectedProject.categories.filter(c => c.id !== categoryId);
        } else {
            this.selectedProject.categories.push({ id: categoryId });
        }
    }
}">

    <h2 class="text-2xl font-bold mb-6">案件管理</h2>

    <!-- 📌 案件追加ボタン -->
    <button @click="openModal = true; selectedProject = { categories: [] }"
        class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg transition transform hover:bg-blue-700 hover:scale-105">
        + 案件追加
    </button>

    <!-- 📌 フェーズごとの案件一覧 -->
    <div class="w-full max-w-[1920px] mx-auto overflow-x-auto pb-6 hide-scrollbar">
        <div class="flex space-x-6 min-w-max px-4">
            @foreach($phases as $phase)
            <div class="w-96 flex-shrink-0 bg-white rounded-lg shadow-sm border border-gray-200 p-4 min-h-[200px]">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-gray-800">{{ $phase->name }}</h3>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $phase->projects->count() }}</span>
                </div>

                <div class="space-y-4 max-h-[calc(100vh-220px)] overflow-y-auto pr-1">
                    @if($phase->projects->count() > 0)
                    @foreach($phase->projects as $project)
                    <div class="bg-white border border-gray-200 p-4 rounded-md shadow-sm cursor-pointer hover:border-blue-300 hover:bg-blue-50/10 transition-colors w-full"
                        @click="openModal = true; selectedProject = { ...{{ $project->toJson() }}, categories: {{ $project->categories->toJson() }} || [] }; activeTab = 'edit'">
                        <h3 class="font-semibold text-gray-800">{{ $project->name }}</h3>
                        <p class="text-sm text-gray-600 mt-2">{{ $project->description }}</p>

                        <!-- 取引先名 -->
                        <div class="mt-2 pt-2 border-t border-gray-100">
                            <div class="flex items-center">
                                <span class="text-xs text-gray-500 w-14">取引先：</span>
                                <p class="text-sm font-medium text-gray-700 ml-1">{{ $project->client->name ?? '未設定' }}</p>
                            </div>
                        </div>

                        <div class="mt-0.5 space-y-1.5">
                            <div class="flex items-center">
                                <span class="text-xs text-gray-500 w-14">売上：</span>
                                <p class="text-sm font-medium text-blue-700 ml-1">¥{{ number_format($project->revenue ?? 0) }}</p>
                            </div>
                            <div class="flex items-center">
                                <span class="text-xs text-gray-500 w-14">粗利：</span>
                                <p class="text-sm font-medium text-green-700 ml-1">¥{{ number_format($project->profit ?? 0) }}</p>
                            </div>
                        </div>

                        @if(count($project->categories) > 0)
                        <div class="grid grid-cols-2 gap-2 mt-3 pt-2 max-h-[4.5rem] overflow-hidden">
                            @foreach ($project->categories as $category)
                            <span class="inline-flex bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-1 rounded truncate">
                                {{ $category->name }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @else
                    <div class="flex flex-col items-center justify-center h-32 border border-dashed border-gray-300 rounded-lg bg-gray-50">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">プロジェクトがありません</p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- 📌 案件編集モーダル -->
    <div x-show="openModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 transition-opacity"
        x-transition.opacity
        @click.self="openModal = false"
        x-cloak>


        <div class="bg-white p-8 rounded-3xl shadow-lg w-[700px] 　max-h-[75vh] overflow-y-auto transform transition-transform my-6 mx-auto p-6" @click.stop>





            <!-- タイトル & 閉じるボタン -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold" x-text="selectedProject ? '案件編集' : '案件追加'"></h2>
                <button @click="openModal = false" class="text-gray-500 hover:text-gray-800 text-3xl">&times;</button>
            </div>

            <!-- タブ切り替え -->
            <div class="flex mb-6 border-b">
                <button @click="activeTab = 'edit'"
                    class="px-6 py-3 font-semibold transition border-b-4 border-blue-500 text-blue-600"
                    :class="activeTab === 'edit' ? 'border-b-4 border-blue-500 text-blue-600' : 'text-gray-500'">
                    案件編集
                </button>
                <!-- <button @click="activeTab = 'files'"
                    class="px-6 py-3 font-semibold transition border-b-4 border-blue-500 text-blue-600"
                    :class="activeTab === 'files' ? 'border-b-4 border-blue-500 text-blue-600' : 'text-gray-500'"
                    x-show="selectedProject">
                    ファイル管理
                </button> -->
            </div>

            <!-- 📌 案件編集タブ -->
            <div x-show="activeTab === 'edit'">
                <form :action="selectedProject ? `/projects/${selectedProject.id}` : '{{ route('projects.store') }}'" method="POST">
                    @csrf
                    <template x-if="selectedProject">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-1 gap-8">
                        <!-- 案件情報 -->
                        <div>
                            <label class="block font-medium mb-2">案件名</label>
                            <input type="text" name="name"
                                class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 shadow-md"
                                x-model="selectedProject ? selectedProject.name : ''">
                        </div>

                        <div class="grid grid-cols-2 gap-8">
                            <!-- フェーズ -->
                            <div>
                                <label class="block font-medium mb-1 text-sm">フェーズ</label>
                                <select name="phase_id"
                                    class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 shadow-md">
                                    @foreach ($phases as $phase)
                                    <option value="{{ $phase->id }}"
                                        x-bind:selected="selectedProject && selectedProject.phase_id == {{ $phase->id }}">
                                        {{ $phase->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 顧客 -->
                            <div>
                                <label class="block font-medium mb-1 text-sm">顧客</label>
                                <select name="client_id"
                                    class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 shadow-md">
                                    @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        x-bind:selected="selectedProject && selectedProject.client_id == {{ $client->id }}">
                                        {{ $client->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- 説明 -->
                        <div>
                            <label class="block font-medium mb-2">説明</label>
                            <textarea name="description"
                                class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 h-32 shadow-md"
                                x-model="selectedProject ? selectedProject.description : ''"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <!-- 売上 -->
                            <div>
                                <label class="block font-medium mb-1 text-sm">売上</label>
                                <input type="number" name="revenue" step="1" min="0"
                                    class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 text-right shadow-md"
                                    :value="selectedProject ? Math.floor(selectedProject.revenue) : ''"
                                    @input="selectedProject ? selectedProject.revenue = Math.floor($event.target.value) || 0 : ''">
                            </div>

                            <!-- 粗利 -->
                            <div>
                                <label class="block font-medium mb-1 text-sm">粗利</label>
                                <input type="number" name="profit" step="1" min="0"
                                    class="w-full p-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 text-right shadow-md"
                                    :value="selectedProject ? Math.floor(selectedProject.profit) : ''"
                                    @input="selectedProject ? selectedProject.profit = Math.floor($event.target.value) || 0 : ''">
                            </div>
                        </div>

                        <!-- カテゴリ選択（タグ形式） -->
                        <div>
                            <label class="block font-medium mb-1">カテゴリ</label>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach ($categories as $category)
                                <label class="inline-flex items-center px-3.5 py-2 rounded-md border border-transparent transition-all duration-200 cursor-pointer select-none text-sm"
                                    :class="selectedProject?.categories?.some(c => c.id == {{ $category->id }}) ? 
                                        'bg-blue-100 text-blue-800 border-blue-200 font-medium shadow-sm' : 
                                        'bg-gray-50 text-gray-600 border-gray-100 hover:bg-gray-100 hover:border-gray-200'">
                                    <input type="checkbox" name="category_id[]" value="{{ $category->id }}" class="hidden"
                                        :checked="selectedProject?.categories?.some(c => c.id == {{ $category->id }})"
                                        @change="toggleCategory({{ $category->id }})">
                                    <svg class="w-4 h-4 mr-1.5"
                                        :class="selectedProject?.categories?.some(c => c.id == {{ $category->id }}) ? 'text-blue-600' : 'text-gray-400'"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path :stroke-width="selectedProject?.categories?.some(c => c.id == {{ $category->id }}) ? 2 : 1.5"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ $category->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- 最適化されたボタンレイアウト -->
                    <div class="mt-8 pt-5 border-t border-gray-200">
                        <!-- ファイル管理ナビゲーション - セカンダリーアクション -->
                        <div x-show="selectedProject && selectedProject.id" class="mb-5">
                            <a :href="`/projects/${selectedProject.id}/files`"
                                class="inline-flex items-center text-blue-600 hover:text-blue-800 py-2.5 px-4 rounded-lg hover:bg-blue-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>ファイル管理へ移動</span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>

                        <!-- プライマリー操作 - 明確な視覚的階層 -->
                        <div class="flex justify-end items-center gap-3">
                            <button @click="openModal = false" type="button"
                                class="min-w-[120px] py-3 px-5 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-400 shadow-sm transition-colors text-sm font-medium">
                                キャンセル
                            </button>

                            <button type="submit"
                                class="min-w-[120px] py-3 px-5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-600 shadow-sm transition-colors text-sm font-medium"
                                :class="{'opacity-50 cursor-not-allowed': isSubmitting}"
                                :disabled="isSubmitting"
                                x-text="selectedProject ? '変更を保存' : 'プロジェクト作成'">
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- 📌 ファイル管理タブ -->
            <!-- <div x-show="activeTab === 'files'" class="mt-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">ファイル管理</h3>
                    <a :href="`/projects/${selectedProject.id}/files`"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        詳細管理へ
                    </a>
                </div> -->

            <!-- 最近のファイル一覧（シンプルな表示） -->
            <!-- <div class="space-y-2">
                    <template x-for="file in recentFiles" :key="file.id">
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span x-text="file.file_name"></span>
                            <span x-text="formatDate(file.created_at)"></span>
                        </div>
                    </template>
                </div> -->
            <!-- </div> -->

        </div>


        @endsection

        <style>
            .hide-scrollbar::-webkit-scrollbar {
                height: 8px;
            }

            .hide-scrollbar::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 8px;
            }

            .hide-scrollbar::-webkit-scrollbar-thumb {
                background: #ccc;
                border-radius: 8px;
            }

            .hide-scrollbar::-webkit-scrollbar-thumb:hover {
                background: #aaa;
            }
        </style>