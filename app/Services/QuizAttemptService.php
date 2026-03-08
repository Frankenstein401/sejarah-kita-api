<?php

namespace App\Services;

use App\Models\Article;
use App\Models\QuizAttempt;
use Illuminate\Database\Eloquent\Collection;

class QuizAttemptService
{
    public function store(string $slug, string $userId, array $data): QuizAttempt
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        return QuizAttempt::create([
            'user_id'      => $userId,
            'article_id'   => $article->id,
            'score'        => $data['score'],
            'total'        => $data['total'],
            'time_seconds' => $data['time_seconds'] ?? null,
        ]);
    }

    public function getHistory(string $userId): Collection
    {
        return QuizAttempt::where('user_id', $userId)
            ->with('article:id,slug,title')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
