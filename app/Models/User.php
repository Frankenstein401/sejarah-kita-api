<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasUuids, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'education',
        'password',
        'avatar',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ── JWT ──

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role' => $this->role];
    }

    // ── Helpers ──

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ── Relationships ──

    public function discussions()
    {
        return $this->hasMany(Discussion::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function readingProgress()
    {
        return $this->hasMany(ReadingProgress::class);
    }

    public function bookmarkedArticles()
    {
        return $this->belongsToMany(Article::class, 'bookmarks')->withTimestamps();
    }
}
