<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use App\Services\EraService;
use Illuminate\Http\JsonResponse;

class EraController extends Controller
{
    public function __construct(
        protected EraService $eraService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->eraService->getAll()]);
    }

    public function show(string $slug): JsonResponse
    {
        return response()->json(['data' => $this->eraService->getBySlug($slug)]);
    }
}
