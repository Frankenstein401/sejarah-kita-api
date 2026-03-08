<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TimelineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    public function __construct(
        protected TimelineService $timelineService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'era_id'        => 'required|exists:eras,id',
            'year'          => 'required|string',
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'detail'        => 'nullable|string',
            'significance'  => 'nullable|array',
            'figures'       => 'nullable|array',
            'image_url'     => 'nullable|string',
            'image_caption' => 'nullable|string',
            'article_slug'  => 'nullable|string',
            'sort_order'    => 'integer',
        ]);

        $event = $this->timelineService->create($request->all());

        return response()->json([
            'data'    => $event,
            'message' => 'Timeline event berhasil dibuat.',
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $event = $this->timelineService->update($id, $request->all());

        return response()->json([
            'data'    => $event,
            'message' => 'Timeline event berhasil diperbarui.',
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->timelineService->delete($id);

        return response()->json(['message' => 'Timeline event berhasil dihapus.']);
    }
}
