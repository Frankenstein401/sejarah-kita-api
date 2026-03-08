<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;

class QuizController extends Controller
{
    public function __construct(
        protected QuizService $quizService
    ) {}

    public function show(string $slug): JsonResponse
    {
        $quiz = $this->quizService->getByArticleSlug($slug);

        if (!$quiz) {
            return response()->json(['message' => 'Quiz belum tersedia untuk artikel ini.'], 404);
        }

        return response()->json(['data' => $quiz]);
    }
}
