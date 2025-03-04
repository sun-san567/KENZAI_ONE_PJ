<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['phase_id', 'client_id', 'name', 'description', 'revenue', 'profit', 'cost'];

    /**
     * フェーズとのリレーション
     */
    public function phase(): BelongsTo
    {
        return $this->belongsTo(Phase::class);
    }

    /**
     * クライアントとのリレーション
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * カテゴリとの多対多リレーション
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'project_categories', 'project_id', 'category_id');
    }

    /**
     * 履歴のリレーション
     */
    public function histories(): HasMany
    {
        return $this->hasMany(ProjectHistory::class);
    }

    /**
     * 更新時に履歴を記録するメソッド
     */
    public function saveWithHistory(array $data)
    {
        // 変更前のデータを取得
        $oldData = $this->only(['revenue', 'profit', 'cost']);

        // データを更新
        $this->update($data);
        $this->refresh(); // 最新のデータを同期

        // 変更があった場合のみ履歴を記録
        if ($this->isDirty(['revenue', 'profit', 'cost'])) {
            $this->histories()->create([
                'revenue' => $this->revenue,
                'profit' => $this->profit,
                'cost' => $this->cost,
                'changed_at' => now(),
            ]);
        }
    }

    public function files()
    {
        return $this->hasMany(ProjectFile::class);
    }
}
