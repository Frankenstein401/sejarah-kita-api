<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use App\Services\DiscussionService;
use Illuminate\Http\JsonResponse;

class DiscussionController extends Controller
{
    public function __construct(
        protected DiscussionService $discussionService
    ) {}

    public function index(string $slug): JsonResponse
    {
        return response()->json(['data' => $this->discussionService->getByArticleSlug($slug)]);
    }
}
