<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ReadingProgress;
use Illuminate\Database\Eloquent\Collection;

class ReadingProgressService
{
    public function getUserProgress(string $userId): Collection
    {
        return ReadingProgress::where('user_id', $userId)
            ->with('article:id,slug,title,hero_image')
            ->get();
    }

    public function updateProgress(string $userId, string $articleId, int $percent): ReadingProgress
    {
        Article::findOrFail($articleId);

        $progress = ReadingProgress::firstOrNew([
            'user_id'    => $userId,
            'article_id' => $articleId,
        ]);

        // Keep the highest progress
        if ($percent > ($progress->progress_percent ?? 0)) {
            $progress->progress_percent = $percent;
        }

        // Only mark completed if it wasn't already
        if (!$progress->is_completed && $percent >= 100) {
            $progress->is_completed = true;
            $progress->completed_at = now();
        }

        $progress->save();

        return $progress;
    }

    public function getStats(string $userId): array
    {
        $user = \App\Models\User::findOrFail($userId);

        return [
            'total_articles'  => Article::published()->count(),
            'articles_read'   => $user->readingProgress()->where('is_completed', true)->count(),
            'quizzes_taken'   => $user->quizAttempts()->count(),
            'average_score'   => round($user->quizAttempts()->avg('score') ?? 0, 1),
            'bookmarks_count' => $user->bookmarks()->count(),
        ];
    }
}
