<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use Illuminate\Http\Request;

class ClubExecutiveController extends Controller
{
    /**
     * Show the form for assigning executives to a club
     */
    public function edit(Club $club)
    {
        $this->authorize('assignExecutives', $club);
        
        $executives = $club->executives()->get();
        $students = User::where('role', 'executive')->get();

        return view('advisor.club-executives', [
            'club' => $club,
            'executives' => $executives,
            'availableExecutives' => $students
        ]);
    }

    /**
     * Assign an executive to a club
     */
    public function assign(Request $request, Club $club)
    {
        $this->authorize('assignExecutives', $club);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'position' => 'required|in:president,vice_president,secretary,treasurer,member'
        ]);

        // Check if user is an executive
        $user = User::findOrFail($validated['user_id']);
        if ($user->role !== 'executive') {
            return back()->with('error', 'Only executives can be assigned to clubs.');
        }

        // Attach or update the executive
        $club->executives()->syncWithoutDetaching([
            $validated['user_id'] => ['position' => $validated['position']]
        ]);

        // Update if already exists
        if ($club->executives()->where('user_id', $validated['user_id'])->exists()) {
            $club->executives()->updateExistingPivot($validated['user_id'], ['position' => $validated['position']]);
        } else {
            $club->executives()->attach($validated['user_id'], ['position' => $validated['position']]);
        }

        return back()->with('success', 'Executive assigned successfully!');
    }

    /**
     * Remove an executive from a club
     */
    public function remove(Club $club, User $user)
    {
        $this->authorize('assignExecutives', $club);

        $club->executives()->detach($user);

        return back()->with('success', 'Executive removed from club.');
    }
}
