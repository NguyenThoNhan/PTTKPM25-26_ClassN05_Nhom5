<?php

namespace App\Events;

// --- CÁC DÒNG USE CẦN THIẾT ---
use App\Models\Loan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookReturned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Thuộc tính công khai (public property) để chứa thông tin về lượt mượn.
     * Bất kỳ Listener nào nhận được sự kiện này đều có thể truy cập qua $event->loan.
     *
     * @var \App\Models\Loan
     */
    public $loan;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Loan $loan
     * @return void
     */
    public function __construct(Loan $loan)
    {
        // Khi sự kiện được tạo, gán đối tượng Loan được truyền vào cho thuộc tính public.
        $this->loan = $loan;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Phần này dùng cho broadcasting thời gian thực (real-time).
        // Chúng ta chưa dùng đến nên có thể để trống.
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}