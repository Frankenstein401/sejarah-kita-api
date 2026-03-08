<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Collection;

class QuizService
{
    // ── Public ──

    public function getByArticleSlug(string $slug): ?Quiz
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        return $article->quiz()->with('questions')->first();
    }

    // ── Admin ──

    public function getAllForAdmin(): Collection
    {
        return Quiz::with('article:id,slug,title')
            ->withCount(['questions', 'attempts'])
            ->get()
            ->map(function ($quiz) {
                // Calculate average score if there are attempts
                $avg = $quiz->attempts()->count() > 0
                    ? $quiz->attempts()->whereRaw('total > 0')->avg(\Illuminate\Support\Facades\DB::raw('score * 100 / total')) ?? 0
                    : 0;
                
                $quiz->avg_score = round($avg);
                return $quiz;
            });
    }

    public function findForAdmin(string $id): Quiz
    {
        return Quiz::with(['article:id,slug,title', 'questions'])->findOrFail($id);
    }

    public function create(array $data): Quiz
    {
        $quiz = Quiz::create([
            'article_id' => $data['article_id'],
            'title'      => $data['title'],
        ]);

        $this->syncQuestions($quiz, $data['questions']);

        return $quiz->load('questions');
    }

    public function update(string $id, array $data): Quiz
    {
        $quiz = Quiz::findOrFail($id);

        if (isset($data['title'])) {
            $quiz->update(['title' => $data['title']]);
        }

        if (array_key_exists('questions', $data)) {
            $this->syncQuestions($quiz, $data['questions']);
        }

        return $quiz->load('questions');
    }

    public function delete(string $id): void
    {
        Quiz::findOrFail($id)->delete();
    }

    // ── Private ──

    protected function syncQuestions(Quiz $quiz, array $questions): void
    {
        $quiz->questions()->delete();

        foreach ($questions as $i => $q) {
            $quiz->questions()->create(array_merge($q, ['sort_order' => $i]));
        }
    }
}
