<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\ReadingProgressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReadingProgressController extends Controller
{
    public function __construct(
        protected ReadingProgressService $progressService
    ) {}

    public function index(): JsonResponse
    {
        $progress = $this->progressService->getUserProgress(auth('api')->id());

        return response()->json(['data' => $progress]);
    }

    public function update(Request $request, string $articleId): JsonResponse
    {
        // Ambil nilai dari berbagai kemungkinan sumber dan nama field
        $percent = $request->input('progress_percent') 
                ?? $request->query('progress_percent') 
                ?? $request->input('percent') 
                ?? $request->query('percent');

        $data = ['progress_percent' => $percent];

        $validator = \Illuminate\Support\Facades\Validator::make($data, [
            'progress_percent' => 'required|integer|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'debug_received_all' => $request->all(), // Lihat apa yang diterima
                'debug_received_query' => $request->query(),
                'debug_method' => $request->method(),
                'errors'  => $validator->errors()
            ], 422);
        }

        $progress = $this->progressService->updateProgress(
            userId: auth('api')->id(),
            articleId: $articleId,
            percent: (int) $percent,
        );

        return response()->json(['data' => $progress]);
    }

    public function stats(): JsonResponse
    {
        $stats = $this->progressService->getStats(auth('api')->id());

        return response()->json(['data' => $stats]);
    }
}
