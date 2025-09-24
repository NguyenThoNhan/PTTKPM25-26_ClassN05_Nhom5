<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications
     */
    public function index()
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = $user->getUnreadNotificationsCount();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        // Check if user owns this notification
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->markAllNotificationsAsRead();

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get unread notifications count (AJAX)
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->getUnreadNotificationsCount();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Get recent notifications (AJAX)
     */
    public function getRecent()
    {
        $notifications = Auth::user()->getRecentNotifications(5);

        return response()->json([
            'success' => true,
            'notifications' => $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'is_read' => $notification->is_read,
                    'icon' => $notification->icon,
                    'color' => $notification->color,
                    'time_ago' => $notification->time_ago,
                    'created_at' => $notification->created_at->toISOString(),
                ];
            })
        ]);
    }

    /**
     * Show notification preferences
     */
    public function preferences()
    {
        $user = Auth::user();
        $preferences = $user->getNotificationPreferences();

        return view('notifications.preferences', compact('user', 'preferences'));
    }

    /**
     * Update notification preferences
     */
    public function updatePreferences(Request $request)
    {
        $request->validate([
            'email_notifications_enabled' => 'boolean',
            'preferences' => 'array',
            'preferences.*' => 'boolean',
        ]);

        $user = Auth::user();
        
        // Update email notifications setting
        $user->update([
            'email_notifications_enabled' => $request->boolean('email_notifications_enabled')
        ]);

        // Update notification preferences
        if ($request->has('preferences')) {
            $user->updateNotificationPreferences($request->preferences);
        }

        return redirect()->back()->with('success', 'Cài đặt thông báo đã được cập nhật!');
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification)
    {
        // Check if user owns this notification
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }

    /**
     * Clear all notifications
     */
    public function clearAll()
    {
        Auth::user()->notifications()->delete();

        return response()->json([
            'success' => true,
            'message' => 'All notifications cleared'
        ]);
    }
}