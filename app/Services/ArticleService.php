<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;

class ArticleService
{
    // ── Public ──

    public function getPublished(?string $era = null, ?string $search = null): Collection
    {
        $query = Article::published()->with('era:id,name,slug,color_hue');

        if ($era) {
            $query->whereHas('era', fn($q) => $q->where('slug', $era));
        }

        if ($search) {
            $query->where(fn($q) => $q
                ->where('title', 'like', "%{$search}%")
                ->orWhere('summary', 'like', "%{$search}%")
            );
        }

        return $query->orderBy('created_at', 'desc')
            ->get(['id', 'slug', 'title', 'era_id', 'year', 'summary', 'hero_image', 'view_count', 'created_at']);
    }

    public function getBySlug(string $slug): Article
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->with([
                'era:id,name,slug,color_hue',
                'sections',
                'videos',
                'quiz.questions',
                'relatedArticles:id,slug,title,era_id,year,summary,hero_image',
            ])
            ->firstOrFail();

        $article->increment('view_count');

        return $article;
    }

    // ── Admin ──

    public function getAllForAdmin(): Collection
    {
        return Article::with('era:id,name,slug')
            ->withCount('sections', 'discussions', 'videos')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findForAdmin(string $id): Article
    {
        return Article::with(['era', 'sections', 'videos', 'relatedArticles:id,slug,title'])
            ->findOrFail($id);
    }

    public function create(array $data): Article
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            $article = Article::create($this->extractArticleFields($data));

            $this->syncSections($article, $data['sections'] ?? []);
            $this->syncVideos($article, $data['videos'] ?? []);
            $this->syncRelations($article, $data['related_slugs'] ?? []);

            return $article->load('sections', 'videos', 'relatedArticles:id,slug,title');
        });
    }

    public function update(string $id, array $data): Article
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($id, $data) {
            $article = Article::findOrFail($id);
            $article->update($this->extractArticleFields($data));

            if (array_key_exists('sections', $data)) {
                $this->syncSections($article, $data['sections']);
            }

            if (array_key_exists('videos', $data)) {
                $this->syncVideos($article, $data['videos']);
            }

            if (array_key_exists('related_slugs', $data)) {
                $this->syncRelations($article, $data['related_slugs']);
            }

            return $article->load('sections', 'videos', 'relatedArticles:id,slug,title');
        });
    }

    public function delete(string $id): void
    {
        Article::findOrFail($id)->delete();
    }

    // ── Private Helpers ──

    protected function extractArticleFields(array $data): array
    {
        return collect($data)
            ->only(['slug', 'title', 'era_id', 'year', 'summary', 'hero_image', 'is_published'])
            ->toArray();
    }

    protected function syncSections(Article $article, array $sections): void
    {
        $article->sections()->delete();

        foreach ($sections as $i => $section) {
            $article->sections()->create(array_merge($section, ['sort_order' => $i]));
        }
    }

    protected function syncVideos(Article $article, array $videos): void
    {
        $article->videos()->delete();

        foreach ($videos as $i => $video) {
            $article->videos()->create(array_merge($video, ['sort_order' => $i]));
        }
    }

    protected function syncRelations(Article $article, array $slugs): void
    {
        $relatedIds = Article::whereIn('slug', $slugs)->pluck('id');
        $article->relatedArticles()->sync($relatedIds);
    }
}
