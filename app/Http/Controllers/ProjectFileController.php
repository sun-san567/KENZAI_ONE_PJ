<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProjectFileController extends Controller
{
    // ミドルウェアはルートで設定するため、コンストラクタは不要
    private $allowedMimeTypes = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    /**
     * ファイル一覧と検索機能
     */
    public function index(Request $request, Project $project)
    {
        // 詳細なエラーログ出力を有効化
        try {
            // 検索パラメータの取得
            $search = $request->input('search');
            $fileType = $request->input('type');

            // デバッグログ
            \Log::info('プロジェクトファイル検索', [
                'project_id' => $project->id,
                'search' => $search,
                'type' => $fileType,
                'is_ajax' => $request->ajax() || $request->has('ajax')
            ]);

            // クエリビルダーの初期化
            $query = ProjectFile::where('project_id', $project->id);

            // 検索条件の適用
            if ($search) {
                $query->where('file_name', 'like', '%' . $search . '%');
                // 必要に応じてdescriptionなど他のフィールドも検索
            }

            // ファイルタイプでフィルタリング
            if ($fileType) {
                switch ($fileType) {
                    case 'favorite':
                        $favoriteIds = auth()->user()->favoriteProjectFiles->pluck('id');
                        $query->whereIn('id', $favoriteIds);
                        break;

                    case 'pdf':
                        $query->where('file_extension', 'pdf');
                        break;

                    case 'doc':
                        $query->whereIn('file_extension', ['doc', 'docx']);
                        break;

                    case 'xls':
                        $query->whereIn('file_extension', ['xls', 'xlsx']);
                        break;

                    case 'img':
                        $query->whereIn('file_extension', ['jpg', 'jpeg', 'png', 'gif']);
                        break;
                }
            }

            // ファイルを取得
            $files = $query->orderBy('file_extension', 'asc')
                ->paginate(10)
                ->withQueryString();

            // AJAXリクエストの場合
            if ($request->ajax() || $request->has('ajax')) {
                // HTMLを直接生成（パーシャルビューの問題を回避）
                $html = '';

                if ($files->count() > 0) {
                    foreach ($files as $file) {
                        // 簡易的なHTMLを生成
                        $html .= '<tr class="hover:bg-gray-50">';
                        $html .= '<td class="px-4 py-4"><input type="checkbox" class="file-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" data-file-id="' . $file->id . '"></td>';
                        $html .= '<td class="px-6 py-4 whitespace-nowrap">';
                        $html .= '<div class="flex items-center">';
                        $html .= '<div class="flex-shrink-0 h-10 w-10 flex items-center justify-center">';
                        $html .= '<i class="fas fa-file text-gray-500 text-xl"></i>';
                        $html .= '</div>';
                        $html .= '<div class="ml-4">';
                        $html .= '<div class="text-sm font-medium text-gray-900">' . $file->file_name . '</div>';
                        $html .= '<div class="text-sm text-gray-500">' . $file->mime_type . '</div>';
                        $html .= '</div></div></td>';
                        $html .= '<td class="px-6 py-4 whitespace-nowrap">';
                        $html .= '<div class="text-sm text-gray-900">' . ($file->file_size ? round($file->file_size / 1024, 2) . ' KB' : 'N/A') . '</div>';
                        $html .= '</td>';
                        $html .= '<td class="px-6 py-4 whitespace-nowrap">';
                        $html .= '<div class="text-sm text-gray-900">' . $file->created_at->format('Y/m/d H:i') . '</div>';
                        $html .= '</td>';
                        $html .= '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">';
                        $html .= '<div class="flex space-x-4">';
                        $html .= '<a href="' . route('projects.files.download', [$project->id, $file->id]) . '" class="text-blue-600 hover:text-blue-900 flex items-center">';
                        $html .= '<i class="fas fa-download mr-1"></i><span>ダウンロード</span></a>';

                        // 削除フォーム
                        $html .= '<form method="POST" action="' . route('projects.files.destroy', [$project->id, $file->id]) . '" class="inline">';
                        $html .= csrf_field();
                        $html .= method_field('DELETE');
                        $html .= '<button type="submit" class="text-red-600 hover:text-red-900 flex items-center">';
                        $html .= '<i class="fas fa-trash-alt mr-1"></i><span>削除</span></button>';
                        $html .= '</form>';

                        $html .= '</div></td></tr>';
                    }
                } else {
                    $html = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">検索条件に一致するファイルがありません</td></tr>';
                }

                return response()->json([
                    'html' => $html,
                    'hasResults' => $files->count() > 0
                ]);
            }

            // 通常リクエストの場合
            return view('projects.files.index', compact('project', 'files'));
        } catch (\Exception $e) {
            // 詳細なエラーログ
            \Log::error('プロジェクトファイル検索エラー', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // AJAXリクエストの場合はJSONエラーを返す
            if ($request->ajax() || $request->has('ajax')) {
                return response()->json([
                    'error' => '検索処理中にエラーが発生しました: ' . $e->getMessage()
                ], 500);
            }

            // 通常リクエストの場合はエラー表示
            return back()->with('error', '検索処理中にエラーが発生しました');
        }
    }

    public function upload(Request $request, Project $project)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif|max:10240',
        ], [
            'files.required' => 'ファイルを選択してください。',
            'files.*.file' => 'アップロードされたファイルが無効です。',
            'files.*.mimes' => 'アップロードできるファイル形式は PDF, Word, Excel, PowerPoint, 画像ファイルのみです。',
            'files.*.max' => 'ファイルサイズは10MB以下にしてください。',
        ]);

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $originalName = $file->getClientOriginalName();
            $fileName = uniqid() . '_' . $originalName;

            // ファイルの保存
            $filePath = $file->storeAs('project-files/' . $project->id, $fileName);

            // ファイル情報の保存 - 実際のデータベースカラムに合わせる
            $projectFile = new ProjectFile([
                'project_id' => $project->id,
                'file_name' => $originalName,
                'file_path' => $filePath,
                'mime_type' => $file->getMimeType(),
                'file_extension' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'uploaded_by' => auth()->id() // 現在のユーザーID
            ]);

            $projectFile->save();
            $uploadedFiles[] = $originalName;
        }

        $message = count($uploadedFiles) > 1
            ? count($uploadedFiles) . 'ファイルがアップロードされました'
            : $uploadedFiles[0] . 'がアップロードされました';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('projects.files.index', $project->id)
            ]);
        }

        return redirect()->route('projects.files.index', $project->id)
            ->with('success', $message);
    }

    public function download(Project $project, ProjectFile $file)
    {
        if (!Storage::exists($file->file_path)) {
            abort(404);
        }

        return Storage::download($file->file_path, $file->file_name);
    }

    /**
     * 削除機能の修正 - リダイレクト応答を返すように変更
     */
    public function destroy(Project $project, $fileId)
    {
        // 権限チェックは一旦無視
        // $this->authorize('update', $project);

        $file = ProjectFile::findOrFail($fileId);

        // プロジェクトとファイルの関連性を確認
        if ($file->project_id !== $project->id) {
            return redirect()->back()->with('error', 'このファイルはプロジェクトに属していません。');
        }

        try {
            // ストレージからファイルを削除
            if ($file->file_path) {
                Storage::delete('public/files/' . $file->file_path);
            }

            // データベースからレコードを削除
            $file->delete();

            // JSON応答ではなくリダイレクトを返す
            return redirect()->back()->with('success', 'ファイルが削除されました。');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ファイルの削除中にエラーが発生しました: ' . $e->getMessage());
        }
    }

    /**
     * 複数のファイルを一括削除
     */
    public function bulkDelete(Request $request, Project $project)
    {
        // $this->authorize('update', $project); // この行をコメントアウトまたは削除

        $request->validate([
            'file_ids' => 'required|array',
            'file_ids.*' => 'integer|exists:files,id'
        ]);

        $fileIds = $request->input('file_ids');
        $deletedCount = 0;

        // トランザクション開始
        DB::beginTransaction();

        try {
            // プロジェクトに属するファイルのみを取得
            $files = ProjectFile::where('project_id', $project->id)
                ->whereIn('id', $fileIds)
                ->get();

            foreach ($files as $file) {
                // ストレージからファイルを削除
                $filePath = storage_path('app/public/files/' . $file->file_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                // データベースからレコードを削除
                $file->delete();
                $deletedCount++;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $deletedCount . '件のファイルが削除されました。',
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'ファイルの削除中にエラーが発生しました。'], 500);
        }
    }

    /**
     * ファイルのプレビュー情報を取得
     */
    public function preview(Project $project, $fileId)
    {
        $this->authorize('view', $project);

        $file = ProjectFile::findOrFail($fileId);

        if ($file->project_id !== $project->id) {
            abort(403, 'このファイルにアクセスする権限がありません。');
        }

        // ファイルの情報を取得
        $previewData = [
            'id' => $file->id,
            'file_name' => $file->file_name,
            'mime_type' => $file->mime_type,
            'preview_url' => route('projects.files.preview-content', [$project->id, $file->id])
        ];

        return response()->json($previewData);
    }

    /**
     * ファイルのプレビューコンテンツを取得
     */
    public function previewContent(Project $project, $fileId)
    {
        $this->authorize('view', $project);

        $file = ProjectFile::findOrFail($fileId);

        if ($file->project_id !== $project->id) {
            abort(403, 'このファイルにアクセスする権限がありません。');
        }

        $path = storage_path('app/public/files/' . $file->file_path);

        // ファイルが存在するか確認
        if (!file_exists($path)) {
            return response()->json(['error' => 'ファイルが見つかりません'], 404);
        }

        // ファイルタイプに応じたレスポンスを返す
        $mimeType = $file->mime_type;

        // 画像ファイル
        if (Str::startsWith($mimeType, 'image/')) {
            return response()->file($path);
        }

        // PDFファイル
        if ($mimeType === 'application/pdf') {
            return response()->file($path);
        }

        // テキストファイル
        if (Str::startsWith($mimeType, 'text/')) {
            $content = file_get_contents($path);
            return response($content, 200, [
                'Content-Type' => 'text/plain; charset=utf-8'
            ]);
        }

        // 対応していないファイル形式
        return response()->json(['error' => 'このファイル形式はプレビューに対応していません'], 400);
    }

    // お気に入りファイル処理
    public function favorite(ProjectFile $projectFile)
    {
        auth()->user()->favoriteProjectFiles()->attach($projectFile->id);
        return back();
    }

    public function unfavorite(ProjectFile $projectFile)
    {
        auth()->user()->favoriteProjectFiles()->detach($projectFile->id);
        return back();
    }
}
