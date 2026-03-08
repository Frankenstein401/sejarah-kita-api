<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\DiscussionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DiscussionController extends Controller
{
    public function __construct(
        protected DiscussionService $discussionService
    ) {}

    public function store(Request $request, string $slug): JsonResponse
    {
        $request->validate([
            'message'   => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:discussions,id',
        ]);

        $discussion = $this->discussionService->store(
            slug: $slug,
            userId: auth('api')->id(),
            data: $request->only('message', 'parent_id'),
        );

        return response()->json([
            'data'    => $discussion,
            'message' => 'Komentar berhasil dikirim, menunggu persetujuan admin.',
        ], 201);
    }
}
