<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['phase_id', 'client_id', 'name', 'description', 'category_id', 'revenue', 'profit'];

    // フェーズの取得
    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    // // 取引先の取得
    // public function client()
    // {
    //     return $this->belongsTo(Client::class);
    // }

    // // 商材カテゴリの取得
    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }
}
