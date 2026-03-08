<?php

namespace App\Services;

use App\Models\Era;
use Illuminate\Database\Eloquent\Collection;

class EraService
{
    public function getAll(): Collection
    {
        return Era::orderBy('sort_order')->get();
    }

    public function getBySlug(string $slug): Era
    {
        return Era::where('slug', $slug)
            ->with('articles:id,slug,title,era_id,year,summary,hero_image')
            ->firstOrFail();
    }
}
