<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;

class EventPolicy
{
    /**
     * Determine whether the user can view the event.
     */
    public function view(User $user, Event $event): bool
    {
        // Creator can always view
        if ($event->created_by === $user->id) {
            return true;
        }

        // Advisors can view events from their clubs
        if ($user->isAdvisor() && $event->club->faculty_advisor_id === $user->id) {
            return true;
        }

        // Admins can view all
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create events.
     */
    public function create(User $user): bool
    {
        return $user->isExecutive() || $user->hasRole('admin');
    }

    public function update(User $user, Event $event): bool
    {
        // Only creator can update their own events
        if ($event->created_by === $user->id) {
            return true;
        }

        // Admins can update
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Event $event): bool
    {
        if ($event->created_by === $user->id) {
            return true;
        }
        if ($user->hasRole('admin')) {
            return true;
        }

        return false;
    }
}
