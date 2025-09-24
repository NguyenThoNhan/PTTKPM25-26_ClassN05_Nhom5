<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class GamificationBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $badges = [
            // Reading badges
            [
                'name' => 'Tân Binh',
                'description' => 'Mượn cuốn sách đầu tiên',
                'icon_path' => 'badges/rookie.png'
            ],
            [
                'name' => 'Độc Giả Chăm Chỉ',
                'description' => 'Mượn 5 cuốn sách',
                'icon_path' => 'badges/dedicated-reader.png'
            ],
            [
                'name' => 'Thư Viện Gia',
                'description' => 'Mượn 20 cuốn sách',
                'icon_path' => 'badges/library-master.png'
            ],
            [
                'name' => 'Nhà Phê Bình',
                'description' => 'Viết 3 đánh giá',
                'icon_path' => 'badges/critic.png'
            ],
            [
                'name' => 'Chuyên Gia Đánh Giá',
                'description' => 'Viết 10 đánh giá',
                'icon_path' => 'badges/review-expert.png'
            ],
            // Streak badges
            [
                'name' => 'Streak Master',
                'description' => 'Chuỗi đọc 7 ngày liên tiếp',
                'icon_path' => 'badges/streak-master.png'
            ],
            [
                'name' => 'Fire Streak',
                'description' => 'Chuỗi đọc 30 ngày liên tiếp',
                'icon_path' => 'badges/fire-streak.png'
            ],
            [
                'name' => 'Legendary Reader',
                'description' => 'Chuỗi đọc 100 ngày liên tiếp',
                'icon_path' => 'badges/legendary-reader.png'
            ],
            // Level badges
            [
                'name' => 'Level Up',
                'description' => 'Đạt level 5',
                'icon_path' => 'badges/level-up.png'
            ],
            [
                'name' => 'Master Reader',
                'description' => 'Đạt level 10',
                'icon_path' => 'badges/master-reader.png'
            ],
            // Special badges
            [
                'name' => 'Digital Signature Expert',
                'description' => 'Mượn 5 tài liệu online',
                'icon_path' => 'badges/digital-expert.png'
            ],
            [
                'name' => 'Helpful Reviewer',
                'description' => 'Nhận 10 lượt vote hữu ích',
                'icon_path' => 'badges/helpful-reviewer.png'
            ],
            [
                'name' => 'Early Bird',
                'description' => 'Đăng nhập 7 ngày liên tiếp',
                'icon_path' => 'badges/early-bird.png'
            ],
            [
                'name' => 'Social Reader',
                'description' => 'Tham gia 5 sự kiện thư viện',
                'icon_path' => 'badges/social-reader.png'
            ],
            [
                'name' => 'Speed Reader',
                'description' => 'Đọc 3 cuốn sách trong 1 ngày',
                'icon_path' => 'badges/speed-reader.png'
            ]
        ];

        foreach ($badges as $badgeData) {
            Badge::updateOrCreate(
                ['name' => $badgeData['name']],
                $badgeData
            );
        }
    }
}