<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\User;

class EventPageController extends Controller
{
    /**
     * Hiển thị danh sách các sự kiện sắp diễn ra.
     */
    public function index()
    {
        $events = Event::withCount('attendees')
                       ->where('start_time', '>=', now()) // Chỉ lấy sự kiện chưa bắt đầu
                       ->orderBy('start_time', 'asc')
                       ->paginate(9);

        return view('events.index', compact('events'));
    }

    /**
     * Hiển thị chi tiết một sự kiện.
     */
    public function show(Event $event)
    {
        $isRegistered = false;
        // Kiểm tra user đã đăng nhập chưa
        if (auth()->check()) {
            // Kiểm tra xem user này đã đăng ký sự kiện này chưa
            /** @var \App\Models\User $user */
$user = auth()->user();
$isRegistered = $user->registeredEvents()->where('event_id', $event->id)->exists();

        }

        // Tính số chỗ còn lại (-1 nghĩa là không giới hạn)
        $remainingSlots = $event->max_attendees ? ($event->max_attendees - $event->attendees()->count()) : -1;

        return view('events.show', compact('event', 'isRegistered', 'remainingSlots'));
    }

    /**
     * Xử lý việc đăng ký sự kiện của người dùng.
     */
    public function register(Event $event)
    {
        $user = auth()->user();

        // Kiểm tra xem sự kiện đã bắt đầu chưa
        if ($event->start_time < now()) {
             return back()->with('error', 'Sự kiện đã diễn ra, không thể đăng ký.');
        }

        // Kiểm tra đã đăng ký chưa
/** @var \App\Models\User $user */
$user = auth()->user();

// Kiểm tra đã đăng ký chưa
if ($user->registeredEvents()->where('event_id', $event->id)->exists()) {
    return back()->with('error', 'Bạn đã đăng ký sự kiện này rồi.');
}

        // Kiểm tra còn chỗ không
        if ($event->max_attendees && $event->attendees()->count() >= $event->max_attendees) {
            return back()->with('error', 'Sự kiện đã hết chỗ.');
        }
        
        // Đăng ký cho user (tạo bản ghi trong bảng trung gian)
        $user->registeredEvents()->attach($event->id);

        return back()->with('success', 'Đăng ký tham gia sự kiện thành công!');
    }

    /**
     * Xử lý việc hủy đăng ký sự kiện của người dùng.
     */
    public function unregister(Event $event)
    {
        $user = auth()->user();

        // Kiểm tra xem sự kiện đã bắt đầu chưa
         if ($event->start_time < now()) {
             return back()->with('error', 'Sự kiện đã diễn ra, không thể hủy đăng ký.');
        }

/** @var \App\Models\User $user */
$user = auth()->user();

// Hủy đăng ký (xóa bản ghi khỏi bảng trung gian)
$user->registeredEvents()->detach($event->id);


        return back()->with('success', 'Hủy đăng ký sự kiện thành công.');
    }
}