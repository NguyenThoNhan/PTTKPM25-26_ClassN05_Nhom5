<?php

namespace App\Listeners;

// --- CÁC DÒNG USE CẦN THIẾT ---
use App\Events\BookReturned;
use App\Models\User;
use App\Models\Badge;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue; // Để xử lý tác vụ dưới nền (tùy chọn nâng cao)
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Log; // Để ghi log khi cần debug

class GamificationSubscriber
{
    /**
     * Điểm kinh nghiệm được cộng cho mỗi lần trả sách.
     */
    const POINTS_PER_RETURN = 10;

    /**
     * Xử lý sự kiện sau khi người dùng trả sách.
     *
     * @param \App\Events\BookReturned $event
     * @return void
     */
    public function handleBookReturned(BookReturned $event): void
    {
        try {
            $user = $event->loan->user;

            // 1. Cập nhật reading streak
            $user->updateReadingStreak();

            // 2. Cộng điểm kinh nghiệm và kiểm tra level up
            $levelUp = $user->addExperience(self::POINTS_PER_RETURN);

            // 3. Cập nhật total books read
            $user->increment('total_books_read');

            // 4. Cập nhật daily challenge progress
            $user->updateDailyChallenge('read_book');

            // 5. Kiểm tra và trao huy hiệu
            $this->checkAndAwardBadges($user);

            // 6. Log level up if occurred
            if ($levelUp) {
                Log::info('User leveled up!', [
                    'user_id' => $user->id,
                    'new_level' => $user->level,
                    'experience_points' => $user->experience_points
                ]);
                
                // Send level up notification
                $notificationService = new NotificationService();
                $notificationService->sendLevelUpNotification($user, $user->level);
            }

        } catch (\Exception $e) {
            // Ghi lại lỗi nếu có vấn đề xảy ra trong quá trình xử lý gamification
            Log::error('Gamification Error on BookReturned: ' . $e->getMessage(), [
                'user_id' => $event->loan->user_id,
                'loan_id' => $event->loan->id
            ]);
        }
    }

    /**
     * Kiểm tra các điều kiện và trao huy hiệu cho người dùng.
     *
     * @param \App\Models\User $user
     * @return void
     */
    private function checkAndAwardBadges(User $user): void
    {
        // Lấy thống kê của user
        $returnedBooksCount = $user->loans()->where('status', 'returned')->count();
        $reviewCount = $user->reviews()->count();
        $currentStreak = $user->reading_streak;
        $maxStreak = $user->max_reading_streak;
        $level = $user->level;

        // Lấy danh sách ID các huy hiệu mà user đã có để tránh truy vấn lại
        $currentUserBadgeIds = $user->badges()->pluck('badges.id');

        // Huy hiệu "Tân Binh" (mượn sách đầu tiên)
        if ($returnedBooksCount >= 1) {
            $this->awardBadge($user, 'Tân Binh', $currentUserBadgeIds);
        }

        // Huy hiệu "Độc Giả Chăm Chỉ" (mượn 5 cuốn)
        if ($returnedBooksCount >= 5) {
            $this->awardBadge($user, 'Độc Giả Chăm Chỉ', $currentUserBadgeIds);
        }

        // Huy hiệu "Thư Viện Gia" (mượn 20 cuốn)
        if ($returnedBooksCount >= 20) {
            $this->awardBadge($user, 'Thư Viện Gia', $currentUserBadgeIds);
        }

        // Huy hiệu "Nhà Phê Bình" (viết 3 reviews)
        if ($reviewCount >= 3) {
            $this->awardBadge($user, 'Nhà Phê Bình', $currentUserBadgeIds);
        }

        // Huy hiệu "Chuyên Gia Đánh Giá" (viết 10 reviews)
        if ($reviewCount >= 10) {
            $this->awardBadge($user, 'Chuyên Gia Đánh Giá', $currentUserBadgeIds);
        }

        // Huy hiệu "Streak Master" (chuỗi đọc 7 ngày)
        if ($currentStreak >= 7) {
            $this->awardBadge($user, 'Streak Master', $currentUserBadgeIds);
        }

        // Huy hiệu "Fire Streak" (chuỗi đọc 30 ngày)
        if ($currentStreak >= 30) {
            $this->awardBadge($user, 'Fire Streak', $currentUserBadgeIds);
        }

        // Huy hiệu "Legendary Reader" (chuỗi đọc 100 ngày)
        if ($maxStreak >= 100) {
            $this->awardBadge($user, 'Legendary Reader', $currentUserBadgeIds);
        }

        // Huy hiệu "Level Up" (đạt level 5)
        if ($level >= 5) {
            $this->awardBadge($user, 'Level Up', $currentUserBadgeIds);
        }

        // Huy hiệu "Master Reader" (đạt level 10)
        if ($level >= 10) {
            $this->awardBadge($user, 'Master Reader', $currentUserBadgeIds);
        }

        // Huy hiệu "Digital Signature Expert" (mượn 5 tài liệu online)
        $onlineBooksCount = $user->loans()->whereHas('book', function($query) {
            $query->where('type', 'online');
        })->where('status', 'returned')->count();
        
        if ($onlineBooksCount >= 5) {
            $this->awardBadge($user, 'Digital Signature Expert', $currentUserBadgeIds);
        }
    }

    /**
     * Award a badge to user if not already owned
     */
    private function awardBadge(User $user, string $badgeName, $currentUserBadgeIds): void
    {
        $badge = Badge::where('name', $badgeName)->first();
        if ($badge && !$currentUserBadgeIds->contains($badge->id)) {
            $user->badges()->attach($badge->id);
            
            // Log badge award
            Log::info('Badge awarded to user', [
                'user_id' => $user->id,
                'badge_name' => $badgeName,
                'badge_id' => $badge->id
            ]);
            
            // Send badge earned notification
            $notificationService = new NotificationService();
            $notificationService->sendBadgeEarnedNotification($user, $badgeName);
        }
    }

    /**
     * Đăng ký các listener với dispatcher.
     *
     * Đây là phương thức mà EventServiceProvider sẽ gọi để biết class này
     * lắng nghe những sự kiện nào và xử lý bằng phương thức nào.
     *
     * @param \Illuminate\Events\Dispatcher $events
     * @return void
     */
    public function subscribe(Dispatcher $events): void
    {
        // Khi sự kiện BookReturned được phát ra, hãy gọi phương thức handleBookReturned
        $events->listen(
            BookReturned::class,
            [GamificationSubscriber::class, 'handleBookReturned']
        );

        // Sau này nếu có sự kiện mới, bạn chỉ cần thêm vào đây. Ví dụ:
        // $events->listen(
        //     ReviewPosted::class,
        //     [GamificationSubscriber::class, 'handleReviewPosted']
        // );
    }
}