<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'order', 'department_id'];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * このフェーズが所属する部門を取得
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
