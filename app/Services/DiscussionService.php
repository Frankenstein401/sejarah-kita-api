<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Discussion;
use Illuminate\Database\Eloquent\Collection;

class DiscussionService
{
    // ── Public (approved only, with nested replies) ──

    public function getByArticleSlug(string $slug): Collection
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        return $article->discussions()
            ->where('is_approved', true)
            ->whereNull('parent_id')
            ->with([
                'user:id,name,avatar',
                'replies' => fn($q) => $q
                    ->where('is_approved', true)
                    ->with('user:id,name,avatar')
                    ->orderBy('created_at'),
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // ── User (post comment) ──

    public function store(string $slug, string $userId, array $data): Discussion
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        $discussion = Discussion::create([
            'article_id'  => $article->id,
            'user_id'     => $userId,
            'parent_id'   => $data['parent_id'] ?? null,
            'message'     => $data['message'],
            'is_approved' => false,
        ]);

        return $discussion->load('user:id,name,avatar');
    }

    // ── Admin (moderation) ──

    public function getAllForAdmin(?string $status = null): Collection
    {
        $query = Discussion::with(['user:id,name,email', 'article:id,slug,title']);

        if ($status === 'approved') {
            $query->where('is_approved', true);
        } elseif ($status === 'pending') {
            $query->where('is_approved', false);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function approve(string $id): Discussion
    {
        $discussion = Discussion::findOrFail($id);
        $discussion->update(['is_approved' => true]);

        return $discussion;
    }

    public function reject(string $id): Discussion
    {
        $discussion = Discussion::findOrFail($id);
        $discussion->update(['is_approved' => false]);

        return $discussion;
    }

    public function delete(string $id): void
    {
        Discussion::findOrFail($id)->delete();
    }
}

