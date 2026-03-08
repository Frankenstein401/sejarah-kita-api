<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(
        protected ArticleService $articleService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $articles = $this->articleService->getPublished(
            era: $request->query('era'),
            search: $request->query('search'),
        );

        return response()->json(['data' => $articles]);
    }

    public function show(string $slug): JsonResponse
    {
        return response()->json(['data' => $this->articleService->getBySlug($slug)]);
    }
}
