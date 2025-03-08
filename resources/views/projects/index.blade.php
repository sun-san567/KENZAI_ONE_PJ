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
                <button @click="activeTab = 'files'"
                    class="px-6 py-3 font-semibold transition border-b-4 border-blue-500 text-blue-600"
                    :class="activeTab === 'files' ? 'border-b-4 border-blue-500 text-blue-600' : 'text-gray-500'"
                    x-show="selectedProject">
                    ファイル管理
                </button>
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
                                class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 shadow-md"
                                x-model="selectedProject ? selectedProject.name : ''">
                        </div>

                        <div class="grid grid-cols-2 gap-8">
                            <!-- フェーズ -->
                            <div>
                                <label class="block font-medium mb-2">フェーズ</label>
                                <select name="phase_id"
                                    class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 shadow-md">
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
                                <label class="block font-medium mb-2">顧客</label>
                                <select name="client_id"
                                    class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 shadow-md">
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

                        <div class="grid grid-cols-2 gap-8">
                            <!-- 売上 -->
                            <div>
                                <label class="block font-medium mb-2">売上</label>
                                <input type="number" name="revenue"
                                    class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 text-right shadow-md"
                                    x-model="selectedProject ? selectedProject.revenue : ''"
                                    @input="this.value = this.value.replace(/\B(?=(\d{3})+(?!\d))/g, ',')">
                            </div>

                            <!-- 粗利 -->
                            <div>
                                <label class="block font-medium mb-2">粗利</label>
                                <input type="number" name="profit"
                                    class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 text-right shadow-md"
                                    x-model="selectedProject ? selectedProject.profit : ''"
                                    @input="this.value = this.value.replace(/\B(?=(\d{3})+(?!\d))/g, ',')">
                            </div>
                        </div>

                        <!-- カテゴリ選択（タグ形式） -->
                        <div>
                            <label class="block font-medium mb-1">カテゴリ</label>
                            <div class="flex flex-wrap gap-6">
                                @foreach ($categories as $category)
                                <label class="px-4 py-3 border rounded-lg cursor-pointer transition duration-200"
                                    :class="selectedProject?.categories?.some(c => c.id == {{ $category->id }}) ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'"
                                    @mouseover="this.classList.add('hover:bg-blue-400')"
                                    @mouseout="this.classList.remove('hover:bg-blue-400')">
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
                    <div class="flex justify-between mt-8 pt-5 gap-8">

                        <button @click="openModal = false" type="button"
                            class="w-1/3 bg-gray-800 text-white px-6 py-3 rounded-full shadow-md transition-transform duration-300 ease-in-out hover:bg-gray-900 hover:-translate-y-1 hover:shadow-lg active:scale-95">
                            閉じる
                        </button>

                        <!-- 更新 or 作成ボタン -->
                        <button type="submit"
                            class="w-1/3 bg-blue-500 text-white px-6 py-3 rounded-full shadow-md transition-transform duration-300 ease-in-out hover:bg-blue-600 hover:-translate-y-1 hover:shadow-lg active:scale-95"
                            x-text="selectedProject ? '更新' : '作成'">
                        </button>
                    </div>
                </form>
            </div>

            <!-- 📌 ファイル管理タブ -->
            <div x-show="activeTab === 'files'" class="mt-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">ファイル管理</h3>
                    <a :href="`/projects/${selectedProject.id}/files`"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        詳細管理へ
                    </a>
                </div>

                <!-- 最近のファイル一覧（シンプルな表示） -->
                <div class="space-y-2">
                    <template x-for="file in recentFiles" :key="file.id">
                        <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                            <span x-text="file.file_name"></span>
                            <span x-text="formatDate(file.created_at)"></span>
                        </div>
                    </template>
                </div>
            </div>

        </div>


        @endsection

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('fileManager', (projectId) => ({
                    projectFiles: [],
                    fileCategory: 'その他',

                    // ✅ ファイル一覧を取得
                    fetchFiles() {
                        if (!projectId) return;
                        console.log("📂 ファイル一覧を取得開始...");

                        fetch(`/api/projects/${projectId}/files`)
                            .then(res => {
                                console.log("📩 APIレスポンス (fetchFiles):", res);
                                if (!res.ok) throw new Error("⚠ ファイル一覧の取得に失敗しました");
                                return res.json();
                            })
                            .then(data => {
                                console.log("✅ 取得したファイル一覧:", data);
                                this.projectFiles = data;
                                console.log("🔄 更新後のファイルリスト:", this.projectFiles);
                            })
                            .catch(error => console.error("❌ エラー:", error));
                    },

                    // ✅ ファイルをアップロード
                    uploadFile(event) {
                        let formData = new FormData();
                        let fileInput = event.target.files[0];
                        let category = document.getElementById('category').value;

                        if (!fileInput) {
                            document.getElementById('uploadStatus').textContent = "ファイルが選択されていません";
                            return;
                        }

                        formData.append('file', fileInput);
                        formData.append('category', category);

                        // ✅ デバッグ出力
                        console.log("送信データ:", formData.get('file'), formData.get('category'));

                        fetch(`/api/projects/${projectId}/files`, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                                }
                            })
                            .then(res => {
                                if (!res.ok) throw new Error("アップロードに失敗しました");
                                return res.json();
                            })
                            .then(data => {
                                console.log("アップロード成功:", data);
                                this.fetchFiles();
                                document.getElementById('uploadStatus').innerText = "✅ アップロード成功！";
                                setTimeout(() => {
                                    document.getElementById('uploadStatus').innerText = "";
                                }, 3000);
                                document.getElementById('uploadForm').reset();
                            })
                            .catch(error => {
                                console.error("アップロードエラー:", error);
                                document.getElementById('uploadStatus').textContent = "アップロード失敗...";
                            })
                    },

                    // ✅ ファイル削除処理
                    deleteFile(fileId) {
                        console.log(`🗑 削除リクエスト: ファイルID ${fileId}`);

                        fetch(`/api/projects/${projectId}/files/${fileId}`, {
                                method: 'DELETE'
                            })
                            .then(res => {
                                console.log("📩 APIレスポンス (deleteFile):", res);
                                if (!res.ok) throw new Error("⚠ 削除に失敗しました");
                                return res.json();
                            })
                            .then(() => {
                                console.log(`✅ ファイルID ${fileId} が削除されました`);
                                this.projectFiles = this.projectFiles.filter(f => f.id !== fileId);
                                console.log("🔄 更新後のファイルリスト:", this.projectFiles);
                            })
                            .catch(error => console.error("❌ 削除エラー:", error));
                    },

                    // ✅ ファイルサイズのフォーマット
                    formatFileSize(size) {
                        const units = ['B', 'KB', 'MB', 'GB', 'TB'];
                        let unitIndex = 0;
                        while (size >= 1024 && unitIndex < units.length - 1) {
                            size /= 1024;
                            unitIndex++;
                        }
                        return `${size.toFixed(2)} ${units[unitIndex]}`;
                    },

                    // ✅ 日付のフォーマット
                    formatDate(date) {
                        return new Date(date).toLocaleDateString();
                    }
                }));
            });
        </script>