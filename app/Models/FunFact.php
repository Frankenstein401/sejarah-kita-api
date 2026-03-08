<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class FunFact extends Model
{
    use HasUuids;

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    protected $fillable = [
        'text',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
