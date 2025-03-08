<div class="col-span-8">
    <div class="bg-white rounded-lg shadow p-6">
        <!-- 検索フォーム -->
        <div class="mb-6">
            <div class="flex gap-4">
                <div class="flex-1">
                    <input type="text"
                        id="searchInput"
                        placeholder="ファイル名で検索..."
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <select id="typeFilter" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">すべての種類</option>
                        <option value="pdf">PDF</option>
                        <option value="image">画像</option>
                        <option value="document">文書</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- ファイル一覧テーブル -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
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
                <tbody class="bg-white divide-y divide-gray-200">
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
                                <!-- ダウンロードボタン -->
                                <a href="{{ route('projects.files.download', [$project->id, $file->id]) }}"
                                    class="text-blue-600 hover:text-blue-900 flex items-center"
                                    data-file-id="{{ $file->id }}"
                                    onclick="handleDownload(event, this)">
                                    <i class="fas fa-download mr-1"></i>
                                    <span>ダウンロード</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- ページネーション -->
        <div class="mt-4">
            {{ $files->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script>
    async function handleDownload(event, element) {
        event.preventDefault();
        const fileId = element.dataset.fileId;

        try {
            // ダウンロード開始時のUI更新
            element.classList.add('opacity-50', 'cursor-wait');
            element.querySelector('i').classList.add('fa-spinner', 'fa-spin');

            const response = await fetch(`/api/files/${fileId}/download`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) {
                throw new Error('ダウンロードに失敗しました');
            }

            // Content-Dispositionヘッダーからファイル名を取得
            const contentDisposition = response.headers.get('Content-Disposition');
            const fileName = contentDisposition ?
                decodeURIComponent(contentDisposition.split('filename=')[1].replace(/['"]/g, '')) :
                'download';

            // ファイルのダウンロード
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);

            // 成功通知
            showToast('ダウンロードが完了しました', 'success');

        } catch (error) {
            console.error('Download error:', error);
            showToast(error.message, 'error');
        } finally {
            // UI を元に戻す
            element.classList.remove('opacity-50', 'cursor-wait');
            element.querySelector('i').classList.remove('fa-spinner', 'fa-spin');
        }
    }

    // トースト通知の表示
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg flex items-center space-x-3 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;

        toast.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
        <span>${message}</span>
    `;

        document.body.appendChild(toast);
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>
@endpush