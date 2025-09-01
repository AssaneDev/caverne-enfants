<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->json('name');
            $table->string('slug')->unique();
            $table->json('description')->nullable();
            $table->ulid('cover_media_id')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamps();
            
            $table->index('featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
