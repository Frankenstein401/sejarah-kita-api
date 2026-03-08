<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Topic extends Model
{
    use HasUuids;

    protected $fillable = [
        'title',
        'description',
        'icon_name',
        'era_id',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function era(): BelongsTo
    {
        return $this->belongsTo(Era::class);
    }
}
