<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LearningLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id','studied_on','minutes','title','memo'];

    protected $casts = [
        'studied_on' => 'date',
    ];

    /** 自分のログだけに絞る */
    public function scopeOwnedBy(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /** キーワード検索（title/memo） */
    public function scopeSearch(Builder $query, string $q): Builder
    {
        $q = trim($q);
        if ($q === '') return $query;

        return $query->where(function (Builder $sub) use ($q) {
            $sub->where('title', 'like', "%{$q}%")
                ->orWhere('memo', 'like', "%{$q}%");
        });
    }

    /** 一覧用の並び順 */
    public function scopeLatestStudied(Builder $query): Builder
    {
        return $query->orderByDesc('studied_on')->orderByDesc('id');
    }

    /** 今日の合計 minutes */
    public function scopeTodayTotalMinutes(Builder $query, int $userId): int
    {
        return (int) $query->ownedBy($userId)
            ->whereDate('studied_on', now()->toDateString())
            ->sum('minutes');
    }
}
