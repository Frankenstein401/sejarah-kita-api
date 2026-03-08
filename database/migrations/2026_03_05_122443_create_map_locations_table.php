<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('map_locations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('era_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignUuid('article_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');
                
            $table->string('name');
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
            $table->string('year');
            $table->text('description');
            $table->string('article_slug')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_locations');
    }
};