<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
    ];

    /**
     * この会社に所属するユーザーを取得
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * この会社の部門を取得
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}
