<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function __construct(
        protected ArticleService $articleService
    ) {}

    public function index(): JsonResponse
    {
        return response()->json(['data' => $this->articleService->getAllForAdmin()]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'slug'                     => 'required|string|unique:articles',
            'title'                    => 'required|string|max:255',
            'era_id'                   => 'required|exists:eras,id',
            'year'                     => 'required|string',
            'summary'                  => 'required|string',
            'hero_image'               => 'nullable|string',
            'is_published'             => 'boolean',
            'sections'                 => 'array',
            'sections.*.heading'       => 'required|string',
            'sections.*.paragraphs'    => 'required|array',
            'sections.*.image_url'     => 'nullable|string',
            'sections.*.image_caption' => 'nullable|string',
            'videos'                   => 'array',
            'videos.*.youtube_id'      => 'required|string',
            'videos.*.title'           => 'required|string',
            'videos.*.channel'         => 'nullable|string',
            'related_slugs'            => 'array',
            'related_slugs.*'          => 'exists:articles,slug',
        ]);

        $article = $this->articleService->create($request->all());

        return response()->json([
            'data'    => $article,
            'message' => 'Artikel berhasil dibuat.',
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        return response()->json(['data' => $this->articleService->findForAdmin($id)]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'slug'         => "string|unique:articles,slug,{$id}",
            'title'        => 'string|max:255',
            'era_id'       => 'exists:eras,id',
            'year'         => 'string',
            'summary'      => 'string',
            'hero_image'   => 'nullable|string',
            'is_published' => 'boolean',
            'sections'     => 'array',
            'videos'       => 'array',
            'related_slugs'=> 'array',
        ]);

        $article = $this->articleService->update($id, $request->all());

        return response()->json([
            'data'    => $article,
            'message' => 'Artikel berhasil diperbarui.',
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $this->articleService->delete($id);

        return response()->json(['message' => 'Artikel berhasil dihapus.']);
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $path = $request->file('image')->store('articles', 'public');

        return response()->json([
            'url' => Storage::disk('public')->url($path),
        ]);
    }
}
