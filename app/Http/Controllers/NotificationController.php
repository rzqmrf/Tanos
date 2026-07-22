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
     * Dedicated Notifications Page View.
     */
    public function page(Request $request)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) {
            return redirect()->route('login');
        }

        $query = Notification::where('user_id', $user->id);

        // Filter by type if provided
        if ($request->filled('type') && in_array($request->type, ['project', 'employee', 'invoice'])) {
            $query->where('type', $request->type);
        }

        // Filter by read status if provided
        if ($request->filled('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $notifications = $query->latest()->paginate(15)->withQueryString();

        $unreadCount = Notification::where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        $totalCount = Notification::where('user_id', $user->id)->count();

        return view('dashboard.notifications', compact('notifications', 'unreadCount', 'totalCount'));
    }

    /**
     * Get notifications for the authenticated user (API for Navbar).
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

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca!');
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

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notifikasi ditandai dibaca.');
    }

    /**
     * Delete a single notification.
     */
    public function destroy($id)
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) {
            return redirect()->route('login');
        }

        Notification::where('user_id', $user->id)
            ->where('id', $id)
            ->delete();

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Delete all notifications for the user.
     */
    public function deleteAll()
    {
        $user = $this->getAuthenticatedUser();
        if (!$user) {
            return redirect()->route('login');
        }

        Notification::where('user_id', $user->id)->delete();

        return redirect()->back()->with('success', 'Seluruh riwayat notifikasi berhasil dibersihkan.');
    }
}
