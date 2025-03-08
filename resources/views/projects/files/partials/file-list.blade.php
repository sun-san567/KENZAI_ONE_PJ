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

        <!-- ファイル一覧 -->
        <div id="fileList" class="space-y-4">
            @foreach($files as $file)
            <div class="file-item flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100"
                data-name="{{ $file->file_name }}"
                data-type="{{ $file->mime_type }}">
                <div class="flex items-center space-x-4">
                    <!-- ファイルタイプアイコン -->
                    <div class="text-2xl">
                        @if(str_contains($file->mime_type, 'pdf'))
                        <i class="fas fa-file-pdf text-red-500"></i>
                        @elseif(str_contains($file->mime_type, 'image'))
                        <i class="fas fa-file-image text-blue-500"></i>
                        @else
                        <i class="fas fa-file text-gray-500"></i>
                        @endif
                    </div>

                    <!-- ファイル情報 -->
                    <div>
                        <p class="font-medium">{{ $file->file_name }}</p>
                        <p class="text-sm text-gray-500">
                            {{ number_format($file->size / 1024, 2) }} KB
                            • {{ $file->created_at->format('Y/m/d H:i') }}
                        </p>
                    </div>
                </div>

                <!-- アクション -->
                <div class="flex space-x-2">
                    <a href="{{ route('projects.files.download', [$project->id, $file->id]) }}"
                        class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const typeFilter = document.getElementById('typeFilter');
        const fileItems = document.querySelectorAll('.file-item');

        function filterFiles() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedType = typeFilter.value;

            fileItems.forEach(item => {
                const fileName = item.dataset.name.toLowerCase();
                const fileType = item.dataset.type;

                const matchesSearch = fileName.includes(searchTerm);
                const matchesType = !selectedType || fileType.includes(selectedType);

                item.style.display = matchesSearch && matchesType ? 'flex' : 'none';
            });
        }

        searchInput.addEventListener('input', filterFiles);
        typeFilter.addEventListener('change', filterFiles);
    });
</script>
@endpush