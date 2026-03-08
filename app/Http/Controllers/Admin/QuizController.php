<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\QuizService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(
        protected QuizService $quizService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->quizService->getAllForAdmin()]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'article_id'                => 'required|exists:articles,id|unique:quizzes,article_id',
            'title'                     => 'required|string|max:255',
            'questions'                 => 'required|array|min:1',
            'questions.*.question'      => 'required|string',
            'questions.*.options'       => 'required|array|size:4',
            'questions.*.correct_index' => 'required|integer|min:0|max:3',
            'questions.*.explanation'   => 'nullable|string',
        ]);

        $quiz = $this->quizService->create($request->all());

        return response()->json([
            'data'    => $quiz,
            'message' => 'Quiz berhasil dibuat.',
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json(['data' => $this->quizService->findForAdmin($id)]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'title'                     => 'string|max:255',
            'questions'                 => 'array',
            'questions.*.question'      => 'required|string',
            'questions.*.options'       => 'required|array|size:4',
            'questions.*.correct_index' => 'required|integer|min:0|max:3',
            'questions.*.explanation'   => 'nullable|string',
        ]);

        $quiz = $this->quizService->update($id, $request->all());

        return response()->json([
            'data'    => $quiz,
            'message' => 'Quiz berhasil diperbarui.',
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->quizService->delete($id);

        return response()->json(['message' => 'Quiz berhasil dihapus.']);
    }
}
