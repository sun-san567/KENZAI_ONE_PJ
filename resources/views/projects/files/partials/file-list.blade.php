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
                                        <div class="text-sm font-medium text-gray-900" title="{{ $file->file_name }}">
                                            {{ Str::limit($file->file_name, 70) }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <!-- {{ Str::title(Str::after($file->mime_type, '/')) }} -->
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
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

                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium border {{ $bgColor }} {{ $textColor }} {{ $borderColor }}">
                                        <i class="fas {{ $fileType['icon'] }} mr-1"></i>
                                        {{ $fileType['name'] }}
                                    </span>
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
                                <div class="flex items-center justify-end space-x-4">
                                    <!-- ダウンロードボタン -->
                                    <a href="{{ route('projects.files.download', [$project->id, $file->id]) }}"
                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow transition-colors">
                                        <i class="fas fa-download mr-2"></i>
                                        ダウンロード
                                    </a>

                                    <!-- 削除ボタン -->
                                    <form action="{{ route('projects.files.destroy', [$project->id, $file->id]) }}"
                                        method="POST"
                                        onsubmit="return confirm('{{ $file->file_name }} を削除してもよろしいですか？\nこの操作は取り消せません。');"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md shadow transition-colors">
                                            <i class="fas fa-trash-alt mr-2"></i>
                                            削除
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