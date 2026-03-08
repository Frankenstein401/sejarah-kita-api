<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Discussion;
use App\Models\Era;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $statsOverview = [
            'totalViews'        => Article::sum('view_count'),
            'totalArticles'     => Article::count(),
            'totalComments'     => Discussion::count(),
            'totalUsers'        => User::count(),
            'totalQuizAttempts' => QuizAttempt::count(),
            'pendingComments'   => Discussion::where('is_approved', false)->count(),
        ];

        // Top 5 articles by view count
        $topArticles = Article::with('era')
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get()
            ->map(fn($a) => [
                'id'    => $a->id,
                'title' => $a->title,
                'views' => $a->view_count,
                'slug'  => $a->slug,
                'era'   => $a->era?->name ?? 'Unknown',
            ]);

        // Articles & total views grouped by era (for charts)
        $articlesPerEra = Era::withCount('articles')
            ->get()
            ->map(fn($era) => [
                'name'     => $era->name,
                'slug'     => $era->slug,
                'articles' => $era->articles_count,
                'views'    => Article::where('era_id', $era->id)->sum('view_count'),
            ]);

        // Recent 5 discussions
        $recentComments = Discussion::with(['user', 'article'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn($d) => [
                'id'          => $d->id,
                'user'        => $d->user?->name ?? 'Anonim',
                'article'     => $d->article?->title ?? '-',
                'message'     => $d->message,
                'is_approved' => $d->is_approved,
                'created_at'  => $d->created_at->diffForHumans(),
            ]);

        return response()->json([
            'data' => [
                'stats'          => $statsOverview,
                'topArticles'    => $topArticles,
                'articlesPerEra' => $articlesPerEra,
                'recentComments' => $recentComments,
            ]
        ]);
    }
}
