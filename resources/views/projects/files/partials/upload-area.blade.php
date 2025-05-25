<div class="col-span-4">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">ファイルアップロード</h2>

        <form id="uploadForm"
            action="{{ route('projects.files.upload', $project->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="space-y-4">
            @csrf

            <div id="dropZone"
                class="relative border-2 border-dashed border-blue-300 rounded-lg p-8 text-center hover:border-blue-500 hover:bg-blue-50 transition-all cursor-pointer">
                <input type="file"
                    name="files[]"
                    id="fileInput"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                    multiple>

                <div id="uploadPrompt" class="space-y-4 pointer-events-none">
                    <div class="flex justify-center">
                        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-cloud-upload-alt text-3xl text-blue-500"></i>
                        </div>
                    </div>

                    <p class="text-gray-800 font-medium text-lg">クリックしてファイルを選択</p>
                    <p class="text-gray-600">または、ドラッグ＆ドロップ</p>

                    <div class="border-t border-gray-200 pt-3 mt-3">
                        <p class="text-xs text-gray-500">サポートファイル:</p>
                        <div class="flex flex-wrap justify-center gap-2 mt-1">
                            <span class="px-2 py-1 bg-gray-100 rounded-md text-xs">PDF</span>
                            <span class="px-2 py-1 bg-gray-100 rounded-md text-xs">Word</span>
                            <span class="px-2 py-1 bg-gray-100 rounded-md text-xs">Excel</span>
                            <span class="px-2 py-1 bg-gray-100 rounded-md text-xs">画像</span>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">最大ファイルサイズ: 合計100MB</p>
                    </div>
                </div>

                <div id="selectedFiles" class="hidden space-y-3">
                    <div class="flex justify-between items-center">
                        <h3 class="font-medium">選択したファイル (<span id="fileCount">0</span>)</h3>
                        <button type="button"
                            id="addMoreFiles"
                            class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-plus mr-1"></i>ファイルを追加
                        </button>
                    </div>
                    <div id="fileList" class="space-y-2 text-left"></div>
                    <div id="totalSize" class="text-right text-sm text-gray-600"></div>
                </div>
            </div>
            <div class="text-center mt-4">
                <button type="submit"
                    id="uploadBtn"
                    class="px-6 py-3 bg-blue-500 text-white rounded-md shadow-md hover:shadow-lg hover:bg-blue-700 transition-all duration-300 ease-in-out flex items-center mx-auto">
                    <i class="fas fa-upload text-lg mr-1.5"></i>
                    アップロード
                </button>
            </div>








            <div id="uploadStatus" class="hidden text-center py-2"></div>

            <div id="uploadProgress" class="hidden w-full bg-gray-200 rounded-full h-3 mt-2">
                <div id="progressBar" class="bg-blue-600 h-3 rounded-full" style="width: 0%"></div>
            </div>

            <div id="statusArea" class="hidden"></div>
        </form>

        @if (session('success'))
        <div class="mt-4 p-4 bg-green-50 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mt-4 p-4 bg-red-50 text-red-700 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="mt-6 pt-4 border-t border-gray-200">
            <form id="fileSearchForm"
                class="max-w-2xl mx-auto flex gap-4 items-center"
                x-data="{
          clearForm() {
              document.getElementById('searchInput').value = '';
              document.getElementById('fileTypeSelect').value = '';
              $nextTick(() => { document.getElementById('fileSearchForm').submit(); });
          }
      }">

                <!-- 検索ボックス -->
                <div class="relative flex-grow">
                    <i class="fas fa-search absolute left-3 top-3.5 text-gray-600"></i>
                    <input
                        type="text"
                        id="searchInput"
                        name="search"
                        class="w-full border border-gray-400 bg-gray-50 rounded-md pl-10 p-3 text-base shadow-sm focus:ring-2 focus:ring-blue-500"
                        placeholder="検索キーワードを入力（例: PDF）">
                </div>

                <!-- ファイル種別フィルター -->
                <!-- カスタムスタイル適用したドロップダウン -->
                <div class="relative">
                    <select
                        id="fileTypeSelect"
                        name="type"
                        class="border border-gray-400 bg-gray-50 rounded-md p-3.5 text-lg shadow-sm max-w-[220px] appearance-none pr-10">
                        <option value="">📂 すべて</option>
                        <option value="favorite">⭐ お気に入り</option>
                        <option value="pdf">📄 PDF</option>
                        <option value="doc">📝 Word</option>
                        <option value="xls">📊 Excel</option>
                        <option value="img">🖼️ 画像</option>
                    </select>

                    <!-- カスタム矢印アイコン -->
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                </div>

                <div class="flex flex-wrap gap-3">

                    <!-- 検索ボタン -->
                    <button type="submit"
                        class="w-[220px] px-4 py-3 pr-4 bg-blue-500 text-white text-lg font-semibold rounded-md shadow-sm hover:bg-blue-600 hover:shadow-lg flex items-center justify-center gap-2 transition-all duration-300">
                        <i class="fas fa-search text-[16px]"></i>
                        <span>検索</span>
                    </button>

                    <!-- クリアボタン -->
                    <button type="button"
                        @click="clearForm()"
                        class="w-[220px] px-4 py-3 pr-6 bg-gray-300 text-gray-700 text-lg font-semibold rounded-md shadow-sm hover:bg-gray-400 hover:shadow-lg flex items-center justify-center gap-2 transition-all duration-300">
                        <i class="fas fa-undo text-[16px]"></i>
                        <span>クリア</span>
                    </button>
                </div>

            </form>

            <!-- 検索結果表示 -->
            <div id="searchStatus" class="mt-2 text-xs text-gray-500 text-center">
                <div id="searchSpinner" class="hidden flex items-center">
                    <i class="fas fa-spinner fa-spin mr-1 text-blue-500"></i>検索中...
                </div>
                <div id="searchResultInfo" class="hidden">
                    <span id="resultCount">0</span> 件のファイルが見つかりました
                </div>
            </div>
        </div>



    </div>
</div>

<!-- upload-area.blade.phpの末尾 -->
<script>
    console.log("インラインスクリプトテスト - ファイルアップロードエリア");
</script>

<!-- 絶対URLパスで指定 -->
<script src="{{ url('/js/file-upload.js') }}?v={{ time() }}"></script>
<script src="{{ url('/js/file-search.js') }}?v={{ time() }}"></script>

<!-- デバッグ追加 -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log("DOM読み込み完了");
        console.log("script要素確認(修正後):", document.querySelector('script[src*="file-upload.js"]'));
        console.log("FileUploader存在確認(修正後):", typeof window.FileUploader);
    });
</script>