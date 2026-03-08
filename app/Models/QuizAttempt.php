<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'article_id',
        'score',
        'total',
        'time_seconds',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'total' => 'integer',
            'time_seconds' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
