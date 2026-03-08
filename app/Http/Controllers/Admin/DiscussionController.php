<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DiscussionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function __construct(
        protected DiscussionService $discussionService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $discussions = $this->discussionService->getAllForAdmin($request->query('status'));

        return response()->json(['data' => $discussions]);
    }

    public function approve(string $id): JsonResponse
    {
        $discussion = $this->discussionService->approve($id);

        return response()->json([
            'data'    => $discussion,
            'message' => 'Komentar disetujui.',
        ]);
    }

    public function reject(string $id): JsonResponse
    {
        $discussion = $this->discussionService->reject($id);

        return response()->json([
            'data'    => $discussion,
            'message' => 'Komentar ditolak.',
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->discussionService->delete($id);

        return response()->json(['message' => 'Komentar dihapus.']);
    }
}
