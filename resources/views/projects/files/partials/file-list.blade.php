<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- 左側：ファイルアップロード (3カラム幅) -->
    <div class="lg:col-span-3 order-2 lg:order-1">
        @include('projects.files.partials.upload-area')

        <!-- アップロード後のファイル一覧 -->
        <div class="mt-6 bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="flex justify-between items-center p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">アップロード済みファイル</h2>
                <span class="text-gray-600 text-base ml-2">{{ $files->total() }} 件のファイル</span>
            </div>

            <!-- テーブルコンテナを最適化して余白のバランスを改善 -->
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-normal w-5/12">
                                        ファイル名
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-normal w-2/12">
                                        種類
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-normal w-1/12">
                                        サイズ
                                    </th>
                                    <th scope="col"
                                        class="px-4 py-3 text-left text-sm font-medium text-gray-600 uppercase tracking-normal w-2/12">
                                        アップロード日
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-sm font-medium text-gray-600 uppercase tracking-normal w-2/12">
                                        操作
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-200" id="fileResults">
                                @forelse ($files as $file)
                                <tr>
                                    <td class="px-6 py-3.5 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <!-- ファイルタイプアイコン -->
                                            <div class="flex-shrink-0 h-8 w-8 flex items-center justify-center">
                                                @if(Str::contains($file->mime_type, 'pdf'))
                                                <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                                @elseif(Str::contains($file->mime_type, 'image'))
                                                <i class="fas fa-file-image text-blue-500 text-xl"></i>
                                                @else
                                                <i class="fas fa-file text-gray-500 text-xl"></i>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-base font-medium text-gray-900 truncate" title="{{ $file->file_name }}">
                                                    {{ Str::limit($file->file_name, 60) }}
                                                </div>
                                                <!-- <div class="text-sm text-gray-500"> -->
                                                <!-- {{ Str::title(Str::after($file->mime_type, '/')) }} -->
                                                <!-- </div> -->
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-3.5 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">

                                            @php
                                            // MIMEタイプに基づくユーザーフレンドリーなファイルタイプ表示
                                            $mimeType = strtolower($file->mime_type);
                                            $extension = strtolower(pathinfo($file->file_name, PATHINFO_EXTENSION));

                                            // ファイルタイプのマッピング（さらに詳細化）
                                            $fileTypeMap = [
                                            // Microsoft Office ファイル
                                            'application/vnd.ms-excel' => ['name' => 'Excel', 'icon' => 'fa-file-excel'],
                                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['name' => 'Excel', 'icon' => 'fa-file-excel'],
                                            'application/vnd.ms-excel.sheet.macroEnabled' => ['name' => 'Excel', 'icon' => 'fa-file-excel'],
                                            'application/msword' => ['name' => 'Word', 'icon' => 'fa-file-word'],
                                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => ['name' => 'Word', 'icon' => 'fa-file-word'],
                                            'application/vnd.ms-powerpoint' => ['name' => 'PowerPoint', 'icon' => 'fa-file-powerpoint'],
                                            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => ['name' => 'PowerPoint', 'icon' => 'fa-file-powerpoint'],

                                            // PDF ファイル
                                            'application/pdf' => ['name' => 'PDF', 'icon' => 'fa-file-pdf'],

                                            // 画像ファイル
                                            'image/jpeg' => ['name' => 'JPEG', 'icon' => 'fa-file-image'],
                                            'image/png' => ['name' => 'PNG', 'icon' => 'fa-file-image'],
                                            'image/gif' => ['name' => 'GIF', 'icon' => 'fa-file-image'],
                                            'image/svg+xml' => ['name' => 'SVG', 'icon' => 'fa-file-image'],
                                            'image/webp' => ['name' => 'WebP', 'icon' => 'fa-file-image'],

                                            // 圧縮ファイル
                                            'application/zip' => ['name' => 'ZIP', 'icon' => 'fa-file-archive'],
                                            'application/x-rar-compressed' => ['name' => 'RAR', 'icon' => 'fa-file-archive'],
                                            'application/x-7z-compressed' => ['name' => '7Z', 'icon' => 'fa-file-archive'],
                                            'application/gzip' => ['name' => 'GZ', 'icon' => 'fa-file-archive'],

                                            // テキストファイル
                                            'text/plain' => ['name' => 'TEXT', 'icon' => 'fa-file-alt'],
                                            'text/csv' => ['name' => 'CSV', 'icon' => 'fa-file-csv'],

                                            // ソースコード関連
                                            'text/html' => ['name' => 'HTML', 'icon' => 'fa-file-code'],
                                            'text/css' => ['name' => 'CSS', 'icon' => 'fa-file-code'],
                                            'application/javascript' => ['name' => 'JS', 'icon' => 'fa-file-code'],
                                            'application/json' => ['name' => 'JSON', 'icon' => 'fa-file-code'],
                                            'text/xml' => ['name' => 'XML', 'icon' => 'fa-file-code'],

                                            // 動画/音声
                                            'video/mp4' => ['name' => 'MP4', 'icon' => 'fa-file-video'],
                                            'video/quicktime' => ['name' => 'MOV', 'icon' => 'fa-file-video'],
                                            'audio/mpeg' => ['name' => 'MP3', 'icon' => 'fa-file-audio'],
                                            'audio/wav' => ['name' => 'WAV', 'icon' => 'fa-file-audio']
                                            ];

                                            // 拡張子ベースのマッピング（MIMEタイプがない場合のバックアップ）
                                            $extensionMap = [
                                            // Microsoft Office
                                            'xlsx' => ['name' => 'Excel', 'icon' => 'fa-file-excel'],
                                            'xls' => ['name' => 'Excel', 'icon' => 'fa-file-excel'],
                                            'xlsm' => ['name' => 'Excel', 'icon' => 'fa-file-excel'],
                                            'doc' => ['name' => 'Word', 'icon' => 'fa-file-word'],
                                            'docx' => ['name' => 'Word', 'icon' => 'fa-file-word'],
                                            'ppt' => ['name' => 'PowerPoint', 'icon' => 'fa-file-powerpoint'],
                                            'pptx' => ['name' => 'PowerPoint', 'icon' => 'fa-file-powerpoint'],

                                            // PDF
                                            'pdf' => ['name' => 'PDF', 'icon' => 'fa-file-pdf'],

                                            // 画像
                                            'jpg' => ['name' => 'JPEG', 'icon' => 'fa-file-image'],
                                            'jpeg' => ['name' => 'JPEG', 'icon' => 'fa-file-image'],
                                            'png' => ['name' => 'PNG', 'icon' => 'fa-file-image'],
                                            'gif' => ['name' => 'GIF', 'icon' => 'fa-file-image'],
                                            'svg' => ['name' => 'SVG', 'icon' => 'fa-file-image'],
                                            'webp' => ['name' => 'WebP', 'icon' => 'fa-file-image'],

                                            // 圧縮
                                            'zip' => ['name' => 'ZIP', 'icon' => 'fa-file-archive'],
                                            'rar' => ['name' => 'RAR', 'icon' => 'fa-file-archive'],
                                            '7z' => ['name' => '7Z', 'icon' => 'fa-file-archive'],
                                            'gz' => ['name' => 'GZ', 'icon' => 'fa-file-archive'],
                                            'tar' => ['name' => 'TAR', 'icon' => 'fa-file-archive'],

                                            // テキスト
                                            'txt' => ['name' => 'TEXT', 'icon' => 'fa-file-alt'],
                                            'csv' => ['name' => 'CSV', 'icon' => 'fa-file-csv'],

                                            // ソースコード
                                            'html' => ['name' => 'HTML', 'icon' => 'fa-file-code'],
                                            'css' => ['name' => 'CSS', 'icon' => 'fa-file-code'],
                                            'js' => ['name' => 'JS', 'icon' => 'fa-file-code'],
                                            'json' => ['name' => 'JSON', 'icon' => 'fa-file-code'],
                                            'xml' => ['name' => 'XML', 'icon' => 'fa-file-code'],

                                            // 動画/音声
                                            'mp4' => ['name' => 'MP4', 'icon' => 'fa-file-video'],
                                            'mov' => ['name' => 'MOV', 'icon' => 'fa-file-video'],
                                            'mp3' => ['name' => 'MP3', 'icon' => 'fa-file-audio'],
                                            'wav' => ['name' => 'WAV', 'icon' => 'fa-file-audio']
                                            ];

                                            // 表示データの初期化
                                            $fileType = ['name' => '', 'icon' => 'fa-file'];

                                            // 表示名の決定（MIMEタイプ → 拡張子 → デフォルト）
                                            if (isset($fileTypeMap[$mimeType])) {
                                            $fileType = $fileTypeMap[$mimeType];
                                            } elseif (isset($extensionMap[$extension])) {
                                            $fileType = $extensionMap[$extension];
                                            } else {
                                            // 汎用的なカテゴリー判定
                                            if (strpos($mimeType, 'excel') !== false || strpos($mimeType, 'spreadsheet') !== false) {
                                            $fileType = ['name' => 'Excel', 'icon' => 'fa-file-excel'];
                                            } elseif (strpos($mimeType, 'word') !== false || strpos($mimeType, 'document') !== false) {
                                            $fileType = ['name' => 'Word', 'icon' => 'fa-file-word'];
                                            } elseif (strpos($mimeType, 'powerpoint') !== false || strpos($mimeType, 'presentation') !== false) {
                                            $fileType = ['name' => 'PowerPoint', 'icon' => 'fa-file-powerpoint'];
                                            } elseif (strpos($mimeType, 'image/') === 0) {
                                            $fileType = ['name' => '画像', 'icon' => 'fa-file-image'];
                                            } elseif (strpos($mimeType, 'video/') === 0) {
                                            $fileType = ['name' => '動画', 'icon' => 'fa-file-video'];
                                            } elseif (strpos($mimeType, 'audio/') === 0) {
                                            $fileType = ['name' => '音声', 'icon' => 'fa-file-audio'];
                                            } elseif (strpos($mimeType, 'text/') === 0) {
                                            $fileType = ['name' => 'テキスト', 'icon' => 'fa-file-alt'];
                                            } else {
                                            // フォールバック: MIMEタイプの「/」以降を取得して先頭大文字化
                                            $fileType = [
                                            'name' => Str::title(Str::after($mimeType, '/')),
                                            'icon' => 'fa-file'
                                            ];
                                            }
                                            }

                                            // ファイルタイプに基づく色とスタイルの設定
                                            $badgeStyle = '';
                                            $bgColor = '';
                                            $textColor = '';
                                            $borderColor = '';

                                            switch ($fileType['name']) {
                                            case 'Excel':
                                            $bgColor = 'bg-green-50';
                                            $textColor = 'text-green-700';
                                            $borderColor = 'border-green-200';
                                            break;
                                            case 'Word':
                                            $bgColor = 'bg-blue-50';
                                            $textColor = 'text-blue-700';
                                            $borderColor = 'border-blue-200';
                                            break;
                                            case 'PowerPoint':
                                            $bgColor = 'bg-orange-50';
                                            $textColor = 'text-orange-700';
                                            $borderColor = 'border-orange-200';
                                            break;
                                            case 'PDF':
                                            $bgColor = 'bg-red-50';
                                            $textColor = 'text-red-700';
                                            $borderColor = 'border-red-200';
                                            break;
                                            case 'JPEG':
                                            case 'PNG':
                                            case 'GIF':
                                            case 'SVG':
                                            case 'WebP':
                                            case '画像':
                                            $bgColor = 'bg-indigo-50';
                                            $textColor = 'text-indigo-700';
                                            $borderColor = 'border-indigo-200';
                                            break;
                                            case 'ZIP':
                                            case 'RAR':
                                            case '7Z':
                                            case 'GZ':
                                            case 'TAR':
                                            $bgColor = 'bg-purple-50';
                                            $textColor = 'text-purple-700';
                                            $borderColor = 'border-purple-200';
                                            break;
                                            case 'TEXT':
                                            case 'CSV':
                                            case 'テキスト':
                                            $bgColor = 'bg-gray-50';
                                            $textColor = 'text-gray-700';
                                            $borderColor = 'border-gray-200';
                                            break;
                                            case 'HTML':
                                            case 'CSS':
                                            case 'JS':
                                            case 'JSON':
                                            case 'XML':
                                            $bgColor = 'bg-cyan-50';
                                            $textColor = 'text-cyan-700';
                                            $borderColor = 'border-cyan-200';
                                            break;
                                            case 'MP4':
                                            case 'MOV':
                                            case '動画':
                                            $bgColor = 'bg-yellow-50';
                                            $textColor = 'text-yellow-700';
                                            $borderColor = 'border-yellow-200';
                                            break;
                                            case 'MP3':
                                            case 'WAV':
                                            case '音声':
                                            $bgColor = 'bg-pink-50';
                                            $textColor = 'text-pink-700';
                                            $borderColor = 'border-pink-200';
                                            break;
                                            default:
                                            $bgColor = 'bg-gray-50';
                                            $textColor = 'text-gray-700';
                                            $borderColor = 'border-gray-200';
                                            }
                                            @endphp

                                            <!-- ファイル種別ラベル -->
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-md text-sm font-medium border {{ $bgColor }} {{ $textColor }} {{ $borderColor }}">
                                                <i class="fas {{ $fileType['icon'] }} mr-1.5"></i>
                                                {{ $fileType['name'] }}
                                            </span>

                                        </div>
                                    </td>

                                    <!-- ファイルサイズ -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if($file->size >= 1048576)
                                            {{ number_format($file->size / 1048576, 2) }} MB
                                            @else
                                            {{ number_format($file->size / 1024, 2) }} KB
                                            @endif
                                        </div>
                                    </td>

                                    <!-- アップロード日時 -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $file->created_at->format('m/d H:i') }}
                                        </div>
                                    </td>
                                    <!-- 操作ボタン -->


                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-4">

                                            <!-- お気に入りボタン -->
                                            <form id="favorite-form-{{ $file->id }}"
                                                action="{{ auth()->user()->favoriteProjectFiles->contains($file->id) 
                                                          ? route('project-files.unfavorite', $file->id) 
                                                          : route('project-files.favorite', $file->id) }}"
                                                method="POST" class="inline-block">
                                                @csrf
                                                @if(auth()->user()->favoriteProjectFiles->contains($file->id))
                                                @method('DELETE')
                                                @endif

                                                <button type="submit"
                                                    class="p-2 rounded-full hover:bg-gray-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50"
                                                    title="{{ auth()->user()->favoriteProjectFiles->contains($file->id) ? 'お気に入り解除' : 'お気に入り追加' }}">

                                                    @if(auth()->user()->favoriteProjectFiles->contains($file->id))
                                                    <x-icons.star-on class="w-5 h-5 text-yellow-400" />
                                                    @else
                                                    <x-icons.star-off class="w-5 h-5 text-gray-300 hover:text-yellow-400" />
                                                    @endif
                                                </button>
                                            </form>

                                            <!-- ダウンロードボタン -->
                                            <a href="{{ route('projects.files.download', [$project->id, $file->id]) }}"
                                                class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md shadow-md hover:shadow-lg transition-all duration-200 leading-none">
                                                <span class="inline-flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4m-8 4h8"></path>
                                                    </svg>
                                                    <span class="hidden sm:inline">ダウンロード</span>
                                                </span>
                                            </a>

                                            <!-- 削除ボタン -->
                                            <form action="{{ route('projects.files.destroy', [$project->id, $file->id]) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ $file->file_name }} を削除してもよろしいですか？\nこの操作は取り消せません。');"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center px-5 py-2.5 border border-gray-300 text-gray-700 bg-white hover:bg-red-50 hover:text-red-500 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500 rounded-md shadow-md hover:shadow-lg transition-all duration-200 leading-none">
                                                    <span class="inline-flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        <span class="hidden sm:inline">削除</span>
                                                    </span>
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
    </div>
</div>