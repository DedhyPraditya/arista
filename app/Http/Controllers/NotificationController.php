<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Get all notifications for current user
    public function index()
    {
        $notifications = Notification::forUser(Auth::id())
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    // Get unread notifications for dropdown
    public function getUnread()
    {
        $notifications = Notification::forUser(Auth::id())
            ->unread()
            ->latest()
            ->limit(5)
            ->get();

        $unreadCount = Notification::forUser(Auth::id())
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    // Mark single notification as read
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);

        // Check if notification belongs to user
        if ($notification->user_id && $notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    // Mark all notifications as read
    public function markAllAsRead()
    {
        Notification::forUser(Auth::id())
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);

        return response()->json(['success' => true]);
    }

    // Delete notification
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);

        // Check if notification belongs to user
        if ($notification->user_id && $notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return response()->json(['success' => true]);
    }
}
