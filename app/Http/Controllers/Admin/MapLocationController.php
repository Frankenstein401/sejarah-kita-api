<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MapLocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MapLocationController extends Controller
{
    public function __construct(
        protected MapLocationService $mapLocationService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->mapLocationService->getAll()]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'era_id'       => 'required|exists:eras,id',
            'article_id'   => 'nullable|exists:articles,id',
            'name'         => 'required|string|max:255',
            'latitude'     => 'required|numeric',
            'longitude'    => 'required|numeric',
            'year'         => 'required|string',
            'description'  => 'required|string',
            'color'        => 'nullable|string|max:20',
            'article_slug' => 'nullable|string',
        ]);

        $location = $this->mapLocationService->create($request->all());

        return response()->json([
            'data'    => $location,
            'message' => 'Lokasi berhasil ditambahkan.',
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $location = $this->mapLocationService->update($id, $request->all());

        return response()->json([
            'data'    => $location,
            'message' => 'Lokasi berhasil diperbarui.',
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->mapLocationService->delete($id);

        return response()->json(['message' => 'Lokasi berhasil dihapus.']);
    }
}
