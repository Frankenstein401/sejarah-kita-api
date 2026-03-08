<?php

namespace App\Services;

use App\Models\TimelineEvent;
use Illuminate\Database\Eloquent\Collection;

class TimelineService
{
    public function getAll(): Collection
    {
        return TimelineEvent::with('era:id,name,slug,color_hue')
            ->orderBy('sort_order')
            ->get();
    }

    public function create(array $data): TimelineEvent
    {
        $event = TimelineEvent::create($data);

        return $event->load('era:id,name');
    }

    public function update(string $id, array $data): TimelineEvent
    {
        $event = TimelineEvent::findOrFail($id);
        $event->update($data);

        return $event->load('era:id,name');
    }

    public function delete(string $id): void
    {
        TimelineEvent::findOrFail($id)->delete();
    }
}
