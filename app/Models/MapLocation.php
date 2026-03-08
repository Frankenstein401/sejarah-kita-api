<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MapLocation extends Model
{
    use HasUuids;

    protected $fillable = [
        'era_id',
        'article_id',
        'name',
        'latitude',
        'longitude',
        'year',
        'description',
        'color',
        'article_slug',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:6',
            'longitude' => 'decimal:6',
        ];
    }

    public function era(): BelongsTo
    {
        return $this->belongsTo(Era::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
