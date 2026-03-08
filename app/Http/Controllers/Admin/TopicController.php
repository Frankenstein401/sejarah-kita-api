<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\TopicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function __construct(
        protected TopicService $topicService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'icon_name'   => 'required|string',
            'era_id'      => 'nullable|exists:eras,id',
            'sort_order'  => 'integer',
        ]);

        $topic = $this->topicService->create($request->all());

        return response()->json([
            'data'    => $topic,
            'message' => 'Topik berhasil dibuat.',
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $topic = $this->topicService->update($id, $request->all());

        return response()->json([
            'data'    => $topic,
            'message' => 'Topik berhasil diperbarui.',
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->topicService->delete($id);

        return response()->json(['message' => 'Topik berhasil dihapus.']);
    }
}
