<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignUuid('article_id')
                ->constrained()
                ->onDelete('cascade');
                
            $table->unsignedTinyInteger('score');
            $table->unsignedTinyInteger('total');
            $table->unsignedInteger('time_seconds')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'article_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};