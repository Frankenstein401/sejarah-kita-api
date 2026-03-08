<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Article extends Model
{
    use HasUuids;

    protected $fillable = [
        'slug',
        'title',
        'era_id',
        'year',
        'summary',
        'hero_image',
        'is_published',
        'view_count',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'view_count' => 'integer',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    public function era(): BelongsTo
    {
        return $this->belongsTo(Era::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(ArticleSection::class);
    }

    public function videos(): HasMany
    {
        return $this->hasMany(ArticleVideo::class);
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    public function discussions(): HasMany
    {
        return $this->hasMany(Discussion::class);
    }

    public function relatedArticles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_relations', 'article_id', 'related_article_id');
    }

    public function bookmarkedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'bookmarks')->withTimestamps();
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function readingProgress(): HasMany
    {
        return $this->hasMany(ReadingProgress::class);
    }

    public function mapLocations(): HasMany
    {
        return $this->hasMany(MapLocation::class);
    }
}
