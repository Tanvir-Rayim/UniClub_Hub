<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentEventController extends Controller
{
    /**
     * Public upcoming events calendar — shows all advisor-approved future events.
     */
    public function calendar()
    {
        $user = Auth::user();

        $events = Event::with(['club', 'venue'])
            ->where('advisor_approval_status', 'approved')
            ->where('proposed_date', '>=', now())
            ->orderBy('proposed_date', 'asc')
            ->get();

        // Get the IDs of events this student has already registered for
        $registeredEventIds = EventRegistration::where('user_id', $user->id)
            ->where('status', 'registered')
            ->pluck('event_id')
            ->toArray();

        return view('student.calendar', [
            'events'              => $events,
            'registeredEventIds'  => $registeredEventIds,
        ]);
    }

    /**
     * Register (get a ticket) for an event.
     */
    public function register(Event $event)
    {
        $user = Auth::user();

        // Event must be advisor-approved and in the future
        if ($event->advisor_approval_status !== 'approved') {
            return back()->with('error', 'This event is not open for registration.');
        }

        if ($event->proposed_date->isPast()) {
            return back()->with('error', 'Registration for this event has closed — the event date has passed.');
        }

        // Prevent duplicate registration
        $existing = EventRegistration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            if ($existing->status === 'registered') {
                return back()->with('error', 'You are already registered for this event.');
            }
            // Re-register if previously cancelled
            $existing->update([
                'status'                 => 'registered',
                'registered_at'          => now(),
                'cancelled_at'           => null,
                'cancellation_deadline'  => $event->proposed_date->subHours(24),
            ]);
            return back()->with('success', 'You have successfully re-registered! Your ticket code is: ' . $existing->ticket_code);
        }

        // Create ticket
        EventRegistration::create([
            'event_id'              => $event->id,
            'user_id'               => $user->id,
            'ticket_code'           => EventRegistration::generateTicketCode(),
            'status'                => 'registered',
            'registered_at'         => now(),
            'cancellation_deadline' => $event->proposed_date->copy()->subHours(24),
        ]);

        return back()->with('success', 'Successfully registered! Check "My Tickets" for your ticket code.');
    }

    /**
     * My Tickets — list all the current student's registrations.
     */
    public function myTickets()
    {
        $user = Auth::user();

        $tickets = EventRegistration::with(['event', 'event.club', 'event.venue'])
            ->where('user_id', $user->id)
            ->orderBy('registered_at', 'desc')
            ->get();

        $activeCount   = $tickets->where('status', 'registered')->count();
        $upcomingCount = $tickets->where('status', 'registered')
            ->filter(fn($t) => $t->event && $t->event->proposed_date->isFuture())
            ->count();

        return view('student.my-tickets', [
            'tickets'       => $tickets,
            'activeCount'   => $activeCount,
            'upcomingCount' => $upcomingCount,
        ]);
    }

    /**
     * Cancel a registration — only if before the cancellation deadline.
     */
    public function cancel(EventRegistration $registration)
    {
        $user = Auth::user();

        // Must own this ticket
        if ($registration->user_id !== $user->id) {
            abort(403, 'You do not own this ticket.');
        }

        if (!$registration->isCancellable()) {
            return back()->with('error', 'Cancellation deadline has passed — you can no longer cancel this registration.');
        }

        $registration->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Your registration has been cancelled successfully.');
    }
}
