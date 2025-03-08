<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_extension',
        'size',
        'category',
        'preview_path',
        'uploaded_by'
    ];

    protected $table = 'project_files';

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
