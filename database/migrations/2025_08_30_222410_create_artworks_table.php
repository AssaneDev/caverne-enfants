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
        Schema::create('artworks', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('sku')->unique();
            $table->json('title');
            $table->string('slug')->unique();
            $table->ulid('artist_id')->nullable();
            $table->ulid('collection_id')->nullable();
            $table->integer('year')->nullable();
            $table->json('medium')->nullable();
            $table->string('dimensions')->nullable();
            $table->integer('price_cents');
            $table->string('currency', 3)->default('EUR');
            $table->string('status')->default('draft');
            $table->timestamp('reserved_until')->nullable();
            $table->boolean('featured')->default(false);
            $table->boolean('on_home')->default(false);
            $table->integer('weight_grams')->nullable();
            $table->string('shipping_class')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('collection_id');
            $table->index(['featured', 'status']);
            $table->index(['on_home', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
};
