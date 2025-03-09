@forelse($files as $file)
<tr class="border-b border-gray-100 hover:bg-blue-50 transition-colors file-row" data-file-id="{{ $file->id }}">
    <td class="pl-4 py-4 w-12">
        <input type="checkbox"
            class="file-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            data-file-id="{{ $file->id }}">
    </td>
    <td class="px-4 py-4">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-md bg-{{ getFileColorClass($file->mime_type) }}-100">
                <i class="{{ getFileIconClass($file->mime_type) }} text-{{ getFileColorClass($file->mime_type) }}-600 text-lg"></i>
            </div>
            <div class="ml-3 min-w-0">
                <div class="text-sm font-medium text-gray-900 truncate max-w-xs" title="{{ $file->file_name }}">
                    {{ $file->file_name }}
                </div>
                <div class="text-xs text-gray-500 mt-0.5">
                    {{ getFriendlyFileType($file->mime_type) }}
                </div>
            </div>
        </div>
    </td>
    <td class="px-4 py-4 whitespace-nowrap">
        <span class="px-2.5 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">
            {{ formatFileSize($file->file_size) }}
        </span>
    </td>
    <td class="px-4 py-4">
        <div class="text-sm text-gray-700">{{ $file->created_at->format('Y年m月d日') }}</div>
        <div class="text-xs text-gray-500">{{ $file->created_at->format('H:i') }}</div>
    </td>
    <td class="pl-4 pr-6 py-4 text-right">
        <div class="flex items-center justify-end space-x-3">
            @if(isPreviewable($file->mime_type))
            <button onclick="openPreview({{ $project->id }}, {{ $file->id }}, '{{ $file->file_name }}')"
                class="flex items-center justify-center h-9 w-9 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors shadow-sm"
                title="プレビュー">
                <i class="fas fa-eye"></i>
            </button>
            @endif

            <a href="{{ route('projects.files.download', [$project->id, $file->id]) }}"
                class="flex items-center justify-center h-9 w-9 bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition-colors shadow-sm"
                title="ダウンロード">
                <i class="fas fa-download"></i>
            </a>

            <div class="relative" x-data="{ confirmOpen: false }">
                <button
                    @click="confirmOpen = true"
                    class="flex items-center justify-center h-9 w-9 bg-red-100 text-red-600 rounded-full hover:bg-red-200 transition-colors shadow-sm"
                    title="削除">
                    <i class="fas fa-trash-alt"></i>
                </button>

                <div
                    x-show="confirmOpen"
                    @click.away="confirmOpen = false"
                    class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 p-3 z-10"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95">
                    <p class="text-sm text-gray-700 mb-2">「{{ $file->file_name }}」を削除しますか？</p>
                    <div class="flex justify-end space-x-2">
                        <button
                            @click="confirmOpen = false"
                            class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs rounded hover:bg-gray-200">
                            キャンセル
                        </button>
                        <form action="{{ route('projects.files.destroy', [$project->id, $file->id]) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                削除する
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="px-6 py-8 text-center text-gray-500 border-b border-gray-100">
        <div class="flex flex-col items-center justify-center">
            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
            </svg>
            <p>検索条件に一致するファイルがありません</p>
        </div>
    </td>
</tr>
@endforelse