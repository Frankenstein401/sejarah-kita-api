<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use App\Services\FunFactService;
use Illuminate\Http\JsonResponse;

class FunFactController extends Controller
{
    public function __construct(
        protected FunFactService $funFactService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->funFactService->getActive()]);
    }

    public function random(): JsonResponse
    {
        return response()->json(['data' => $this->funFactService->getRandom()]);
    }
}
