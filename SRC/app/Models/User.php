<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Badge;
use App\Models\Event;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
        'experience_points',
        'reading_streak',
        'max_reading_streak',
        'last_reading_date',
        'total_books_read',
        'total_reviews_written',
        'total_helpful_votes',
        'level',
        'achievements',
        'daily_challenges',
        'bio',
        'avatar_path',
        'location',
        'social_links',
        'is_public_profile',
        'reading_preferences',
        'notification_preferences',
        'email_notifications_enabled',
        'last_notification_read_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_reading_date' => 'date',
        'achievements' => 'array',
        'daily_challenges' => 'array',
        'social_links' => 'array',
        'reading_preferences' => 'array',
        'is_public_profile' => 'boolean',
        'notification_preferences' => 'array',
        'email_notifications_enabled' => 'boolean',
        'last_notification_read_at' => 'datetime',
    ];
    // Một người dùng có thể có nhiều lượt mượn
public function loans()
{
    return $this->hasMany(Loan::class);
}

// Một người dùng (admin) có thể thêm nhiều sách
public function books()
{
    return $this->hasMany(Book::class);
}
public function badges()
{
    return $this->belongsToMany(Badge::class, 'badge_user') // Chỉ định rõ tên bảng trung gian
                 ->withTimestamps('unlocked_at', 'unlocked_at'); // Chỉ định tên cột cho cả created_at và updated_at
}

/**
 * Các sự kiện mà người dùng đã đăng ký.
 *
 * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
 */
public function registeredEvents()
{
    return $this->belongsToMany(Event::class, 'event_registrations');
}
public function favoriteBooks()
{
    return $this->belongsToMany(Book::class, 'favorites');
}

public function reviews()
{
    return $this->hasMany(Review::class);
}

public function verifiedReviews()
{
    return $this->hasMany(Review::class)->where('is_verified_purchase', true);
}

// ========== SOCIAL FEATURES ==========

/**
 * Get discussions created by this user.
 */
public function discussions()
{
    return $this->hasMany(Discussion::class);
}

/**
 * Get discussion replies by this user.
 */
public function discussionReplies()
{
    return $this->hasMany(DiscussionReply::class);
}

/**
 * Get reading groups created by this user.
 */
public function createdReadingGroups()
{
    return $this->hasMany(ReadingGroup::class, 'creator_id');
}

/**
 * Get reading groups this user is a member of.
 */
public function readingGroups()
{
    return $this->belongsToMany(ReadingGroup::class, 'reading_group_members')
                ->withPivot(['role', 'joined_at'])
                ->withTimestamps();
}

/**
 * Get discussion likes by this user.
 */
public function discussionLikes()
{
    return $this->hasMany(DiscussionLike::class);
}

/**
 * Get user's social feed (recent activity).
 */
public function getSocialFeed($limit = 20)
{
    $activities = collect();
    
    // Recent reviews
    $reviews = $this->reviews()->with('book')->latest()->take(5)->get();
    foreach ($reviews as $review) {
        $activities->push([
            'type' => 'review',
            'data' => $review,
            'created_at' => $review->created_at,
        ]);
    }
    
    // Recent discussions
    $discussions = $this->discussions()->with('book')->latest()->take(5)->get();
    foreach ($discussions as $discussion) {
        $activities->push([
            'type' => 'discussion',
            'data' => $discussion,
            'created_at' => $discussion->created_at,
        ]);
    }
    
    // Recent loans
    $loans = $this->loans()->with('book')->latest()->take(5)->get();
    foreach ($loans as $loan) {
        $activities->push([
            'type' => 'loan',
            'data' => $loan,
            'created_at' => $loan->created_at,
        ]);
    }
    
    return $activities->sortByDesc('created_at')->take($limit);
}

/**
 * Get user's reading statistics for profile.
 */
public function getProfileStats()
{
    return [
        'total_books_read' => $this->getTotalBooksRead(),
        'total_reviews' => $this->reviews()->count(),
        'total_discussions' => $this->discussions()->count(),
        'total_reading_groups' => $this->readingGroups()->count(),
        'current_streak' => $this->reading_streak,
        'max_streak' => $this->max_reading_streak,
        'level' => $this->level,
        'experience_points' => $this->experience_points,
        'member_since' => $this->created_at,
    ];
}

// ========== NOTIFICATION METHODS ==========

/**
 * Get notifications for this user.
 */
public function notifications()
{
    return $this->hasMany(Notification::class);
}

/**
 * Get unread notifications count.
 */
public function getUnreadNotificationsCount()
{
    return $this->notifications()->unread()->count();
}

/**
 * Get recent notifications.
 */
public function getRecentNotifications($limit = 10)
{
    return $this->notifications()
        ->latest()
        ->take($limit)
        ->get();
}

/**
 * Get notification preferences.
 */
public function getNotificationPreferences()
{
    return $this->notification_preferences ?? [
        'loan_due' => true,
        'loan_overdue' => true,
        'book_returned' => true,
        'new_discussion' => true,
        'new_reply' => true,
        'reading_group_invite' => true,
        'badge_earned' => true,
        'level_up' => true,
    ];
}

/**
 * Update notification preferences.
 */
public function updateNotificationPreferences($preferences)
{
    $this->update(['notification_preferences' => $preferences]);
}

/**
 * Check if user wants to receive specific notification type.
 */
public function wantsNotification($type)
{
    $preferences = $this->getNotificationPreferences();
    return $preferences[$type] ?? true;
}

/**
 * Mark all notifications as read.
 */
