@forelse($files as $file)
<tr class="hover:bg-gray-50 file-row" data-file-id="{{ $file->id }}">
    <td class="px-4 py-4">
        <input type="checkbox" 
            class="file-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500"
            data-file-id="{{ $file->id }}">
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center">
            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center">
                <i class="{{ getFileIconClass($file->mime_type) }} text-gray-500 text-xl"></i>
            </div>
            <div class="ml-4">
                <div class="text-sm font-medium text-gray-900">
                    {{ $file->file_name }}
                </div>
                <div class="text-sm text-gray-500">
                    {{ $file->mime_type }}
                </div>
            </div>
        </div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900">{{ formatFileSize($file->file_size) }}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="text-sm text-gray-900">{{ $file->created_at->format('Y年m月d日') }}</div>
        <div class="text-sm text-gray-500">{{ $file->created_at->format('H:i') }}</div>
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
        <div class="flex space-x-3">
            @if(isPreviewable($file->mime_type))
            <button onclick="openPreview({{ $project->id }}, {{ $file->id }}, '{{ $file->file_name }}')" 
                    class="text-gray-600 hover:text-gray-900 flex items-center">
                <i class="fas fa-eye mr-1"></i>
                <span>プレビュー</span>
            </button>
            @endif

            <a href="{{ route('projects.files.download', [$project->id, $file->id]) }}"
               class="text-blue-600 hover:text-blue-900 flex items-center">
                <i class="fas fa-download mr-1"></i>
                <span>ダウンロード</span>
            </a>

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
        検索条件に一致するファイルがありません
    </td>
</tr>
@endforelse