<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phase_id', 'description'];

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }
}
