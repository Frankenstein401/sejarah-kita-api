<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Bookmark;
use Illuminate\Database\Eloquent\Collection;

class BookmarkService
{
    public function getUserBookmarks(string $userId): Collection
    {
        return Bookmark::where('user_id', $userId)
            ->with('article:id,slug,title,year,summary,hero_image')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function toggle(string $userId, string $articleId): array
    {
        Article::findOrFail($articleId);

        $existing = Bookmark::where('user_id', $userId)
            ->where('article_id', $articleId)
            ->first();

        if ($existing) {
            $existing->delete();

            return ['bookmarked' => false, 'message' => 'Bookmark dihapus.'];
        }

        Bookmark::create([
            'user_id'    => $userId,
            'article_id' => $articleId,
        ]);

        return ['bookmarked' => true, 'message' => 'Artikel di-bookmark.'];
    }
}
