<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserNotification;
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


    public function createFollowNotification(User $user, User $follower)
    {
        $user->notifications()->create([
            'message' => "{$follower->username} followed you",
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id, // ✅ Ensure notifiable_id is set correctly
            'notification_by' => $follower->id,
            'sent_at' => now(),
        ]);
    }
}
