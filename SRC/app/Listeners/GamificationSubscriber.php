<?php

namespace App\Listeners;

// --- CÁC DÒNG USE CẦN THIẾT ---
use App\Events\BookReturned;
use App\Models\User;
use App\Models\Badge;
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

            // 1. Cộng điểm kinh nghiệm
            $user->increment('experience_points', self::POINTS_PER_RETURN);

            // 2. Kiểm tra và trao huy hiệu
            $this->checkAndAwardBadges($user);

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
        // Lấy số sách đã trả thành công của user
        // Chúng ta có thể cache kết quả này để tối ưu performance sau này
        $returnedBooksCount = $user->loans()->where('status', 'returned')->count();

        // Lấy danh sách ID các huy hiệu mà user đã có để tránh truy vấn lại
        $currentUserBadgeIds = $user->badges()->pluck('badges.id');

        // Huy hiệu "Tân Binh" (mượn sách đầu tiên)
        if ($returnedBooksCount >= 1) {
            $badge = Badge::where('name', 'Tân Binh')->first();
            if ($badge && !$currentUserBadgeIds->contains($badge->id)) {
                $user->badges()->attach($badge->id);
            }
        }

        // Huy hiệu "Độc Giả Chăm Chỉ" (mượn 5 cuốn)
        if ($returnedBooksCount >= 5) {
            $badge = Badge::where('name', 'Độc Giả Chăm Chỉ')->first();
            if ($badge && !$currentUserBadgeIds->contains($badge->id)) {
                $user->badges()->attach($badge->id);
            }
        }
        
        // Thêm các điều kiện huy hiệu khác ở đây...
        // Ví dụ: Huy hiệu "Nhà Phê Bình" khi viết 3 reviews
        // $reviewCount = $user->reviews()->count();
        // if ($reviewCount >= 3) { ... }
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