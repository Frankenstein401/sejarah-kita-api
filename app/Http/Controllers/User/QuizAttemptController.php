<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\QuizAttemptService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizAttemptController extends Controller
{
    public function __construct(
        protected QuizAttemptService $quizAttemptService
    ) {}

    public function store(Request $request, string $slug): JsonResponse
    {
        $request->validate([
            'score'        => 'required|integer|min:0|lte:total',
            'total'        => 'required|integer|min:1',
            'time_seconds' => 'nullable|integer|min:0',
        ]);

        $attempt = $this->quizAttemptService->store(
            slug: $slug,
            userId: auth('api')->id(),
            data: $request->only('score', 'total', 'time_seconds'),
        );

        return response()->json([
            'data'    => $attempt,
            'message' => 'Hasil quiz tersimpan.',
        ], 201);
    }

    public function history(): JsonResponse
    {
        $attempts = $this->quizAttemptService->getHistory(auth('api')->id());

        return response()->json(['data' => $attempts]);
    }
}
