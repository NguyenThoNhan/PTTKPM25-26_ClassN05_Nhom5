<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'rating',
        'review_text',
        'images',
        'helpful_count',
        'is_verified_purchase',
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified_purchase' => 'boolean',
    ];

    /**
     * Get the user who wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book being reviewed.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Scope for verified purchases only.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    /**
     * Scope for specific rating.
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope for recent reviews.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get formatted rating stars.
     */
    public function getStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Get helpful percentage.
     */
    public function getHelpfulPercentageAttribute()
    {
        if ($this->helpful_count == 0) {
            return 0;
        }
        
        // This would need a separate table for helpful votes
        // For now, return a simple calculation
        return min(100, $this->helpful_count * 10);
    }
}