@extends('layouts.app')

@section('content')
<div class="w-full max-w-[90%] xl:max-w-screen-xl px-4 sm:px-6 md:px-8 lg:px-10 xl:px-12 mx-auto transition-all duration-300"
    x-bind:class="sidebarOpen ? 'ml-64' : 'ml-16'"
    x-data="{
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

    <!-- 📌 PC用: 見出し横に配置 -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">案件管理</h2>
        <button @click="openModal = true; selectedProject = { categories: [] }"
            x-show="!openModal"
            x-cloak
            class="hidden md:block bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow-lg transition">
            + 案件追加
        </button>
    </div>

    <!-- 📌 モバイル用: フローティングボタン -->
    <button @click="openModal = true; selectedProject = { categories: [] }"
        x-show="!openModal"
        x-cloak
        class="fixed md:hidden z-50 shadow-lg transition hover:shadow-xl hover:scale-105
               bottom-6 right-6 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-full">
        +
    </button>

    <!-- 画面上部に表示するステータス -->
    <!-- <div class="bg-white p-4 mb-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-800">
                プロジェクト管理
            </h2>

             既存のコンテンツ -->
    <!-- </div>
    </div> -->

    <!-- 📌 フェーズごとの案件一覧 -->
    <div class="w-full max-w-[1920px] mx-auto overflow-x-auto pb-6 px-4 hide-scrollbar-x bg-gray-100">
        <div class="flex space-x-6 min-w-max px-4 pb-4">
            @foreach($phases as $phase)
            <div style="width: 280px; min-width: 280px; max-width: 280px;" class="flex-shrink-0 bg-white rounded-lg shadow-sm border border-gray-200 p-4 h-[calc(100vh-150px)] flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-gray-800">{{ $phase->name }}</h3>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">{{ $phase->projects->count() }}</span>
                </div>

                @if($phase->projects->count() > 0)
                <div class="space-y-4 overflow-y-auto hide-scrollbar-y pr-2 flex-grow bg-white">
                    <!-- 最初の5つのプロジェクトを表示 -->
                    @foreach($phase->projects->take(5) as $index => $project)
                    <div class="project-card bg-white border border-gray-200 p-4 rounded-md shadow-sm cursor-pointer hover:border-blue-300 hover:bg-blue-50/10 transition-colors"
                        @click="openModal = true; selectedProject = { ...{{ $project->toJson() }}, categories: {{ $project->categories->toJson() }} || [] }; activeTab = 'edit'">
                        <h3 class="font-semibold text-gray-800 truncate">{{ $project->name }}</h3>
                        <!-- <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $project->description }}</p> -->

                        <!-- 取引先名 -->
                        <div class="mt-2 pt-2 border-t border-gray-100">
                            <div class="flex items-center">
                                <span class="text-xs text-gray-500 w-16 flex-shrink-0">取引先：</span>
                                <p class="text-sm font-medium text-gray-700 ml-1 truncate">{{ $project->client->name ?? '未設定' }}</p>
                            </div>
                        </div>

                        <div class="mt-0.5 space-y-1.5">
                            <div class="flex items-center">
                                <span class="text-xs text-gray-500 w-16 flex-shrink-0">売上：</span>
                                <p class="text-sm font-medium text-blue-700 ml-1">¥{{ number_format($project->revenue ?? 0) }}</p>
                            </div>
                            <div class="flex items-center">
                                <span class="text-xs text-gray-500 w-16 flex-shrink-0">粗利：</span>
                                <p class="text-sm font-medium text-green-700 ml-1">¥{{ number_format($project->profit ?? 0) }}</p>
                            </div>
                        </div>

                        @if(count($project->categories) > 0)
                        <div class="flex flex-wrap gap-1 mt-3 pt-2">
                            @foreach ($project->categories->take(3) as $category)
                            <span class="inline-flex bg-gray-100 text-gray-600 text-xs font-medium px-2 py-0.5 rounded truncate">
                                {{ $category->name }}
                            </span>
                            @endforeach
                            @if(count($project->categories) > 3)
                            <span class="inline-flex bg-gray-100 text-gray-600 text-xs font-medium px-2 py-0.5 rounded">
                                +{{ count($project->categories) - 3 }}
                            </span>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endforeach

                    <!-- 残りのプロジェクト（最初は非表示） -->
                    @if($phase->projects->count() > 5)
                    <div class="hidden-projects hidden">
                        @foreach($phase->projects->skip(5) as $project)
                        <div class="project-card bg-white border border-gray-200 p-4 rounded-md shadow-sm cursor-pointer hover:border-blue-300 hover:bg-blue-50/10 transition-colors mt-4"
                            @click="openModal = true; selectedProject = { ...{{ $project->toJson() }}, categories: {{ $project->categories->toJson() }} || [] }; activeTab = 'edit'">
                            <h3 class="font-semibold text-gray-800 truncate">{{ $project->name }}</h3>
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $project->description }}</p>

                            <!-- 取引先名 -->
                            <div class="mt-2 pt-2 border-t border-gray-100">
                                <div class="flex items-center">
                                    <span class="text-xs text-gray-500 w-16 flex-shrink-0">取引先：</span>
                                    <p class="text-sm font-medium text-gray-700 ml-1 truncate">{{ $project->client->name ?? '未設定' }}</p>
                                </div>
                            </div>

                            <div class="mt-0.5 space-y-1.5">
                                <div class="flex items-center">
                                    <span class="text-xs text-gray-500 w-16 flex-shrink-0">売上：</span>
                                    <p class="text-sm font-medium text-blue-700 ml-1">¥{{ number_format($project->revenue ?? 0) }}</p>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-xs text-gray-500 w-16 flex-shrink-0">粗利：</span>
                                    <p class="text-sm font-medium text-green-700 ml-1">¥{{ number_format($project->profit ?? 0) }}</p>
                                </div>
                            </div>

                            @if(count($project->categories) > 0)
                            <div class="flex flex-wrap gap-1 mt-3 pt-2">
                                @foreach ($project->categories->take(3) as $category)
                                <span class="inline-flex bg-gray-100 text-gray-600 text-xs font-medium px-2 py-0.5 rounded truncate">
                                    {{ $category->name }}
                                </span>
                                @endforeach
                                @if(count($project->categories) > 3)
                                <span class="inline-flex bg-gray-100 text-gray-600 text-xs font-medium px-2 py-0.5 rounded">
                                    +{{ count($project->categories) - 3 }}
                                </span>
                                @endif
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- もっと見るボタン -->
                    <div class="flex justify-center mt-2 bg-white">
                        <button class="show-more-btn text-sm text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-md transition-colors flex items-center">
                            <span>もっと見る</span>
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                    @endif
                </div>
                @else
                <div class="border border-dashed border-gray-300 rounded-lg bg-gray-50 flex-grow flex flex-col items-center justify-center" style="width: calc(100% - 8px);">
                    <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">プロジェクトがありません</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <!-- 📌 案件編集モーダル -->
    <div x-show="openModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity overflow-y-auto"
        x-transition.opacity
        @click.self="openModal = false"
        x-cloak
        x-effect="if(openModal) { document.body.style.overflow = 'hidden'; } else { document.body.style.overflow = ''; }">

        <div class="min-h-screen py-6 flex flex-col justify-center sm:py-12">
            <div class="bg-white rounded-3xl shadow-lg w-[700px] max-w-[700px] mx-auto p-10 transform transition-transform" @click.stop>


                <!-- タイトル & 閉じるボタン -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold" x-text="selectedProject ? '案件編集' : '案件追加'"></h2>
                    <button @click="openModal = false" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-700 hover:bg-red-100 hover:text-red-600 transition-colors focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- タブ切り替え -->
                <div class="flex mb-6 border-b">
                    <button @click="activeTab = 'edit'"
                        class="px-6 py-3 font-semibold transition border-b-4 border-blue-500 text-blue-600"
                        :class="activeTab === 'edit' ? 'border-b-4 border-blue-500 text-blue-600' : 'text-gray-500'">
                        案件詳細
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
                    <form x-init="storeUrl = '{{ route('projects.store') }}'"
                        :action="selectedProject ? `/projects/${selectedProject.id}` : storeUrl"
                        method="POST">

                        @csrf

                        <!-- 編集時に PUT メソッドを適用 -->
                        <template x-if="selectedProject">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="grid grid-cols-1 gap-6">
                            <!-- 案件情報 -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">案件名</label>
                                <input type="text" name="name"
                                    class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-400"
                                    x-model="selectedProject ? selectedProject.name : ''">
                            </div>


                            <div class="grid grid-cols-2 gap-6">
                                <!-- フェーズ -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">フェーズ</label>
                                    <select name="phase_id"
                                        class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-400">
                                        @foreach ($phases as $phase)
                                        <option value="{{ $phase->id }}"
                                            x-bind:selected="selectedProject && selectedProject.phase_id == {{ $phase->id }}">
                                            {{ $phase->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- 顧客 -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">顧客</label>
                                    <select name="client_id"
                                        class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-400">
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
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">説明</label>
                                <textarea name="description"
                                    class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-400 h-32"
                                    x-model="selectedProject ? selectedProject.description : ''"></textarea>
                            </div>

                            <!-- 日付関連フィールド追加 -->
                            <div class="grid grid-cols-3 gap-6">
                                <!-- 見積期限 -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">見積期限</label>
                                    <input type="date" name="estimate_deadline"
                                        class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-400"
                                        :value="selectedProject && selectedProject.estimate_deadline ? selectedProject.estimate_deadline : ''">
                                </div>

                                <!-- 着工日 -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">着工日</label>
                                    <input type="date" name="start_date"
                                        class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-400"
                                        :value="selectedProject && selectedProject.start_date ? selectedProject.start_date : ''">
                                </div>

                                <!-- 竣工日 -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">竣工日</label>
                                    <input type="date" name="end_date"
                                        class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-400"
                                        :value="selectedProject && selectedProject.end_date ? selectedProject.end_date : ''">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6">
                                <!-- 売上 -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">売上</label>
                                    <input type="number" name="revenue" step="1" min="0"
                                        class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-400 text-right"
                                        :value="selectedProject ? Math.floor(selectedProject.revenue) : ''"
                                        @input="selectedProject ? selectedProject.revenue = Math.floor($event.target.value) || 0 : ''">
                                </div>

                                <!-- 粗利 -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">粗利</label>
                                    <input type="number" name="profit" step="1" min="0"
                                        class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring-2 focus:ring-blue-400 text-right"
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
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    /* 横スクロールバー */
    .hide-scrollbar-x::-webkit-scrollbar {
        height: 8px;
    }

    .hide-scrollbar-x::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 8px;
    }

    .hide-scrollbar-x::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 8px;
    }

    .hide-scrollbar-x::-webkit-scrollbar-thumb:hover {
        background: #aaa;
    }

    /* 縦スクロールバー */
    .hide-scrollbar-y::-webkit-scrollbar {
        width: 6px;
    }

    .hide-scrollbar-y::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 6px;
    }

    .hide-scrollbar-y::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 6px;
    }

    .hide-scrollbar-y::-webkit-scrollbar-thumb:hover {
        background: #aaa;
    }

    html,
    body {
        background-color: #f3f4f6;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // もっと見るボタンのイベント処理
        const showMoreButtons = document.querySelectorAll('.show-more-btn');

        showMoreButtons.forEach(button => {
            button.addEventListener('click', function() {
                const parentContainer = this.closest('.space-y-4');
                const hiddenProjects = parentContainer.querySelector('.hidden-projects');

                if (hiddenProjects.classList.contains('hidden')) {
                    // 隠れたプロジェクトを表示
                    hiddenProjects.classList.remove('hidden');
                    this.querySelector('span').textContent = '折りたたむ';
                    this.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>';
                } else {
                    // プロジェクトを再度隠す
                    hiddenProjects.classList.add('hidden');
                    this.querySelector('span').textContent = 'もっと見る';
                    this.querySelector('svg').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';

                    // ボタンが見えるようにスクロール
                    this.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest'
                    });
                }
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>