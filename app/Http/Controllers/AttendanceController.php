<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendance;
use App\Models\AttendanceNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function sendAttendanceNotification(Event $event)
    {
        $user = Auth::user();
        if ($event->created_by !== $user->id && !$user->hasRole('admin')) {
            abort(403);
        }
        $notification = AttendanceNotification::create([
            'event_id' => $event->id,
            'sent_by_id' => $user->id,
            'sent_at' => now(),
            'message' => "Please mark your attendance for {$event->title}"
        ]);
        $members = $event->club->members()
            ->wherePivot('status', 'approved')
            ->pluck('user_id');

        // Create attendance records for each member (if not already exists)
        foreach ($members as $memberId) {
            EventAttendance::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'user_id' => $memberId
                ],
                [
                    'marked_attended' => false
                ]
            );
        }

        return back()->with('success', 'Attendance notification sent to all club members!');
    }

    // Mark attendance for a member
    public function markAttendance(Request $request, Event $event)
    {
        $user = Auth::user();

        // Check if user is an approved member of the club
        $isMember = $event->club->members()
            ->where('user_id', $user->id)
            ->wherePivot('status', 'approved')
            ->exists();

        if (!$isMember && !$user->hasRole('admin')) {
            abort(403);
        }

        // Check if attendance notification was sent
        $notificationExists = $event->attendanceNotifications()->exists();
        if (!$notificationExists && !$user->hasRole('admin')) {
            return back()->with('error', 'Attendance notification has not been sent yet.');
        }

        // Update or create attendance record
        $attendance = EventAttendance::updateOrCreate(
            [
                'event_id' => $event->id,
                'user_id' => $user->id
            ],
            [
                'marked_attended' => true,
                'marked_at' => now()
            ]
        );

        return back()->with('success', 'Your attendance has been marked!');
    }

    // View attendance list (for event creator/admin)
    public function show(Event $event)
    {
        $user = Auth::user();

        // Only event creator or admin can view attendance
        if ($event->created_by !== $user->id && !$user->hasRole('admin')) {
            abort(403);
        }

        $attendances = $event->attendances()->with('user')->paginate(20);
        $attendedCount = $event->attendances()->where('marked_attended', true)->count();
        $totalCount = $event->attendances()->count();
        $notificationSent = $event->attendanceNotifications()->exists();
        $lastNotification = $event->attendanceNotifications()->latest()->first();

        return view('attendance.show', [
            'event' => $event,
            'attendances' => $attendances,
            'attendedCount' => $attendedCount,
            'totalCount' => $totalCount,
            'notificationSent' => $notificationSent,
            'lastNotification' => $lastNotification,
        ]);
    }

    // View pending attendance (for members)
    public function pending()
    {
        $user = Auth::user();

        // Get all events where user is a member and attendance notification has been sent
        $pendingEvents = Event::whereHas('club.members', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('status', 'approved');
        })
        ->whereHas('attendanceNotifications')
        ->whereHas('attendances', function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('marked_attended', false);
        })
        ->with(['club', 'attendances' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }])
        ->latest()
        ->paginate(10);

        return view('attendance.pending', [
            'pendingEvents' => $pendingEvents,
            'pendingCount' => $pendingEvents->total(),
        ]);
    }
}
