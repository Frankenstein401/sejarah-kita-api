<?php

namespace App\Http\Controllers\PublicApi;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Era;
use App\Models\QuizAttempt;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'data' => [
                'total_articles' => Article::published()->count(),
                'total_eras' => Era::count(),
                'total_quiz_completed' => QuizAttempt::count(),
                'years_covered' => 1700, // Hardcoded for now, or calculate from eras/articles
            ]
        ]);
    }
}
