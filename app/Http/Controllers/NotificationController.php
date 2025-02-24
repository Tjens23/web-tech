<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $userNotification = UserNotification::where('notification_id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($userNotification) {
            $userNotification->update(['read_status' => 'read']);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }


    public function createFollowNotification($followedUser, $follower)
    {
        $notification = Notifications::create([
            'message' => $follower->username . ' followed you',
            'sent_at' => now(),
            'notifiable_type' => get_class($followedUser),
            'notification_by' => $follower->id  // Changed to follower's ID
        ]);

        UserNotification::create([
            'user_id' => $followedUser->id,
            'notification_id' => $notification->id,
            'read_status' => 'unread'
        ]);

        return $notification;
    }
}
