<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('title');

            $table->foreignUuid('era_id')
                ->constrained('eras')
                ->onDelete('cascade');

            $table->string('year');
            $table->text('summary');
            $table->string('hero_image')->nullable();
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('view_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};