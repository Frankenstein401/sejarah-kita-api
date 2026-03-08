<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FunFactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FunFactController extends Controller
{
    public function __construct(
        protected FunFactService $funFactService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->funFactService->getAll()]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'text'      => 'required|string',
            'is_active' => 'boolean',
        ]);

        $fact = $this->funFactService->create($request->all());

        return response()->json([
            'data'    => $fact,
            'message' => 'Fun fact berhasil dibuat.',
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $fact = $this->funFactService->update($id, $request->all());

        return response()->json([
            'data'    => $fact,
            'message' => 'Fun fact berhasil diperbarui.',
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->funFactService->delete($id);

        return response()->json(['message' => 'Fun fact berhasil dihapus.']);
    }
}
