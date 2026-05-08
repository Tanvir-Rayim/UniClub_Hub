<?php

namespace App\Http\Controllers;

use App\Models\AdvisorEventNotification;
use App\Models\Club;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $user = Auth::user(); 
        // Get clubs where user is assigned as executive
        $assignedClubIds = $user->assignedClubs()->pluck('clubs.id');
        
        // Get all events from those clubs
        $events = Event::whereIn('club_id', $assignedClubIds)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('events.index', [
            'events' => $events,
            'eventCount' => $events->count()
        ]);
    }
    public function create()
    {
        $user = Auth::user();
        // Get clubs where user is assigned as executive
        $clubs = $user->assignedClubs()->get();
        if ($clubs->isEmpty()) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not assigned to any clubs.');
        }
        return view('events.create', [
            'clubs' => $clubs
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'venue_id' => 'nullable|exists:venues,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'proposed_date' => 'required|date|after:today',
            'budget' => 'nullable|numeric|min:0',
            'expected_audience' => 'nullable|integer|min:1',
        ]);

        $user = Auth::user();
        
        // Verify user is assigned to this club
        $isAssignedToClub = $user->assignedClubs()
            ->where('club_id', $validated['club_id'])
            ->exists();

        if (!$isAssignedToClub) {
            return redirect()->route('events.index')
                ->with('error', 'You are not assigned to this club.');
        }

        // Create the event
        $event = Event::create([
            'club_id' => $validated['club_id'],
            'venue_id' => $validated['venue_id'] ?? null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'proposed_date' => $validated['proposed_date'],
            'budget' => $validated['budget'],
            'expected_audience' => $validated['expected_audience'],
            'status' => 'pending_approval',
            'created_by' => $user->id
        ]);

        // Get the club and its advisor
        $club = Club::find($validated['club_id']);
        $advisor = $club->advisors;

        // Create notification for the advisor if one exists
        if ($advisor) {
            AdvisorEventNotification::create([
                'advisor_id' => $advisor->id,
                'event_id' => $event->id,
                'club_id' => $club->id,
                'notification_type' => 'new_proposal',
                'message' => "New event proposal: {$event->title} from {$club->name} awaiting your approval.",
                'status' => 'pending'
            ]);
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event proposal submitted successfully! Awaiting advisor approval.');
    }

    public function show(Event $event)
    {
        $user = Auth::user();
        
        $isCreator = $event->created_by === $user->id;
        $isAdvisor = $event->club->faculty_advisor_id === $user->id;
        $isAdmin = $user->isAdmin();
        
        if (!($isCreator || $isAdvisor || $isAdmin)) {
            abort(403, 'Unauthorized access.');
        }
        
        return view('events.show', [
            'event' => $event
        ]);
    }

    public function edit(Event $event)
    {
        $user = Auth::user();
        
        if ($event->created_by !== $user->id || $event->status !== 'draft') {
            abort(403, 'You cannot edit this event.');
        }

        $clubs = $user->assignedClubs()->get();

        return view('events.edit', [
            'event' => $event,
            'clubs' => $clubs
        ]);
    }

    public function update(Request $request, Event $event)
    {
        $user = Auth::user();
        
        // Only creator can edit (if still in draft status)
        if ($event->created_by !== $user->id || $event->status !== 'draft') {
            abort(403, 'You cannot edit this event.');
        }

        $validated = $request->validate([
            'club_id' => 'required|exists:clubs,id',
            'venue_id' => 'nullable|exists:venues,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'proposed_date' => 'required|date|after:today',
            'budget' => 'nullable|numeric|min:0',
            'expected_audience' => 'nullable|integer|min:1',
        ]);

        // Verify user is assigned to the club
        $isAssignedToClub = $user->assignedClubs()
            ->where('club_id', $validated['club_id'])
            ->exists();

        if (!$isAssignedToClub) {
            return back()->with('error', 'You are not assigned to this club.');
        }

        $event->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'Event proposal updated successfully!');
    }

    public function destroy(Event $event)
    {
        $user = Auth::user();
        
        // Only creator can delete (if still in draft status)
        if ($event->created_by !== $user->id || $event->status !== 'draft') {
            abort(403, 'You cannot delete this event.');
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event proposal deleted.');
    }

    /**
     * Advisor reviews and approves/rejects event proposals
     */
    public function approve(Request $request, Event $event)
    {
        $user = Auth::user();

        // Only advisor of the club can approve
        if ($event->club->advisors && $event->club->advisors->id !== $user->id && !$user->isAdmin()) {
            abort(403, 'You cannot approve this event.');
        }

        $validated = $request->validate([
            'advisor_remarks' => 'nullable|string'
        ]);

        $event->update([
            'advisor_approval_status' => 'approved',
            'advisor_remarks' => $validated['advisor_remarks'] ?? null,
        ]);

        return back()->with('success', 'Event proposal approved and forwarded to Admin!');
    }

    public function reject(Request $request, Event $event)
    {
        $user = Auth::user();

        // Only advisor of the club can reject
        if ($event->club->advisors && $event->club->advisors->id !== $user->id && !$user->isAdmin()) {
            abort(403, 'You cannot reject this event.');
        }

        $validated = $request->validate([
            'advisor_remarks' => 'required|string'
        ]);

        $event->update([
            'advisor_approval_status' => 'rejected',
            'advisor_remarks' => $validated['advisor_remarks'],
            'status' => 'rejected',
            'rejection_reason' => $validated['advisor_remarks']
        ]);

        return back()->with('success', 'Event proposal rejected.');
    }
}
