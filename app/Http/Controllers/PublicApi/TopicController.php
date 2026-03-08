<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use App\Services\TopicService;
use Illuminate\Http\JsonResponse;

class TopicController extends Controller
{
    public function __construct(
        protected TopicService $topicService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->topicService->getAll()]);
    }
}
