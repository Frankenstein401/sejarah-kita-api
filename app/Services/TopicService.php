<?php

namespace App\Services;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Collection;

class TopicService
{
    public function getAll(): Collection
    {
        return Topic::with('era:id,name,slug')
            ->orderBy('sort_order')
            ->get();
    }

    public function create(array $data): Topic
    {
        return Topic::create($data);
    }

    public function update(string $id, array $data): Topic
    {
        $topic = Topic::findOrFail($id);
        $topic->update($data);

        return $topic;
    }

    public function delete(string $id): void
    {
        Topic::findOrFail($id)->delete();
    }
}
