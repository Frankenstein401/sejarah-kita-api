<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('quiz_id')
                ->constrained()
                ->onDelete('cascade');

            $table->text('question');
            $table->json('options');
            $table->unsignedTinyInteger('correct_index');
            $table->text('explanation')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};