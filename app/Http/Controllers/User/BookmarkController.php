<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\BookmarkService;
use Illuminate\Http\JsonResponse;

class BookmarkController extends Controller
{
    public function __construct(
        protected BookmarkService $bookmarkService
    ) {}

    public function index(): JsonResponse
    {
        $bookmarks = $this->bookmarkService->getUserBookmarks(auth('api')->id());

        return response()->json(['data' => $bookmarks]);
    }

    public function toggle(string $articleId): JsonResponse
    {
        $result = $this->bookmarkService->toggle(auth('api')->id(), $articleId);

        return response()->json($result, $result['bookmarked'] ? 201 : 200);
    }
}
