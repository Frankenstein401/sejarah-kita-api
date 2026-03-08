<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('article_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignUuid('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignUuid('parent_id')
                ->nullable()
                ->constrained('discussions')
                ->onDelete('cascade');

            $table->text('message');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            $table->index(['article_id', 'is_approved']);
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discussions');
    }
};