<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\AuthController;

// Public
use App\Http\Controllers\PublicApi\EraController;
use App\Http\Controllers\PublicApi\ArticleController;
use App\Http\Controllers\PublicApi\QuizController;
use App\Http\Controllers\PublicApi\DiscussionController as PublicDiscussionController;
use App\Http\Controllers\PublicApi\TimelineController;
use App\Http\Controllers\PublicApi\MapLocationController;
use App\Http\Controllers\PublicApi\TopicController;
use App\Http\Controllers\PublicApi\FunFactController;
use App\Http\Controllers\PublicApi\StatsController as PublicStatsController;

// User
use App\Http\Controllers\User\DiscussionController as UserDiscussionController;
use App\Http\Controllers\User\BookmarkController;
use App\Http\Controllers\User\QuizAttemptController;
use App\Http\Controllers\User\ReadingProgressController;

// Admin
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\QuizController as AdminQuizController;
use App\Http\Controllers\Admin\DiscussionController as AdminDiscussionController;
use App\Http\Controllers\Admin\TimelineController as AdminTimelineController;
use App\Http\Controllers\Admin\MapLocationController as AdminMapLocationController;
use App\Http\Controllers\Admin\TopicController as AdminTopicController;
use App\Http\Controllers\Admin\FunFactController as AdminFunFactController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('send-otp',  [AuthController::class, 'sendOtp']);
    Route::post('verify-otp',[AuthController::class, 'verifyOtp']);
    Route::post('register',  [AuthController::class, 'register']);
    Route::post('login',     [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::post('logout',          [AuthController::class, 'logout']);
        Route::post('refresh',         [AuthController::class, 'refresh']);
        Route::get('me',               [AuthController::class, 'me']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::delete('account',       [AuthController::class, 'deleteAccount']);
    });
});

/*
|--------------------------------------------------------------------------
| PUBLIC — Tanpa login
|--------------------------------------------------------------------------
*/
Route::get('eras',            [EraController::class, 'index']);
Route::get('eras/{slug}',     [EraController::class, 'show']);

Route::get('articles',        [ArticleController::class, 'index']);
Route::get('articles/{slug}', [ArticleController::class, 'show']);

Route::get('articles/{slug}/quiz',        [QuizController::class, 'show']);
Route::get('articles/{slug}/discussions', [PublicDiscussionController::class, 'index']);

Route::get('timeline',      [TimelineController::class, 'index']);
Route::get('map-locations', [MapLocationController::class, 'index']);
Route::get('topics',        [TopicController::class, 'index']);
Route::get('stats',         [PublicStatsController::class, 'index']);

Route::get('fun-facts',        [FunFactController::class, 'index']);
Route::get('fun-facts/random', [FunFactController::class, 'random']);

/*
|--------------------------------------------------------------------------
| USER — Login required
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')->group(function () {
    // Discussions
    Route::post('articles/{slug}/discussions', [UserDiscussionController::class, 'store']);

    // Bookmarks
    Route::get('bookmarks',              [BookmarkController::class, 'index']);
    Route::post('bookmarks/{articleId}', [BookmarkController::class, 'toggle']);

    // Quiz Attempts
    Route::post('articles/{slug}/quiz-attempt', [QuizAttemptController::class, 'store']);
    Route::get('quiz-attempts',                 [QuizAttemptController::class, 'history']);

    // Reading Progress
    Route::get('progress',             [ReadingProgressController::class, 'index']);
    Route::get('progress/stats',       [ReadingProgressController::class, 'stats']);
    Route::put('progress/{articleId}', [ReadingProgressController::class, 'update']);
});

/*
|--------------------------------------------------------------------------
| ADMIN — Login + role admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:api', 'admin'])->prefix('admin')->group(function () {
    // Dashboard stats
    Route::get('stats', [AdminDashboardController::class, 'index']);

    // Articles CRUD + image upload
    Route::post('articles/upload-image', [AdminArticleController::class, 'uploadImage']);
    Route::apiResource('articles', AdminArticleController::class);

    // Quizzes CRUD
    Route::apiResource('quizzes', AdminQuizController::class);

    // Discussions moderation
    Route::get('discussions',                [AdminDiscussionController::class, 'index']);
    Route::patch('discussions/{id}/approve', [AdminDiscussionController::class, 'approve']);
    Route::patch('discussions/{id}/reject',  [AdminDiscussionController::class, 'reject']);
    Route::delete('discussions/{id}',        [AdminDiscussionController::class, 'destroy']);

    // Timeline
    Route::post('timeline',        [AdminTimelineController::class, 'store']);
    Route::put('timeline/{id}',    [AdminTimelineController::class, 'update']);
    Route::delete('timeline/{id}', [AdminTimelineController::class, 'destroy']);

    // Map Locations
    Route::get('map-locations',             [AdminMapLocationController::class, 'index']);
    Route::post('map-locations',            [AdminMapLocationController::class, 'store']);
    Route::put('map-locations/{id}',        [AdminMapLocationController::class, 'update']);
    Route::delete('map-locations/{id}',     [AdminMapLocationController::class, 'destroy']);

    // Topics
    Route::post('topics',        [AdminTopicController::class, 'store']);
    Route::put('topics/{id}',    [AdminTopicController::class, 'update']);
    Route::delete('topics/{id}', [AdminTopicController::class, 'destroy']);

    // Fun Facts
    Route::get('fun-facts',          [AdminFunFactController::class, 'index']);
    Route::post('fun-facts',         [AdminFunFactController::class, 'store']);
    Route::put('fun-facts/{id}',     [AdminFunFactController::class, 'update']);
    Route::delete('fun-facts/{id}',  [AdminFunFactController::class, 'destroy']);
});