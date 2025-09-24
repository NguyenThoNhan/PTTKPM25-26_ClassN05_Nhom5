<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event; // <-- Thêm use này
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Hiển thị danh sách các sự kiện.
     */
    public function index()
    {
        $events = Event::withCount('attendees')->latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Hiển thị form để tạo sự kiện mới.
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Lưu sự kiện mới vào CSDL.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'max_attendees' => 'nullable|integer|min:1',
        ]);

        // Gán ID của admin đang tạo sự kiện
        $validated['user_id'] = auth()->id();

        Event::create($validated);

        return redirect()->route('admin.events.index')->with('success', 'Tạo sự kiện thành công!');
    }

    /**
     * Hiển thị thông tin chi tiết của một sự kiện và danh sách người đăng ký.
     */
    public function show(Event $event)
    {
        // Eager load 'attendees' để tối ưu hóa truy vấn
        $event->load('attendees');
        return view('admin.events.show', compact('event'));
    }

    /**
     * Hiển thị form để chỉnh sửa sự kiện.
     */
    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Cập nhật thông tin sự kiện trong CSDL.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after_or_equal:start_time',
            'max_attendees' => 'nullable|integer|min:1',
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')->with('success', 'Cập nhật sự kiện thành công!');
    }

    /**
     * Xóa sự kiện khỏi CSDL.
     */
    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Xóa sự kiện thành công!');
    }
}