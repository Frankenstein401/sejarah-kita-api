<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use App\Services\TimelineService;
use Illuminate\Http\JsonResponse;

class TimelineController extends Controller
{
    public function __construct(
        protected TimelineService $timelineService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->timelineService->getAll()]);
    }
}
