<?php

namespace App\Services;

use App\Models\MapLocation;
use Illuminate\Database\Eloquent\Collection;

class MapLocationService
{
    public function getAll(?string $era = null): Collection
    {
        $query = MapLocation::with('era:id,name,slug,color_hue');

        if ($era) {
            $query->whereHas('era', fn($q) => $q->where('slug', $era));
        }

        return $query->get();
    }

    public function create(array $data): MapLocation
    {
        $location = MapLocation::create($data);

        return $location->load('era:id,name');
    }

    public function update(string $id, array $data): MapLocation
    {
        $location = MapLocation::findOrFail($id);
        $location->update($data);

        return $location->load('era:id,name');
    }

    public function delete(string $id): void
    {
        MapLocation::findOrFail($id)->delete();
    }
}
