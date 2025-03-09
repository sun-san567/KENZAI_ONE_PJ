<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- 左側：ファイルアップロード (3カラム幅) -->
    <div class="lg:col-span-3 order-2 lg:order-1">
        @include('projects.files.partials.upload-area')

        <!-- アップロード後のファイル一覧 -->
        <div class="mt-6 bg-white rounded-lg shadow overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b">
                <h2 class="text-xl font-semibold">アップロード済みファイル</h2>
                <span class="text-gray-500 text-sm">{{ $files->total() }}件のファイル</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ファイル名
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                種類
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                サイズ
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                アップロード日
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                操作
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="fileResults">
                        @forelse ($files as $file)
                        <tr>
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
                                            <!-- {{ Str::title(Str::after($file->mime_type, '/')) }} -->
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    {{ Str::title(Str::after($file->mime_type, '/')) }}
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
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                ファイルがまだアップロードされていません
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ページネーション -->
            <div class="px-6 py-3 border-t">
                {{ $files->links() }}
            </div>
        </div>
    </div>
</div>