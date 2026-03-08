<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use App\Services\MapLocationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MapLocationController extends Controller
{
    public function __construct(
        protected MapLocationService $mapLocationService
    ) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->mapLocationService->getAll($request->query('era')),
        ]);
    }
}
