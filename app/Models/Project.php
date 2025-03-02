<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;


class Project extends Model
{
    use HasFactory;

    protected $fillable = ['phase_id', 'client_id', 'name', 'description', 'category_id', 'revenue', 'profit', 'cost'];

    public function phase()
    {
        return $this->belongsTo(Phase::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'project_category');
    }


    // 履歴を保存するリレーション
    public function histories()
    {
        return $this->hasMany(ProjectHistory::class);
    }

    // 更新時に履歴を記録するメソッド
    public function saveWithHistory($data)
    {
        // 変更前のデータを取得
        $oldData = $this->only(['revenue', 'profit', 'cost']);

        // データを更新
        $this->update($data);

        // 変更があれば履歴を保存
        if (array_diff_assoc($oldData, $this->only(['revenue', 'profit', 'cost']))) {
            $this->histories()->create([
                'revenue' => $this->revenue,
                'profit' => $this->profit,
                'cost' => $this->cost,
                'changed_at' => now(),
            ]);
        }
    }
}
