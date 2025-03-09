@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            プロジェクトファイル: {{ $project->name }}
        </h1>

        <a href="{{ route('projects.show', $project->id) }}" class="inline-flex items-center px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i> プロジェクトに戻る
        </a>
    </div>

    <!-- ファイルリスト表示 -->
    @include('projects.files.partials.file-list')
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