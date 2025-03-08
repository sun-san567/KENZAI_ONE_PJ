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
                class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer">
                <input type="file"
                    name="files[]"
                    id="fileInput"
                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                    multiple>

                <div id="uploadPrompt" class="space-y-2 pointer-events-none">
                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                    <p class="text-gray-600">クリックしてファイルを選択</p>
                    <p class="text-sm text-gray-500">または、ドラッグ＆ドロップ</p>
                    <p class="text-xs text-gray-400 mt-2">最大ファイルサイズ: 合計100MB</p>
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

            <button type="submit"
                id="uploadBtn"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                アップロード
            </button>

            <div id="statusArea" class="hidden mt-3 p-3 rounded-lg text-center"></div>
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
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('uploadForm');
        const fileInput = document.getElementById('fileInput');
        const dropZone = document.getElementById('dropZone');
        const uploadPrompt = document.getElementById('uploadPrompt');
        const selectedFiles = document.getElementById('selectedFiles');
        const fileList = document.getElementById('fileList');
        const fileCount = document.getElementById('fileCount');
        const totalSize = document.getElementById('totalSize');
        const addMoreFiles = document.getElementById('addMoreFiles');
        const statusArea = document.getElementById('statusArea');

        let currentFiles = new Set();

        fileInput.addEventListener('change', handleFileSelection);

        addMoreFiles.addEventListener('click', () => {
            fileInput.click();
        });

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
            handleFileSelection({
                target: {
                    files: e.dataTransfer.files
                }
            });
        });

        function handleFileSelection(e) {
            const files = Array.from(e.target.files || []);
            if (files.length === 0) return;

            uploadPrompt.classList.add('hidden');
            selectedFiles.classList.remove('hidden');

            files.forEach(file => {
                if (!currentFiles.has(file.name)) {
                    currentFiles.add(file.name);
                    addFileToList(file);
                }
            });

            updateFileCount();
            updateTotalSize();
        }

        function addFileToList(file) {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between bg-white p-3 rounded-lg border';

            const fileIcon = getFileIcon(file.type);

            fileItem.innerHTML = `
                <div class="flex items-center space-x-3">
                    <i class="fas ${fileIcon.icon} ${fileIcon.color}"></i>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                        <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
                    </div>
                </div>
                <button type="button" 
                        class="text-gray-400 hover:text-red-500"
                        onclick="removeFile('${file.name}')">
                    <i class="fas fa-times"></i>
                </button>
            `;

            fileList.appendChild(fileItem);
        }

        window.removeFile = function(fileName) {
            currentFiles.delete(fileName);
            const items = fileList.children;
            for (let item of items) {
                if (item.querySelector('p').textContent === fileName) {
                    item.remove();
                    break;
                }
            }

            updateFileCount();
            updateTotalSize();

            if (currentFiles.size === 0) {
                selectedFiles.classList.add('hidden');
                uploadPrompt.classList.remove('hidden');
                fileInput.value = '';
            }
        };

        function updateFileCount() {
            fileCount.textContent = currentFiles.size;
        }

        function updateTotalSize() {
            let total = 0;
            const files = fileInput.files;
            Array.from(files).forEach(file => {
                if (currentFiles.has(file.name)) {
                    total += file.size;
                }
            });
            totalSize.textContent = `合計: ${formatFileSize(total)}`;
        }

        function getFileIcon(mimeType) {
            let icon = 'fa-file';
            let color = 'text-blue-500';

            if (mimeType.includes('pdf')) {
                icon = 'fa-file-pdf';
                color = 'text-red-500';
            } else if (mimeType.includes('image')) {
                icon = 'fa-file-image';
                color = 'text-green-500';
            } else if (mimeType.includes('word')) {
                icon = 'fa-file-word';
                color = 'text-blue-700';
            } else if (mimeType.includes('excel')) {
                icon = 'fa-file-excel';
                color = 'text-green-700';
            }

            return {
                icon,
                color
            };
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (currentFiles.size === 0) {
                showStatus('ファイルを選択してください', 'error');
                return;
            }

            const formData = new FormData(this);

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    showStatus(result.message, 'success');
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 1500);
                } else {
                    throw new Error(result.message || 'アップロードに失敗しました');
                }
            } catch (error) {
                showStatus(error.message, 'error');
            }
        });

        function showStatus(message, type) {
            statusArea.textContent = message;
            statusArea.className = 'mt-3 p-3 rounded-lg text-center';

            if (type === 'success') {
                statusArea.classList.add('bg-green-50', 'text-green-700');
            } else if (type === 'error') {
                statusArea.classList.add('bg-red-50', 'text-red-700');
            }

            statusArea.classList.remove('hidden');
        }
    });
</script>
@endpush