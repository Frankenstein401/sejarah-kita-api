<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timeline_events', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('era_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('year');
            $table->string('title');
            $table->text('description');
            $table->text('detail')->nullable();
            $table->json('significance')->nullable();
            $table->json('figures')->nullable();
            $table->string('image_url')->nullable();
            $table->string('image_caption')->nullable();
            $table->string('article_slug')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timeline_events');
    }
};