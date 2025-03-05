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
    <div class="flex space-x-4 overflow-x-auto pb-4 mt-6">
        @foreach ($phases as $phase)
        <div class="w-1/5 bg-gray-200 p-5 rounded-lg shadow-lg">
            <h2 class="text-lg font-bold">{{ $phase->name }}</h2>

            <div class="mt-4 space-y-3">
                @foreach ($projectsByPhase[$phase->id] ?? [] as $project)
                <!-- 🖊 チケットクリックで編集モーダル表示 -->
                <div class="bg-white p-4 rounded-lg shadow-lg cursor-pointer hover:bg-gray-100 transition transform hover:scale-105"
                    @click="openModal = true; selectedProject = { ...{{ $project->toJson() }}, categories: {{ $project->categories->toJson() }} || [] }; activeTab = 'edit'">
                    <h3 class="font-semibold">{{ $project->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $project->description }}</p>
                    <p class="text-sm font-bold text-blue-600">売上: ¥{{ number_format($project->revenue ?? 0) }}</p>
                    <p class="text-sm font-bold text-green-600">粗利: ¥{{ number_format($project->profit ?? 0) }}</p>

                    <!-- カテゴリ表示（タグ形式） -->
                    <div class="flex flex-wrap mt-2">
                        @foreach ($project->categories as $category)
                        <span class="bg-blue-200 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full mr-2 mb-1">
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

    <!-- 📌 案件編集モーダル -->
    <div x-show="openModal"
        class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 transition-opacity"
        x-transition.opacity
        @click.self="openModal = false"
        x-cloak>


        <div class="bg-white p-6 rounded-2xl shadow-lg w-[600px] max-h-[75vh] overflow-y-auto transform transition-transform my-6" @click.stop>



            <!-- タイトル & 閉じるボタン -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold" x-text="selectedProject ? '案件編集' : '案件追加'"></h2>
                <button @click="openModal = false" class="text-gray-500 hover:text-gray-800 text-3xl">&times;</button>
            </div>

            <!-- タブ切り替え -->
            <div class="flex mb-6 border-b">
                <button @click="activeTab = 'edit'"
                    class="px-6 py-3 font-semibold transition"
                    :class="activeTab === 'edit' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500'">
                    案件編集
                </button>
                <button @click="activeTab = 'files'"
                    class="px-6 py-3 font-semibold transition"
                    :class="activeTab === 'files' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-500'"
                    x-show="selectedProject">
                    ファイル管理
                </button>
            </div>

            <!-- 📌 案件編集タブ -->
            <div x-show="activeTab === 'edit'">
                <form :action="selectedProject ? `/projects/${selectedProject.id}` : '{{ route('projects.store') }}'"
                    method="POST">
                    @csrf
                    <template x-if="selectedProject">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="grid grid-cols-1 gap-6">
                        <!-- 案件情報 -->
                        <div>
                            <label class="block font-medium mb-1">案件名</label>
                            <input type="text" name="name" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400"
                                x-model="selectedProject ? selectedProject.name : ''">
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <!-- フェーズ -->
                            <div>
                                <label class="block font-medium mb-1">フェーズ</label>
                                <select name="phase_id" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400">
                                    @foreach ($phases as $phase)
                                    <option value="{{ $phase->id }}" x-bind:selected="selectedProject && selectedProject.phase_id == {{ $phase->id }}">
                                        {{ $phase->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- 顧客 -->
                            <div>
                                <label class="block font-medium mb-1">顧客</label>
                                <select name="client_id" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400">
                                    @foreach ($clients as $client)
                                    <option value="{{ $client->id }}" x-bind:selected="selectedProject && selectedProject.client_id == {{ $client->id }}">
                                        {{ $client->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- 説明 -->
                        <div>
                            <label class="block font-medium mb-1">説明</label>
                            <textarea name="description" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400"
                                x-model="selectedProject ? selectedProject.description : ''"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <!-- 売上 -->
                            <div>
                                <label class="block font-medium mb-1">売上</label>
                                <input type="number" name="revenue" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400"
                                    x-model="selectedProject ? selectedProject.revenue : ''">
                            </div>

                            <!-- 粗利 -->
                            <div>
                                <label class="block font-medium mb-1">粗利</label>
                                <input type="number" name="profit" class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400"
                                    x-model="selectedProject ? selectedProject.profit : ''">
                            </div>
                        </div>

                        <!-- カテゴリ選択（タグ形式） -->
                        <div>
                            <label class="block font-medium mb-1">カテゴリ</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($categories as $category)
                                <label class="px-4 py-2 border rounded-full cursor-pointer transition duration-200"
                                    :class="selectedProject?.categories?.some(c => c.id == {{ $category->id }}) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'">
                                    <input type="checkbox" name="category_id[]" value="{{ $category->id }}" class="hidden"
                                        :checked="selectedProject?.categories?.some(c => c.id == {{ $category->id }})"
                                        @change="toggleCategory({{ $category->id }})">
                                    {{ $category->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- ボタン配置 -->
                    <div class="flex justify-between mt-8">
                        <button @click="openModal = false" type="button" class="bg-gray-600 text-white px-6 py-3 rounded-lg shadow-lg transition transform hover:bg-gray-700 hover:scale-105">閉じる</button>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-lg transition transform hover:bg-blue-700 hover:scale-105" x-text="selectedProject ? '更新' : '作成'"></button>
                    </div>
                </form>
            </div>

            <!-- 📌 ファイル管理タブ -->
            <div x-show="activeTab === 'files'" x-data="fileManager(selectedProject?.id ?? 0)">
                <label class="block font-semibold mb-4">ファイルアップロード</label>
                <input type="file" @change="uploadFile" class="block w-full text-sm text-gray-500 mb-4">
                <div class="space-y-4">
                    <template x-for="file in projectFiles" :key="file.id">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg shadow-lg">
                            <div class="flex items-center space-x-4">
                                <div class="text-2xl">📄</div>
                                <div>
                                    <p class="font-medium" x-text="file.file_name"></p>
                                    <p class="text-sm text-gray-500" x-text="formatFileSize(file.size)"></p>
                                    <p class="text-sm text-gray-500" x-text="file.created_at"></p>
                                    <p class="text-sm text-gray-500" x-text="file.updated_at"></p>
                                </div>
                            </div>
                            <button @click="deleteFile(file.id)" class="text-red-600 hover:text-red-800">🗑️</button>
                        </div>
                    </template>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('fileManager', (projectId) => ({
            projectFiles: [],
            fileCategory: 'その他',

            fetchFiles() {
                if (!projectId) return;
                fetch(`/api/projects/${projectId}/files`)
                    .then(res => res.json())
                    .then(data => this.projectFiles = data);
            },

            uploadFile(event) {
                let formData = new FormData();
                formData.append('file', event.target.files[0]);

                fetch(`/api/projects/${projectId}/files`, {
                        method: 'POST',
                        body: formData
                    }).then(res => res.json())
                    .then(data => this.projectFiles.push(data))
                    .catch(error => alert('アップロード失敗'));
            },

            deleteFile(fileId) {
                fetch(`/api/projects/${projectId}/files/${fileId}`, {
                        method: 'DELETE'
                    })
                    .then(() => this.projectFiles = this.projectFiles.filter(f => f.id !== fileId));
            },

            formatFileSize(size) {
                const units = ['B', 'KB', 'MB', 'GB', 'TB'];
                let unitIndex = 0;
                while (size >= 1024 && unitIndex < units.length - 1) {
                    size /= 1024;
                    unitIndex++;
                }
                return `${size.toFixed(2)} ${units[unitIndex]}`;
            }
        }));
    });
</script>