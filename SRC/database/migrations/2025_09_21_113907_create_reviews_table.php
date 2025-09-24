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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-5 stars
            $table->text('review_text')->nullable();
            $table->json('images')->nullable(); // Store image paths as JSON
            $table->unsignedInteger('helpful_count')->default(0);
            $table->boolean('is_verified_purchase')->default(false); // User actually borrowed this book
            $table->timestamps();
            
            // Ensure one review per user per book
            $table->unique(['user_id', 'book_id']);
            
            // Index for performance
            $table->index(['book_id', 'rating']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};