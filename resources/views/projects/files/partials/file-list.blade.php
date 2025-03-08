<div class="col-span-8">
    <div class="bg-white rounded-lg shadow p-6">
        <!-- 上部コントロール部分 - 検索とアップロード -->
        <div class="mb-6 flex flex-col md:flex-row justify-between gap-4">
            <!-- 検索エリア -->
            <div class="flex flex-col sm:flex-row gap-4 w-full md:w-2/3">
                <div class="w-full sm:w-2/3">
                    <div class="flex">
                        <div class="relative flex-1">
                            <input type="text"
                                id="searchInput"
                                placeholder="ファイル名で検索..."
                                class="w-full px-4 py-2 pr-10 border border-r-0 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span class="absolute right-3 top-2.5 text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <button type="button" id="searchButton" onclick="window.fileSearchModule.performSearch()"
                            class="px-4 py-2 bg-blue-500 text-white rounded-r-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            検索
                        </button>
                    </div>
                </div>

                <div class="w-full sm:w-1/3">
                    <select id="typeFilter" onchange="window.fileSearchModule.performSearch()" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">すべてのタイプ</option>
                        <option value="document">文書</option>
                        <option value="image">画像</option>
                        <option value="video">動画</option>
                        <option value="audio">音声</option>
                        <option value="archive">圧縮ファイル</option>
                    </select>
                </div>
            </div>

            <!-- アップロードエリアを復元（既存のファイルを利用） -->
            <div class="w-full md:w-1/3 lg:w-1/4 xl:w-1/5">
                @include('projects.files.partials.upload-area')
            </div>
        </div>

        <!-- デバッグ情報表示エリア -->
        <!-- <div id="debugArea" class="mb-4 p-3 bg-gray-100 rounded-lg border border-gray-300 overflow-auto max-h-48 text-xs font-mono">
            <div class="flex justify-between mb-1">
                <h3 class="font-bold">デバッグ情報</h3>
                <button onclick="document.getElementById('debugContent').innerHTML = ''" class="text-xs text-red-500 hover:text-red-700">クリア</button>
            </div>
            <div id="debugContent"></div>
        </div> -->

        <!-- ファイル一覧テーブル -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="w-8 px-4 py-3">
                            <input type="checkbox" id="selectAll"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ファイル名
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            サイズ
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            アップロード日時
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            操作
                        </th>
                    </tr>
                </thead>
                <tbody id="fileListBody" class="bg-white divide-y divide-gray-200">
                    @foreach($files as $file)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <!-- ファイルタイプアイコン -->
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if(Str::contains($file->mime_type, 'pdf'))
                                    <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                                    @elseif(Str::contains($file->mime_type, 'image'))
                                    <i class="fas fa-file-image text-blue-500 text-2xl"></i>
                                    @else
                                    <i class="fas fa-file text-gray-500 text-2xl"></i>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $file->file_name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ Str::title(Str::after($file->mime_type, '/')) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ number_format($file->size / 1024, 2) }} KB
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $file->created_at->format('Y/m/d H:i') }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-3">
                                <!-- プレビューボタン（既存機能を保持） -->
                                @if(isPreviewable($file->mime_type))
                                <button onclick="openPreview({{ $project->id }}, {{ $file->id }}, '{{ $file->file_name }}')"
                                    class="text-gray-600 hover:text-gray-900 flex items-center">
                                    <i class="fas fa-eye mr-1"></i>
                                    <span>プレビュー</span>
                                </button>
                                @endif

                                <!-- ダウンロードボタン -->
                                <a href="{{ route('projects.files.download', [$project->id, $file->id]) }}"
                                    class="text-blue-600 hover:text-blue-900 flex items-center">
                                    <i class="fas fa-download mr-1"></i>
                                    <span>ダウンロード</span>
                                </a>

                                <!-- 削除ボタン -->
                                <form action="{{ route('projects.files.destroy', [$project->id, $file->id]) }}"
                                    method="POST"
                                    onsubmit="return confirm('{{ $file->file_name }} を削除してもよろしいですか？\nこの操作は取り消せません。');"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 flex items-center">
                                        <i class="fas fa-trash-alt mr-1"></i>
                                        <span>削除</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- 検索結果がない場合のメッセージ -->
        <div id="noResultsMessage" class="hidden py-8 text-center text-gray-500">
            <i class="fas fa-search text-3xl mb-2"></i>
            <p>検索条件に一致するファイルが見つかりませんでした。</p>
        </div>

        <!-- ページネーション -->
        <div class="mt-4" id="pagination">
            {{ $files->links() }}
        </div>
    </div>
</div>