public function markAllNotificationsAsRead()
{
    $this->notifications()->unread()->update([
        'is_read' => true,
        'read_at' => now()
    ]);
    
    $this->update(['last_notification_read_at' => now()]);
}

/**
 * Create a notification for this user.
 */
public function createNotification($type, $title, $message, $data = null)
{
    return $this->notifications()->create([
        'type' => $type,
        'title' => $title,
        'message' => $message,
        'data' => $data,
    ]);
}

// ========== GAMIFICATION METHODS ==========

/**
 * Get user's current level based on experience points
 */
public function getCurrentLevel()
{
    return $this->level;
}

/**
 * Calculate experience points needed for next level
 */
public function getExperienceForNextLevel()
{
    return $this->level * 100; // Each level requires 100 more points
}

/**
 * Get progress to next level (0-100)
 */
public function getLevelProgress()
{
    $currentLevelExp = ($this->level - 1) * 100;
    $nextLevelExp = $this->level * 100;
    $progress = $this->experience_points - $currentLevelExp;
    $total = $nextLevelExp - $currentLevelExp;
    
    return $total > 0 ? min(100, ($progress / $total) * 100) : 0;
}

/**
 * Add experience points and check for level up
 */
public function addExperience($points)
{
    $this->increment('experience_points', $points);
    
    // Check for level up
    $newLevel = floor($this->experience_points / 100) + 1;
    if ($newLevel > $this->level) {
        $this->update(['level' => $newLevel]);
        return true; // Level up!
    }
    
    return false;
}

/**
 * Update reading streak
 */
public function updateReadingStreak()
{
    $today = now()->toDateString();
    $yesterday = now()->subDay()->toDateString();
    
    if ($this->last_reading_date === $yesterday) {
        // Consecutive day - increment streak
        $this->increment('reading_streak');
    } elseif ($this->last_reading_date !== $today) {
        // Not consecutive - reset streak
        $this->update(['reading_streak' => 1]);
    }
    
    // Update max streak if current is higher
    if ($this->reading_streak > $this->max_reading_streak) {
        $this->update(['max_reading_streak' => $this->reading_streak]);
    }
    
    $this->update(['last_reading_date' => $today]);
}

/**
 * Get reading streak status
 */
public function getReadingStreakStatus()
{
    $today = now()->toDateString();
    $yesterday = now()->subDay()->toDateString();
    
    if ($this->last_reading_date === $today) {
        return 'active'; // Streak is active today
    } elseif ($this->last_reading_date === $yesterday) {
        return 'at_risk'; // Streak will break if no activity today
    } else {
        return 'broken'; // Streak is broken
    }
}

/**
 * Get total books completed (returned)
 */
public function getTotalBooksRead()
{
    return $this->loans()->where('status', 'returned')->count();
}

/**
 * Get reading statistics
 */
public function getReadingStats()
{
    return [
        'total_books_read' => $this->getTotalBooksRead(),
        'current_streak' => $this->reading_streak,
        'max_streak' => $this->max_reading_streak,
        'total_reviews' => $this->reviews()->count(),
        'total_helpful_votes' => $this->reviews()->sum('helpful_count'),
        'level' => $this->level,
        'experience_points' => $this->experience_points,
        'level_progress' => $this->getLevelProgress(),
    ];
}

/**
 * Get daily challenges
 */
public function getDailyChallenges()
{
    $challenges = $this->daily_challenges ?? [];
    $today = now()->toDateString();
    
    // If no challenges for today, generate new ones
    if (!isset($challenges[$today])) {
        $challenges[$today] = $this->generateDailyChallenges();
        $this->update(['daily_challenges' => $challenges]);
    }
    
    return $challenges[$today];
}

/**
 * Generate daily challenges
 */
private function generateDailyChallenges()
{
    return [
        'read_book' => [
            'title' => 'Đọc một cuốn sách',
            'description' => 'Mượn và trả một cuốn sách hôm nay',
            'target' => 1,
            'current' => 0,
            'completed' => false,
            'reward' => 50,
        ],
        'write_review' => [
            'title' => 'Viết đánh giá',
            'description' => 'Viết một đánh giá cho cuốn sách bạn đã đọc',
            'target' => 1,
            'current' => 0,
            'completed' => false,
            'reward' => 30,
        ],
        'helpful_review' => [
            'title' => 'Đánh giá hữu ích',
            'description' => 'Nhận 3 lượt vote hữu ích cho đánh giá của bạn',
            'target' => 3,
            'current' => 0,
            'completed' => false,
            'reward' => 40,
        ],
    ];
}

/**
 * Update daily challenge progress
 */
public function updateDailyChallenge($challengeKey, $increment = 1)
{
    $challenges = $this->daily_challenges ?? [];
    $today = now()->toDateString();
    
    if (isset($challenges[$today][$challengeKey])) {
        $challenges[$today][$challengeKey]['current'] += $increment;
        
        // Check if completed
        if ($challenges[$today][$challengeKey]['current'] >= $challenges[$today][$challengeKey]['target']) {
            $challenges[$today][$challengeKey]['completed'] = true;
            
            // Award experience points
            $this->addExperience($challenges[$today][$challengeKey]['reward']);
        }
        
        $this->update(['daily_challenges' => $challenges]);
    }
}

/**
 * Get leaderboard position
 */
public function getLeaderboardPosition()
{
    return User::where('experience_points', '>', $this->experience_points)->count() + 1;
}

/**
 * Get top users for leaderboard
 */
public static function getLeaderboard($limit = 10)
{
    return self::orderBy('experience_points', 'desc')
        ->orderBy('max_reading_streak', 'desc')
        ->orderBy('total_books_read', 'desc')
        ->take($limit)
        ->get();
}

}
