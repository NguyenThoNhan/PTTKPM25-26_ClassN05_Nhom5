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
        // Add notification preferences to users table
        Schema::table('users', function (Blueprint $table) {
            $table->json('notification_preferences')->nullable(); // Store notification preferences as JSON
            $table->boolean('email_notifications_enabled')->default(true); // Enable/disable email notifications
            $table->timestamp('last_notification_read_at')->nullable(); // Track last read notification
        });

        // Create notifications table
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // loan_due, loan_overdue, book_returned, new_discussion, etc.
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Additional data (book_id, loan_id, etc.)
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamp('email_sent_at')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['user_id', 'is_read', 'created_at']);
            $table->index(['type', 'created_at']);
            $table->index(['email_sent', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notification_preferences',
                'email_notifications_enabled',
                'last_notification_read_at'
            ]);
        });
    }
};