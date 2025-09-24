<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscussionReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'discussion_id',
        'user_id',
        'parent_id',
        'content',
        'likes_count',
        'is_solution',
    ];

    protected $casts = [
        'is_solution' => 'boolean',
    ];

    /**
     * Get the discussion this reply belongs to.
     */
    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    /**
     * Get the user who wrote this reply.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent reply (for nested replies).
     */
    public function parent()
    {
        return $this->belongsTo(DiscussionReply::class, 'parent_id');
    }

    /**
     * Get child replies (nested replies).
     */
    public function children()
    {
        return $this->hasMany(DiscussionReply::class, 'parent_id');
    }

    /**
     * Get all likes for this reply.
     */
    public function likes()
    {
        return $this->morphMany(DiscussionLike::class, 'likeable');
    }

    /**
     * Check if user has liked this reply.
     */
    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Scope for top-level replies.
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope for solutions.
     */
    public function scopeSolutions($query)
    {
        return $query->where('is_solution', true);
    }

    /**
     * Toggle like for user.
     */
    public function toggleLike($userId)
    {
        $like = $this->likes()->where('user_id', $userId)->first();
        
        if ($like) {
            $like->delete();
            $this->decrement('likes_count');
            return false; // Unliked
        } else {
            $this->likes()->create(['user_id' => $userId]);
            $this->increment('likes_count');
            return true; // Liked
        }
    }

    /**
     * Mark as solution.
     */
    public function markAsSolution()
    {
        // Unmark other solutions for this discussion
        $this->discussion->replies()->update(['is_solution' => false]);
        
        // Mark this as solution
        $this->update(['is_solution' => true]);
    }
}