<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'title',
        'content',
        'type',
        'tags',
        'views_count',
        'likes_count',
        'replies_count',
        'is_pinned',
        'is_locked',
    ];

    protected $casts = [
        'tags' => 'array',
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
    ];

    /**
     * Get the user who created the discussion.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book this discussion is about.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get all replies for this discussion.
     */
    public function replies()
    {
        return $this->hasMany(DiscussionReply::class);
    }

    /**
     * Get top-level replies (not nested).
     */
    public function topLevelReplies()
    {
        return $this->hasMany(DiscussionReply::class)->whereNull('parent_id');
    }

    /**
     * Get all likes for this discussion.
     */
    public function likes()
    {
        return $this->morphMany(DiscussionLike::class, 'likeable');
    }

    /**
     * Check if user has liked this discussion.
     */
    public function isLikedBy($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Scope for general discussions.
     */
    public function scopeGeneral($query)
    {
        return $query->where('type', 'general');
    }

    /**
     * Scope for book discussions.
     */
    public function scopeBookDiscussions($query)
    {
        return $query->where('type', 'book_discussion');
    }

    /**
     * Scope for reading group discussions.
     */
    public function scopeReadingGroup($query)
    {
        return $query->where('type', 'reading_group');
    }

    /**
     * Scope for pinned discussions.
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Scope for public discussions.
     */
    public function scopePublic($query)
    {
        return $query->where('is_locked', false);
    }

    /**
     * Get formatted tags.
     */
    public function getFormattedTagsAttribute()
    {
        return $this->tags ? implode(', ', $this->tags) : '';
    }

    /**
     * Get excerpt of content.
     */
    public function getExcerptAttribute()
    {
        return Str::limit(strip_tags($this->content), 150);
    }

    /**
     * Increment views count.
     */
    public function incrementViews()
    {
        $this->increment('views_count');
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
}