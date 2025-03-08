<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

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
        return view('projects.files.index', [
            'project' => $project,
            'files' => $project->files()->latest()->get()
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

    public function destroy(Project $project, ProjectFile $file)
    {
        abort_unless(auth()->user()->can('update', $project), 403);

        Storage::delete($file->file_path);
        $file->delete();

        return response()->json(['message' => 'ファイルを削除しました']);
    }
}
