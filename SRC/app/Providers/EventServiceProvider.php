<?php

namespace App\Providers;

// --- CÁC DÒNG USE CẦN THIẾT ---
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

// Thêm use cho Subscriber của chúng ta
use App\Listeners\GamificationSubscriber;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     * (Các ánh xạ từ Sự kiện tới Trình lắng nghe cho ứng dụng.)
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Đây là ánh xạ mặc định của Laravel Breeze
        // Khi một người dùng đăng ký (Registered event), Laravel sẽ gửi email xác thực
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     * (Các lớp Subscriber cần đăng ký.)
     *
     * Đây là nơi chúng ta sẽ đăng ký các listener cho hệ thống Gamification.
     * Một Subscriber là một class có thể lắng nghe nhiều sự kiện khác nhau.
     *
     * @var array
     */
    protected $subscribe = [
        // Đăng ký GamificationSubscriber. Laravel sẽ tự động tìm các phương thức
        // 'handle' bên trong class này để xử lý các sự kiện tương ứng.
         GamificationSubscriber::class,
    ];

    /**
     * Register any events for your application.
     * (Đăng ký bất kỳ sự kiện nào cho ứng dụng của bạn.)
     */
    public function boot(): void
    {
        // Phương thức này thường để trống trừ khi bạn có nhu cầu đăng ký sự kiện phức tạp hơn.
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     * (Xác định xem các sự kiện và trình lắng nghe có nên được tự động phát hiện hay không.)
     *
     * Để false để chúng ta có thể kiểm soát hoàn toàn việc đăng ký một cách tường minh.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}