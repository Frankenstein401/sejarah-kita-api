<?php

namespace App\Services;

use App\Models\FunFact;
use Illuminate\Database\Eloquent\Collection;

class FunFactService
{
    // ── Public ──

    public function getActive(): Collection
    {
        return FunFact::active()->get();
    }

    public function getRandom(): ?FunFact
    {
        return FunFact::active()->inRandomOrder()->first();
    }

    // ── Admin ──

    public function getAll(): Collection
    {
        return FunFact::all();
    }

    public function create(array $data): FunFact
    {
        return FunFact::create($data);
    }

    public function update(string $id, array $data): FunFact
    {
        $fact = FunFact::findOrFail($id);
        $fact->update($data);

        return $fact;
    }

    public function delete(string $id): void
    {
        FunFact::findOrFail($id)->delete();
    }
}
