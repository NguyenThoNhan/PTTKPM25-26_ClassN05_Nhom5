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
        // Add social fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable(); // User bio/description
            $table->string('avatar_path')->nullable(); // Profile avatar
            $table->string('location')->nullable(); // User location
            $table->json('social_links')->nullable(); // Social media links
            $table->boolean('is_public_profile')->default(true); // Public/private profile
            $table->json('reading_preferences')->nullable(); // Reading preferences
        });

        // Create discussions table
        Schema::create('discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('type')->default('general'); // general, book_discussion, reading_group
            $table->json('tags')->nullable(); // Discussion tags
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('replies_count')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['type', 'created_at']);
            $table->index(['book_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });

        // Create discussion_replies table
        Schema::create('discussion_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discussion_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('discussion_replies')->onDelete('cascade');
            $table->text('content');
            $table->unsignedInteger('likes_count')->default(0);
            $table->boolean('is_solution')->default(false); // Mark as solution
            $table->timestamps();
            
            // Indexes
            $table->index(['discussion_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });

        // Create discussion_likes table
        Schema::create('discussion_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('likeable'); // Can like discussions or replies
            $table->timestamps();
            
            // Prevent duplicate likes
            $table->unique(['user_id', 'likeable_id', 'likeable_type']);
        });

        // Create reading_groups table (using events table structure)
        Schema::create('reading_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->string('cover_image_path')->nullable();
            $table->string('category')->default('general'); // fiction, non-fiction, etc.
            $table->unsignedInteger('max_members')->default(50);
            $table->boolean('is_public')->default(true);
            $table->boolean('is_active')->default(true);
            $table->json('rules')->nullable(); // Group rules
            $table->timestamps();
        });

        // Create reading_group_members table
        Schema::create('reading_group_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reading_group_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->default('member'); // member, moderator, admin
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();
            
            // Prevent duplicate memberships
            $table->unique(['reading_group_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_group_members');
        Schema::dropIfExists('reading_groups');
        Schema::dropIfExists('discussion_likes');
        Schema::dropIfExists('discussion_replies');
        Schema::dropIfExists('discussions');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'bio',
                'avatar_path',
                'location',
                'social_links',
                'is_public_profile',
                'reading_preferences'
            ]);
        });
    }
};