<?php

namespace App\Http\Controllers\Jobseeker;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        // Middleware is handled by routes
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get all notifications for the user, ordered by latest first
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Mark all notifications as read
        $user->unreadNotifications->markAsRead();

        return view('jobseeker.notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $user = Auth::user();
        
        $notification = $user->notifications()->findOrFail($id);
        
        // Mark this specific notification as read
        $notification->markAsRead();

        return view('jobseeker.notifications.show', compact('notification'));
    }

    public function markAsRead($id)
    {
        $user = Auth::user();
        
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        
        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json(['success' => true]);
    }

    public function clearAll()
    {
        $user = Auth::user();
        $user->notifications()->delete();

        return response()->json(['success' => true]);
    }

    public function getUnreadCount()
    {
        $user = Auth::user();
        $unreadCount = $user->unreadNotifications->count();

        return response()->json(['count' => $unreadCount]);
    }
}
