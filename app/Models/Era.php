<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Era extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'color_hue',
        'badge_class',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function timelineEvents(): HasMany
    {
        return $this->hasMany(TimelineEvent::class);
    }

    public function mapLocations(): HasMany
    {
        return $this->hasMany(MapLocation::class);
    }

    public function topics(): HasMany
    {
        return $this->hasMany(Topic::class);
    }
}
