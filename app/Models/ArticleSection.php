<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleSection extends Model
{
    use HasUuids;

    protected $fillable = [
        'article_id',
        'heading',
        'paragraphs',
        'image_url',
        'image_caption',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'paragraphs' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
