<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Support\Facades\Storage;

class ProjectFileController extends Controller
{
    public function upload(Request $request, Project $project)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240|mimes:pdf,xlsx,dwg,jpg,png',
        ]);

        foreach ($request->file('files') as $file) {
            $path = $file->store('project_files/' . $project->id, 'public');

            ProjectFile::create([
                'project_id' => $project->id,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'size' => $file->getSize(),
                'uploaded_by' => auth()->id(),
            ]);
        }

        return back()->with('success', 'ファイルがアップロードされました');
    }
}
