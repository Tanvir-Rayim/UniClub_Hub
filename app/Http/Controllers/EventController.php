<?php

namespace App\Http\Controllers;

use App\Models\AdvisorEventNotification;
use App\Models\Club;
use App\Models\Event;
use App\Models\Venue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Event::with(['club', 'venue', 'creator']);

        if ($user->isAdmin()) {
            // Admin sees ALL events
        } else {
            // Executive sees only events from their assigned clubs
            $assignedClubIds = $user->assignedClubs()->pluck('clubs.id');
            $query->whereIn('club_id', $assignedClubIds);
        }

        // Search & Filtering
        if ($request->filled('search')) {
            $query->where('title', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('club_id')) {
            $query->where('club_id', $request->club_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('proposed_date', $request->date);
        }

        $events = $query->orderBy('created_at', 'desc')->get();

        return view('events.index', [
            'events'     => $events,
            'eventCount' => $events->count(),
            'isAdmin'    => $user->isAdmin(),
            'clubs'      => $user->isAdmin() ? Club::all() : $user->assignedClubs()->get(),
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
 
        // Venue capacity validation
        if ($request->filled('venue_id')) {
            $venue = Venue::findOrFail($request->venue_id);
            $minCapacity = ceil($venue->capacity * 0.4);
            $maxCapacity = $venue->capacity;
            $audience = $request->expected_audience;

            if (!$audience) {
                return back()->withInput()->withErrors(['expected_audience' => 'Expected audience is required when a venue is selected.']);
            }

            if ($audience < $minCapacity || $audience > $maxCapacity) {
                return back()->withInput()->withErrors([
                    'expected_audience' => "For the selected venue ({$venue->name}), the expected audience must be between {$minCapacity} and {$maxCapacity} (40% to 100% of total capacity)."
                ]);
            }

            // Venue conflict check: Check if any approved or pending event already exists at this venue on this date
            $proposedDate = \Carbon\Carbon::parse($request->proposed_date)->toDateString();
            $conflict = Event::where('venue_id', $request->venue_id)
                ->whereIn('status', ['approved', 'pending_approval'])
                ->whereDate('proposed_date', $proposedDate)
                ->exists();

            if ($conflict) {
                return back()->withInput()->withErrors([
                    'venue_id' => 'This venue is already booked for another event on the selected date.'
                ]);
            }
        }

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

        // Venue capacity validation
        if ($request->filled('venue_id')) {
            $venue = Venue::findOrFail($request->venue_id);
            $minCapacity = ceil($venue->capacity * 0.4);
            $maxCapacity = $venue->capacity;
            $audience = $request->expected_audience;

            if (!$audience) {
                return back()->withInput()->withErrors(['expected_audience' => 'Expected audience is required when a venue is selected.']);
            }

            if ($audience < $minCapacity || $audience > $maxCapacity) {
                return back()->withInput()->withErrors([
                    'expected_audience' => "For the selected venue ({$venue->name}), the expected audience must be between {$minCapacity} and {$maxCapacity} (40% to 100% of total capacity)."
                ]);
            }

            // Venue conflict check
            $proposedDate = \Carbon\Carbon::parse($request->proposed_date)->toDateString();
            $conflict = Event::where('venue_id', $request->venue_id)
                ->where('id', '!=', $event->id)
                ->whereIn('status', ['approved', 'pending_approval'])
                ->whereDate('proposed_date', $proposedDate)
                ->exists();

            if ($conflict) {
                return back()->withInput()->withErrors([
                    'venue_id' => 'This venue is already booked for another event on the selected date.'
                ]);
            }
        }

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

    /**
     * Admin final approval or rejection
     */
    public function adminApprove(Request $request, Event $event)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($event->advisor_approval_status !== 'approved') {
            return back()->with('error', 'Event must be approved by an advisor first.');
        }

        $event->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'Event proposal has been officially approved!');
    }

    public function adminReject(Request $request, Event $event)
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $event->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason']
        ]);

        return back()->with('success', 'Event proposal has been rejected.');
    }

    /**
     * Download the list of registered participants as a PDF.
     */
    public function downloadParticipantsPDF(Event $event)
    {
        $user = Auth::user();
        
        // Authorization check
        $isCreator = $event->created_by === $user->id;
        $isAdvisor = $event->club->faculty_advisor_id === $user->id;
        $isAdmin = $user->isAdmin();
        
        if (!($isCreator || $isAdvisor || $isAdmin)) {
            abort(403, 'Unauthorized access.');
        }

        $participants = $event->registrations()
            ->where('status', 'registered')
            ->with('user')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('events.participants-pdf', [
            'event' => $event,
            'participants' => $participants,
            'generatedAt' => now()
        ]);

        return $pdf->download($event->title . '_participants_' . now()->format('Y-m-d') . '.pdf');
    }
}
