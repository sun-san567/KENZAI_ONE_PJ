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

    <!-- コンパクト検索フォーム (改善版) -->
    <div class="bg-white rounded-lg shadow-sm p-5 mb-6" x-data="{ 
        showAdvanced: false,
        clearForm() {
            this.$refs.keywordInput.value = '';
            this.$refs.nameInput.value = '';
            this.$refs.clientInput.value = '';
            this.$refs.estimateDeadlineInput.value = '';
            this.$refs.endDateInput.value = '';
        }
    }">
        <form action="{{ route('projects.index') }}" method="GET">
            <!-- メイン検索欄 -->
            <div class="flex flex-col md:flex-row gap-3 items-end">
                <div class="flex-grow">
                    <label for="search_keyword" class="block text-sm font-medium text-gray-700 mb-1">キーワード検索</label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="text" id="search_keyword" name="search_keyword" x-ref="keywordInput"
                            value="{{ request('search_keyword') }}"
                            placeholder="プロジェクト名・取引先名で検索"
                            class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                </div>

                <div class="flex gap-2 ml-auto">
                    <button type="button" @click="showAdvanced = !showAdvanced"
                        class="px-4 py-2 text-sm border border-gray-300 rounded-md hover:bg-gray-50 transition flex items-center">
                        <span x-text="showAdvanced ? '基本検索' : '詳細検索'"></span>
                        <svg x-show="!showAdvanced" class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        <svg x-show="showAdvanced" class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </button>

                    <button type="button" @click="clearForm()"
                        class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                        クリア
                    </button>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        検索
                    </button>
                </div>
            </div>

            <!-- 詳細検索オプション -->
            <div x-show="showAdvanced"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="mt-5 pt-5 border-t border-gray-200">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <!-- プロジェクト名 -->
                    <div>
                        <label for="search_name" class="block text-sm font-medium text-gray-700 mb-1">プロジェクト名</label>
                        <input type="text" id="search_name" name="search_name" x-ref="nameInput"
                            value="{{ request('search_name') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>

                    <!-- 取引先名 -->
                    <div>
                        <label for="search_client" class="block text-sm font-medium text-gray-700 mb-1">取引先名</label>
                        <input type="text" id="search_client" name="search_client" x-ref="clientInput"
                            value="{{ request('search_client') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>

                    <!-- 見積期限 -->
                    <div>
                        <label for="search_estimate_deadline" class="block text-sm font-medium text-gray-700 mb-1">見積期限</label>
                        <input type="date" id="search_estimate_deadline" name="search_estimate_deadline" x-ref="estimateDeadlineInput"
                            value="{{ request('search_estimate_deadline') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>

                    <!-- 竣工日 -->
                    <div>
                        <label for="search_end_date" class="block text-sm font-medium text-gray-700 mb-1">竣工日</label>
                        <input type="date" id="search_end_date" name="search_end_date" x-ref="endDateInput"
                            value="{{ request('search_end_date') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- 📌 モバイル用: フローティングボタン -->
    <!-- <button @click="openModal = true; selectedProject = { categories: [] }"
        x-show="!openModal"
        x-cloak
        class="fixed md:hidden z-50 shadow-lg transition hover:shadow-xl hover:scale-105
               bottom-6 right-6 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-full">
        +
    </button> -->

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
                        @click="openModal = true; selectedProject = { ...{{ $project->toJson() }}, categories: {{ $project->categories->toJson() }} || [] }; activeTab = 'edit'"
                        @if($project->is_deadline_today)
                        border-2 border-red-500 bg-red-50
                        @endif">
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
                            @if(isset($project->estimate_deadline))
                            <div class="flex items-center">
                                <span class="text-xs text-gray-500 w-16 flex-shrink-0">見積期限：</span>
                                <!-- <span class="text-xs text-gray-500 w-16 flex-shrink-0">粗利：</span> -->
                                <p class="text-sm font-medium text-yellow-700 ml-1">{{ $project->estimate_deadline->format('Y/m/d') }}</p>
                            </div>
                            @endif
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
                            @click="openModal = true; selectedProject = { ...{{ $project->toJson() }}, categories: {{ $project->categories->toJson() }} || [] }; activeTab = 'edit'"
                            @if($project->is_deadline_today)
                            border-2 border-red-500 bg-red-50
                            @endif">
                            <h3 class="font-semibold text-gray-800 truncate">{{ $project->name }}</h3>
                            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $project->description }}</p>

                            <!-- 取引先名 -->
                            <div class="mt-2 pt-2 border-t border-gray-100">
                                <div class="flex items-center">
                                    <span class="text-xs text-gray-500 w-16 flex-shrink-0">取引先：</span>
                                    <p class="text-sm font-medium text-gray-700 ml-1 truncate">{{ $project->client->name ?? '未設定' }}</p>
                                </div>
                            </div>



                            @if(config('app.debug'))
                            <!-- デバッグ情報（開発環境のみ表示） -->
                            <span class="ml-2 text-xs text-red-400">
                                (Raw: {{ $project->getRawOriginal('estimate_deadline') ?? 'null' }})
                            </span>
                            @endif

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
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <script>
                        document.addEventListener('alpine:init', () => {
                            Alpine.store('debug', {
                                logForm(event) {
                                    console.log('Form submission:', {
                                        action: event.target.action,
                                        method: event.target.method,
                                        hasMethodField: event.target.querySelector('input[name="_method"]') !== null,
                                        methodValue: event.target.querySelector('input[name="_method"]')?.value
                                    });
                                }
                            });
                        });
                    </script>

                    <form method="POST"
                        x-data="{ storeUrl: '{{ route('projects.store') }}' }"
                        :action="selectedProject?.id 
            ? '{{ route('projects.update', '') }}' + selectedProject.id 
            : storeUrl"
                        @submit="$store.debug.logForm($event)">

                        @csrf

                        <!-- 編集時に PUT メソッドを適用 -->
                        <template x-if="selectedProject?.id">
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
                                <div class="mb-4" x-data="{
                                    clients: {{ $clients->toJson() }},
                                    keyword: '',
                                    get filteredClients() {
                                        if (!this.keyword.trim()) return this.clients;
                                        const searchTerm = this.keyword.toLowerCase().trim();
                                        return this.clients.filter(client => 
                                            client.name.toLowerCase().includes(searchTerm)
                                        );
                                    }
                                }">
                                    <!-- 絞り込み適用されるセレクトボックス -->
                                    <div x-data="clientSelector()" class="mb-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">顧客</label>

                                        <div class="relative border border-gray-300 rounded-md shadow-sm">
                                            <!-- 検索input -->
                                            <input type="text" x-model="keyword" placeholder="顧客名で検索"
                                                class="w-full px-3 py-2 rounded-t-md focus:ring-2 focus:ring-blue-400 focus:outline-none">

                                            <!-- セレクトボックス -->
                                            <select name="client_id"
                                                class="w-full px-3 py-2 rounded-b-md border-t border-gray-300 focus:ring-2 focus:ring-blue-400">
                                                <template x-for="client in filteredClients" :key="client.id">
                                                    <option :value="client.id" x-text="client.name"
                                                        :selected="selectedProject && selectedProject.client_id == client.id"></option>
                                                </template>
                                            </select>
                                        </div>

                                        <!-- 0件時の案内 -->
                                        <div class="text-sm text-gray-500 mt-1" x-show="keyword.trim() && filteredClients.length === 0">
                                            「<span x-text="keyword"></span>」に一致する顧客はありません
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- 説明 -->
                            <div class="mb-0.5">
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
                                        class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring-2 focus:ring-indigo-500"
                                        :value="selectedProject?.estimate_deadline ? new Date(selectedProject.estimate_deadline).toISOString().split('T')[0] : ''">
                                </div>

                                <!-- 着工日 -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">着工日</label>
                                    <input type="date" name="start_date"
                                        class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring-2 focus:ring-indigo-500"
                                        :value="selectedProject?.start_date ? new Date(selectedProject.start_date).toISOString().split('T')[0] : ''">
                                </div>

                                <!-- 竣工日 -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">竣工日</label>
                                    <input type="date" name="end_date"
                                        class="w-full border-gray-300 rounded-md p-2 shadow-sm focus:ring-2 focus:ring-indigo-500"
                                        :value="selectedProject?.end_date ? new Date(selectedProject.end_date).toISOString().split('T')[0] : ''">
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
                            <!-- モーダル外で Alpine.js の状態を管理 -->
                            <div x-data="categoryModalController()"
                                x-init="initFromSelectedProject()"
                                @project-selected.window="initFromEvent($event.detail)">
                                <label class="block font-medium mb-1">カテゴリ</label>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @foreach ($categories->unique('id') as $category)
                                    <label class="inline-flex items-center px-3.5 py-2 rounded-md border transition-all duration-200 cursor-pointer select-none text-sm"
                                        :class="selectedCategories.includes({{ $category->id }}) 
                                            ? 'bg-blue-100 text-blue-800 border-blue-300 font-medium shadow-sm ring-2 ring-blue-200 ring-opacity-50' 
                                            : 'bg-gray-50 text-gray-600 border-gray-200 hover:bg-gray-100 hover:border-gray-300'">

                                        <!-- 実際のチェックボックス（非表示） -->
                                        <input type="checkbox" name="category_id[]" value="{{ $category->id }}" class="hidden"
                                            :checked="selectedCategories.includes({{ $category->id }})"
                                            @change="toggleCategory({{ $category->id }})">

                                        <!-- チェック状態によるアイコン表示 -->
                                        <svg class="w-4 h-4 mr-1.5"
                                            :class="selectedCategories.includes({{ $category->id }}) ? 'text-blue-600' : 'text-gray-400'"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path :stroke-width="selectedCategories.includes({{ $category->id }}) ? 2 : 1.5"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>

                                        {{ $category->name }}
                                    </label>
                                    @endforeach
                                </div>

                                <!-- 選択されたカテゴリ数の表示 -->
                                <div x-show="selectedCategories.length > 0" class="mt-2 text-sm text-blue-600 font-medium">
                                    <span x-text="selectedCategories.length + ' 個のカテゴリが選択されています'"></span>
                                </div>
                            </div>
                        </div>

                        <!-- 最適化されたボタンレイアウト -->
                        <div class="mt-8 pt-5 border-t border-gray-200">
                            <!-- ファイル管理ナビゲーション - セカンダリーアクション -->
                            <div x-data="{ configAppUrl: '{{ url('') }}' }">
                                <div x-show="selectedProject && selectedProject.id" class="mb-5">
                                    <a :href="`${configAppUrl}/projects/${selectedProject.id}/files`"
                                        class="inline-flex items-center text-blue-600 hover:text-blue-800 py-2.5 px-4 rounded-lg hover:bg-blue-50 transition-colors">
                                        <!-- SVGアイコン略 -->
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
                            </div>


                            <!-- プライマリー操作 - 明確な視覚的階層 -->
                            <div class="flex justify-end items-center gap-3">
                                <button @click="openModal = false" type="button"
                                    class="min-w-[120px] py-3 px-5 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-400 shadow-sm transition-colors text-sm font-medium">
                                    キャンセル
                                </button>

                                <!-- submit ボタンを通常のボタンに変更し、JavaScript で制御 -->
                                <button type="button"
                                    class="min-w-[120px] py-3 px-5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-600 shadow-sm transition-colors text-sm font-medium"
                                    :class="{'opacity-50 cursor-not-allowed': isSubmitting}"
                                    :disabled="isSubmitting"
                                    x-text="selectedProject && selectedProject.id ? '変更を保存' : 'プロジェクト作成'"
                                    @click="(() => {
                                        // フォーム検証
                                        const form = $el.closest('form');
                                        
                                        // 明示的にアクションを設定
                                        if (selectedProject && selectedProject.id) {
                                            form.action = '{{ url('/projects') }}/' + selectedProject.id;
                                            const methodInput = form.querySelector('input[name=_method]');
                                            if (methodInput) methodInput.value = 'PUT';
                                        } else {
                                            form.action = '{{ route('projects.store') }}';
                                        }
                                        
                                        console.log('Submitting to: ' + form.action + ' with method: ' + 
                                                    (form.querySelector('input[name=_method]')?.value || 'POST'));
                                                
                                        // フォーム送信
                                        form.submit();
                                    })()">
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
<script>
    function categoryModalController() {
        return {
            selectedCategories: [],

            // モーダル表示時の初期化（既存の selectedProject からカテゴリを取得）
            initFromSelectedProject() {
                if (typeof selectedProject !== 'undefined' && selectedProject && selectedProject.categories) {
                    this.selectedCategories = selectedProject.categories.map(c => c.id);
                    console.log('初期化されたカテゴリ:', this.selectedCategories);
                }
            },

            // project-selected イベントからの初期化
            initFromEvent(detail) {
                if (detail && detail.categories) {
                    this.selectedCategories = detail.categories.map(c => c.id);
                } else if (typeof selectedProject !== 'undefined' && selectedProject && selectedProject.categories) {
                    this.selectedCategories = selectedProject.categories.map(c => c.id);
                } else {
                    this.selectedCategories = [];
                }
                console.log('イベントからカテゴリを更新:', this.selectedCategories);
            },

            toggleCategory(id) {
                if (this.selectedCategories.includes(id)) {
                    this.selectedCategories = this.selectedCategories.filter(c => c !== id);
                } else {
                    this.selectedCategories.push(id);
                }
                console.log('カテゴリ切り替え後:', this.selectedCategories);
            }
        }
    }
</script>