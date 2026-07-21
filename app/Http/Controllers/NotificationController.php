<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Get the logged-in user from the custom session.
     */
    private function getAuthenticatedUser()
    {
        if (!session()->has('user')) {
            return null;
        }

        $email = session('user.username');
        return User::where('email', $email)->first();
    }

    /**
     * Get notifications for the authenticated user.
     */
    public function index()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        // format created_at diff for human in a simple way
        $formattedNotifications = $notifications->map(function ($n) {
            return [
                'id' => $n->id,
                'title' => $n->title,
                'message' => $n->message,
                'type' => $n->type,
                'read_at' => $n->read_at,
                'time_ago' => $n->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'notifications' => $formattedNotifications,
            'unreadCount' => $unreadCount
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead($id)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $notification = Notification::where('user_id', $user->id)
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->update(['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    }
}
