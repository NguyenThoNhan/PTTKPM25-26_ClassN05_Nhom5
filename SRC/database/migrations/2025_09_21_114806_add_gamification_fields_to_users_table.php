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
        Schema::table('users', function (Blueprint $table) {
            // Gamification fields
            $table->unsignedInteger('reading_streak')->default(0); // Current reading streak in days
            $table->unsignedInteger('max_reading_streak')->default(0); // Best reading streak ever
            $table->date('last_reading_date')->nullable(); // Last day user borrowed/returned a book
            $table->unsignedInteger('total_books_read')->default(0); // Total books completed
            $table->unsignedInteger('total_reviews_written')->default(0); // Total reviews written
            $table->unsignedInteger('total_helpful_votes')->default(0); // Total helpful votes received
            $table->unsignedInteger('level')->default(1); // User level based on experience
            $table->json('achievements')->nullable(); // Store achievement data as JSON
            $table->json('daily_challenges')->nullable(); // Store daily challenge progress
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'reading_streak',
                'max_reading_streak', 
                'last_reading_date',
                'total_books_read',
                'total_reviews_written',
                'total_helpful_votes',
                'level',
                'achievements',
                'daily_challenges'
            ]);
        });
    }
};