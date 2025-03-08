@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <!-- ヘッダー -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold">{{ $project->name }}</h1>
            <p class="text-gray-600">ファイル管理</p>
        </div>
        <a href="{{ route('projects.index') }}" class="text-gray-600 hover:text-gray-800">
            ← 案件一覧に戻る
        </a>
    </div>

    <!-- メインコンテンツ -->
    <div class="grid grid-cols-12 gap-6">
        @include('projects.files.partials.upload-area')
        @include('projects.files.partials.file-list')
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.querySelector('.border-dashed');
        const fileInput = document.querySelector('input[type="file"]');
        const uploadForm = document.querySelector('form');

        // ドラッグ&ドロップ処理
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

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                handleFileUpload(files[0]);
            }
        });

        // ファイル選択時の処理
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFileUpload(e.target.files[0]);
            }
        });

        function handleFileUpload(file) {
            const formData = new FormData();
            formData.append('file', file);

            // プログレスバーの表示
            const progressBar = document.createElement('div');
            progressBar.className = 'h-2 bg-blue-200 rounded-full mt-4';
            progressBar.innerHTML = '<div class="h-2 bg-blue-600 rounded-full" style="width: 0%"></div>';
            dropZone.appendChild(progressBar);

            fetch('{{ route("projects.files.upload", $project->id) }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // ファイル一覧の更新
                    location.reload();
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    alert('アップロードに失敗しました');
                });
        }
    });
</script>
@endpush

@endsection