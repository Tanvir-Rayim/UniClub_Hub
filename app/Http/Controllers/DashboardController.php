<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'advisor' => redirect()->route('advisor.dashboard'),
            'executive' => redirect()->route('executive.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => abort(403, 'Unknown role'),
        };
    }

    // Student Dashboard
    public function studentDashboard()
    {
        $user  = Auth::user();
        $clubs = $user->clubs()->wherePivot('status', 'approved')->get();

        // Event stats
        $registeredEvents = \App\Models\EventRegistration::where('user_id', $user->id)
            ->where('status', 'registered')
            ->count();

        $upcomingRegistered = \App\Models\EventRegistration::where('user_id', $user->id)
            ->where('status', 'registered')
            ->whereHas('event', fn($q) => $q->where('proposed_date', '>=', now()))
            ->count();

        // Mini preview of next 3 upcoming approved events
        $upcomingEvents = \App\Models\Event::with(['club'])
            ->where('advisor_approval_status', 'approved')
            ->where('proposed_date', '>=', now())
            ->orderBy('proposed_date', 'asc')
            ->limit(3)
            ->get();

        return view('dashboards.student', [
            'user'                => $user,
            'clubs'               => $clubs,
            'clubCount'           => $clubs->count(),
            'pendingApplications' => $user->clubs()->wherePivot('status', 'pending')->count(),
            'registeredEvents'    => $registeredEvents,
            'upcomingRegistered'  => $upcomingRegistered,
            'upcomingEvents'      => $upcomingEvents,
        ]);
    }


    // Executive Dashboard
    public function executiveDashboard()
    {
        $user = Auth::user();
        
        // Get clubs where user is assigned as executive
        $assignedClubs = $user->assignedClubs()->get();
        
        // Get proposed events from assigned clubs
        $proposedEvents = \App\Models\Event::whereIn('club_id', $assignedClubs->pluck('id'))
            ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboards.executive', [
            'user' => $user,
            'clubs' => $assignedClubs,
            'activeClubsCount' => $assignedClubs->count(),
            'totalMembers' => $assignedClubs->sum(function($club) { return $club->getActiveMembersCount(); }),
            'proposedEvents' => $proposedEvents,
            'proposedEventsCount' => $proposedEvents->count()
        ]);
    }

    // Advisor Dashboard
    public function advisorDashboard()
    {
        $user = Auth::user();
        $advisedClubs = $user->advisedClubs()->get();
        
        // Get pending event proposals from clubs under this advisor
        $pendingEvents = \App\Models\Event::whereIn('club_id', $advisedClubs->pluck('id'))
            ->where('status', 'pending_approval')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboards.advisor', [
            'user' => $user,
            'clubs' => $advisedClubs,
            'clubCount' => $advisedClubs->count(),
            'pendingEvents' => $pendingEvents,
            'pendingEventsCount' => $pendingEvents->count()
        ]);
    }

    // Admin Dashboard
    public function adminDashboard()
    {
        $totalUsers    = \App\Models\User::count();
        $totalClubs    = \App\Models\Club::count();
        $totalAdvisors = \App\Models\User::where('role', 'advisor')->count();
        $totalEvents   = \App\Models\Event::count();

        // Pending proposals (awaiting advisor approval) — latest 5
        $pendingProposals = \App\Models\Event::with(['club', 'creator'])
            ->where('status', 'pending_approval')
            ->where('advisor_approval_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Advisor-approved events — latest 5
        $advisorApprovedEvents = \App\Models\Event::with(['club', 'creator'])
            ->where('advisor_approval_status', 'approved')
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboards.admin', [
            'totalUsers'            => $totalUsers,
            'totalClubs'            => $totalClubs,
            'totalAdvisors'         => $totalAdvisors,
            'totalEvents'           => $totalEvents,
            'pendingProposals'      => $pendingProposals,
            'pendingProposalsCount' => \App\Models\Event::where('status', 'pending_approval')->where('advisor_approval_status', 'approved')->count(),
            'advisorApprovedEvents' => $advisorApprovedEvents,
            'advisorApprovedCount'  => \App\Models\Event::where('advisor_approval_status', 'approved')->count(),
        ]);
    }

    // Advisor Event Notifications
    public function advisorEventNotifications()
    {
        $user = Auth::user();
        $advisedClubs = $user->advisedClubs()->pluck('id');
        
        // Get pending event proposals from clubs under this advisor
        $events = \App\Models\Event::whereIn('club_id', $advisedClubs)
            ->where('status', 'pending_approval')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('advisor.event-notifications', [
            'user' => $user,
            'events' => $events,
            'eventCount' => $events->count()
        ]);
    }
}
