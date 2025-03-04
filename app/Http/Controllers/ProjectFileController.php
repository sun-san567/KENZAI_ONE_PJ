<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProjectFileController extends Controller
{
    /**
     * 📂 指定プロジェクトのファイル一覧を取得
     */
    public function index(Project $project)
    {
        return response()->json($project->files);
    }

    /**
     * 📂 ファイルアップロード処理
     */
    public function upload(Request $request, Project $project)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 最大10MB
            'category' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('project_files', 'public'); // `storage/app/public/project_files/` に保存
        $fileExtension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        $uploadedFile = ProjectFile::create([
            'project_id' => $project->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'mime_type' => $mimeType,
            'file_extension' => $fileExtension,
            'size' => $size,
            'category' => $request->input('category', 'その他'),
            'preview_path' => $mimeType === 'image/jpeg' || $mimeType === 'image/png' ? $filePath : null,
            'uploaded_by' => Auth::id(),
        ]);

        return response()->json($uploadedFile, 201);
    }

    /**
     * 🗑 ファイル削除処理
     */
    public function destroy(Project $project, ProjectFile $file)
    {
        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return response()->json(['message' => 'ファイルが削除されました。']);
    }
}
