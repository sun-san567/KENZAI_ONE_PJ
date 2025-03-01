<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'department_id', // 追加
        'user_id',       // 追加
        'name',
        'phone',
        'address',
    ];
}
