<?php

namespace App\Http\Controllers;
use App\Models\AdvisorEventNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AdvisorNotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notifications = AdvisorEventNotification::forAdvisor($user->id)
            ->latest()
            ->paginate(15);
        $pendingCount = AdvisorEventNotification::forAdvisor($user->id)
            ->pending()
            ->count();
        return view('advisor.notifications.index', [
            'notifications' => $notifications,
            'pendingCount' => $pendingCount
        ]);
    }
    public function pending()
    {
        $user = Auth::user();
        $notifications = AdvisorEventNotification::forAdvisor($user->id)
            ->pending()
            ->latest()
            ->paginate(15);
        return view('advisor.notifications.pending', [
            'notifications' => $notifications
        ]);
    }

    public function markAsRead(AdvisorEventNotification $notification)
    {
        $user = Auth::user();
        if ($notification->advisor_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $notification->markAsRead();
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read'
        ]);
    }
    public function markAllAsRead()
    {
        $user = Auth::user();
        AdvisorEventNotification::forAdvisor($user->id)
            ->pending()
            ->update([
                'status' => 'read',
                'read_at' => now()
            ]);
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }
    public function archive(AdvisorEventNotification $notification)
    {
        $user = Auth::user();
        if ($notification->advisor_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $notification->archive();
        return response()->json([
            'success' => true,
            'message' => 'Notification archived'
        ]);
    }
    public function destroy(AdvisorEventNotification $notification)
    {
        $user = Auth::user();
        if ($notification->advisor_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $notification->delete();
        return response()->json([
            'success' => true,
            'message' => 'Notification deleted'
        ]);
    }
    public function getUnreadCount()
    {
        $user = Auth::user();

        $count = AdvisorEventNotification::forAdvisor($user->id)
            ->where(function ($query) {
                $query->pending()
                      ->orWhere(function ($q) {
                          $q->where('status', 'read')
                            ->whereNull('read_at');
                      });
            })
            ->count();

        return response()->json([
            'unread_count' => $count
        ]);
    }
    public function getRecent($limit = 5)
    {
        $user = Auth::user();
        $notifications = AdvisorEventNotification::forAdvisor($user->id)
            ->pending()
            ->latest()
            ->limit($limit)
            ->get();
        return response()->json([
            'notifications' => $notifications->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $notif->type_text,
                    'type_icon' => $notif->type_icon,
                    'status' => $notif->status_text,
                    'message' => $notif->message,
                    'event_title' => $notif->event->title ?? 'Unknown Event',
                    'club_name' => $notif->club->name ?? 'Unknown Club',
                    'created_at' => $notif->created_at->diffForHumans(),
                    'read_at' => $notif->read_at
                ];
            })
        ]);
    }
}
