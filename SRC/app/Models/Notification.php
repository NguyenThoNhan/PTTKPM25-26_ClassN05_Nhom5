<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at',
        'email_sent',
        'email_sent_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'email_sent' => 'boolean',
        'read_at' => 'datetime',
        'email_sent_at' => 'datetime',
    ];

    /**
     * Get the user who owns this notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications.
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Scope for specific notification type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for notifications that need email sending.
     */
    public function scopeNeedsEmail($query)
    {
        return $query->where('email_sent', false)
                    ->where('created_at', '>=', now()->subHours(24)); // Only send emails for notifications less than 24 hours old
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        }
    }

    /**
     * Mark notification as email sent.
     */
    public function markEmailSent()
    {
        $this->update([
            'email_sent' => true,
            'email_sent_at' => now()
        ]);
    }

    /**
     * Get notification icon based on type.
     */
    public function getIconAttribute()
    {
        return match($this->type) {
            'loan_due' => 'fas fa-clock text-yellow-500',
            'loan_overdue' => 'fas fa-exclamation-triangle text-red-500',
            'book_returned' => 'fas fa-check-circle text-green-500',
            'new_discussion' => 'fas fa-comments text-blue-500',
            'new_reply' => 'fas fa-reply text-purple-500',
            'reading_group_invite' => 'fas fa-users text-indigo-500',
            'badge_earned' => 'fas fa-medal text-yellow-500',
            'level_up' => 'fas fa-star text-orange-500',
            default => 'fas fa-bell text-gray-500'
        };
    }

    /**
     * Get notification color based on type.
     */
    public function getColorAttribute()
    {
        return match($this->type) {
            'loan_due' => 'yellow',
            'loan_overdue' => 'red',
            'book_returned' => 'green',
            'new_discussion' => 'blue',
            'new_reply' => 'purple',
            'reading_group_invite' => 'indigo',
            'badge_earned' => 'yellow',
            'level_up' => 'orange',
            default => 'gray'
        };
    }

    /**
     * Get formatted time ago.
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}