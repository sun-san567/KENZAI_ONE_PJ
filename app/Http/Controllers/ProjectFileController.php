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

    public function index(Project $project)
    {
        $files = ProjectFile::where('project_id', $project->id)->paginate(15);
        return view('projects.files.index', [
            'project' => $project,
            'files' => $files
        ]);
    }

    public function upload(Request $request, Project $project)
    {
        // 複数ファイルのバリデーション
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|max:102400', // 100MB制限
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
     * 指定されたファイルを削除（MVP版：権限チェックを簡略化）
     */
    public function destroy(Project $project, $fileId)
    {
        // MVP段階では権限チェックを省略
        // TODO: リリース前に適切な権限チェックを実装する
        
        $file = ProjectFile::findOrFail($fileId);
        
        // プロジェクトとファイルの関連性だけは確認
        if ($file->project_id !== $project->id) {
            return redirect()->back()->with('error', 'このファイルはプロジェクトに属していません。');
        }
        
        try {
            // ストレージからファイルを削除
            if ($file->file_path) {
                $filePath = storage_path('app/public/files/' . $file->file_path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            // データベースからレコードを削除
            $file->delete();
            
            return redirect()->back()->with('success', 'ファイルを削除しました。');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'ファイルの削除中にエラーが発生しました: ' . $e->getMessage());
        }
    }

    /**
     * 複数のファイルを一括削除（MVP版：権限チェックを簡略化）
     */
    public function bulkDelete(Request $request, Project $project)
    {
        // MVP段階では権限チェックを省略
        // TODO: リリース前に適切な権限チェックを実装する
        
        $request->validate([
            'file_ids' => 'required|array',
            'file_ids.*' => 'integer|exists:project_files,id'
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
                if ($file->file_path) {
                    $filePath = storage_path('app/public/files/' . $file->file_path);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
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
            return response()->json(['error' => 'ファイルの削除中にエラーが発生しました: ' . $e->getMessage()], 500);
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
}
