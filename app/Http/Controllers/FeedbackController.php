<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $user = Auth::user();

        // Validate the request
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback_text' => 'nullable|string|max:1000',
        ]);

        // Check if the user has attended the event
        $hasAttended = $event->attendances()
            ->where('user_id', $user->id)
            ->where('marked_attended', true)
            ->exists();

        if (!$hasAttended) {
            return back()->with('error', 'You can only leave feedback for events you have attended.');
        }

        // Check if feedback already exists from this user
        $existingFeedback = EventFeedback::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existingFeedback) {
            return back()->with('error', 'You have already submitted feedback for this event.');
        }

        // Create the feedback
        EventFeedback::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'rating' => $request->rating,
            'feedback_text' => $request->feedback_text,
        ]);

        return back()->with('success', 'Thank you! Your anonymous feedback has been submitted.');
    }
}
