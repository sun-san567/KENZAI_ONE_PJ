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
                    class="px-6 py-3 bg-blue-600 text-white rounded-md shadow-md hover:shadow-lg hover:bg-blue-700 transition-all duration-300 ease-in-out flex items-center mx-auto">
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
            <form id="fileSearchForm" class="grid grid-cols-12 gap-2">
                <div class="col-span-7">
                    <input
                        type="text"
                        id="searchInput"
                        name="search"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                        placeholder="ファイル名を検索...">
                </div>

                <div class="col-span-3">
                    <select
                        id="fileTypeSelect"
                        name="type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="">すべて</option>
                        <option value="pdf">PDF</option>
                        <option value="doc">Word</option>
                        <option value="xls">Excel</option>
                        <option value="img">画像</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <button
                        type="submit"
                        class="w-full py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center">
                        <i class="fas fa-search">検索する</i>
                    </button>
                </div>
            </form>

            <div id="searchStatus" class="mt-1 text-xs text-gray-500 flex items-center">
                <div id="searchSpinner" class="hidden">
                    <i class="fas fa-spinner fa-spin mr-1 text-blue-500"></i>検索中...
                </div>
                <div id="searchResultInfo" class="hidden">
                    <span id="resultCount">0</span>件のファイルが見つかりました
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