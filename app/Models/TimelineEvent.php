<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimelineEvent extends Model
{
    use HasUuids;

    protected $fillable = [
        'era_id',
        'year',
        'title',
        'description',
        'detail',
        'significance',
        'figures',
        'image_url',
        'image_caption',
        'article_slug',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'significance' => 'array',
            'figures' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function era(): BelongsTo
    {
        return $this->belongsTo(Era::class);
    }
}
