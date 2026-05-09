<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ClubController extends Controller
{
    // Admin: Create new club
    public function create()
    {
        $advisors = User::where('role', 'advisor')->get();
        return view('clubs.create', ['advisors' => $advisors]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:clubs',
            'description' => 'nullable|string',
            'faculty_advisor_id' => 'nullable|exists:users,id'
        ]);

        Club::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'faculty_advisor_id' => $validated['faculty_advisor_id'],
            'created_by' => Auth::id()
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Club created successfully!');
    }

    // Show club details
    public function show(Club $club)
    {
        $club->load('members', 'advisors', 'creator');
        return view('clubs.show', ['club' => $club]);
    }

    // Edit club
    public function edit(Club $club)
    {
        $advisors = User::where('role', 'advisor')->get();
        return view('clubs.edit', ['club' => $club, 'advisors' => $advisors]);
    }

    public function update(Request $request, Club $club)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:clubs,name,' . $club->id,
            'description' => 'nullable|string',
            'faculty_advisor_id' => 'nullable|exists:users,id'
        ]);

        $club->update($validated);

        return redirect()->route('clubs.show', $club)->with('success', 'Club updated successfully!');
    }

    // List all clubs
    public function index(Request $request)
    {
        $query = Club::where('is_active', true);

        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $clubs = $query->latest()->paginate(15);
        return view('clubs.index', ['clubs' => $clubs]);
    }

    // Apply for membership
    public function applyForMembership(Club $club)
    {
        $user = Auth::user();

        // Executives cannot apply for membership
        if ($user->isExecutive()) {
            return back()->with('error', 'Executives must be assigned to clubs by advisors.');
        }

        // Enforce max 4 clubs per student
        $currentMembershipCount = $user->clubs()
            ->wherePivotIn('status', ['approved', 'pending'])
            ->count();

        if ($currentMembershipCount >= 4) {
            return back()->with('error', 'You have reached the maximum limit of 4 clubs. Please leave a club before joining another.');
        }

        // Check if already a member or has a pending application
        $existing = $user->clubs()->where('club_id', $club->id)->first();
        if ($existing) {
            return back()->with('error', 'You have already applied to or joined this club.');
        }

        $club->members()->syncWithoutDetaching([
            $user->id => ['status' => 'pending']
        ]);

        return back()->with('success', 'Application submitted! Awaiting approval.');
    }

    // Executive: Approve/Reject membership applications
    public function approveMembership(Request $request, Club $club, User $user)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'position' => 'nullable|in:member,secretary,treasurer,vice_president'
        ]);

        $updateData = ['status' => $validated['status']];
        
        if ($validated['status'] === 'approved') {
            $updateData['joined_at'] = now();
            if ($validated['position'] ?? null) {
                $updateData['position'] = $validated['position'];
            }
        }

        $club->members()->updateExistingPivot($user->id, $updateData);

        return back()->with('success', 'Membership status updated!');
    }

    // List pending membership applications for executive
    public function membershipApplications(Club $club)
    {
        $user = Auth::user();

        // Only executives of the club can see applications
        if ($club->created_by !== $user->id && !$user->hasRole('admin')) {
            abort(403);
        }

        $applications = $club->members()->wherePivot('status', 'pending')->paginate(10);

        return view('clubs.applications', [
            'club' => $club,
            'applications' => $applications
        ]);
    }

    // View all members of a club
    public function members(Club $club)
    {
        $user = Auth::user();

        // Only executives, advisors, and admins can view members
        $isExecutive = $club->executives()->where('user_id', $user->id)->exists();
        $isAdvisor = $club->faculty_advisor_id == $user->id;
        
        if (!$isExecutive && !$isAdvisor && !$user->hasRole('admin')) {
            abort(403);
        }

        $members = $club->members()->wherePivot('status', 'approved')->paginate(15);

        return view('clubs.members', [
            'club' => $club,
            'members' => $members,
            'canEditMembers' => $isExecutive || $isAdvisor || $user->hasRole('admin')
        ]);
    }

    // Edit member details
    public function editMember(Club $club, User $user)
    {
        $user_obj = Auth::user();

        // Only executives, advisors, and admins can edit members
        $isExecutive = $club->executives()->where('user_id', $user_obj->id)->exists();
        $isAdvisor = $club->faculty_advisor_id == $user_obj->id;
        
        if (!$isExecutive && !$isAdvisor && !$user_obj->hasRole('admin')) {
            abort(403);
        }

        $member = $club->members()->where('user_id', $user->id)->first();
        
        if (!$member || $member->pivot->status !== 'approved') {
            abort(404);
        }

        return view('clubs.edit-member', [
            'club' => $club,
            'member' => $user,
            'position' => $member->pivot->position
        ]);
    }

    // Update member position
    public function updateMember(Request $request, Club $club, User $user)
    {
        $auth_user = Auth::user();

        // Only executives, advisors, and admins can update members
        $isExecutive = $club->executives()->where('user_id', $auth_user->id)->exists();
        $isAdvisor = $club->faculty_advisor_id == $auth_user->id;
        
        if (!$isExecutive && !$isAdvisor && !$auth_user->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'position' => 'required|in:member,secretary,treasurer,vice_president'
        ]);

        $club->members()->updateExistingPivot($user->id, ['position' => $validated['position']]);

        return redirect()->route('clubs.members', $club)->with('success', 'Member position updated successfully!');
    }

    // Remove member from club
    public function removeMember(Club $club, User $user)
    {
        $auth_user = Auth::user();

        // Only executives, advisors, and admins can remove members
        $isExecutive = $club->executives()->where('user_id', $auth_user->id)->exists();
        $isAdvisor = $club->faculty_advisor_id == $auth_user->id;
        
        if (!$isExecutive && !$isAdvisor && !$auth_user->hasRole('admin')) {
            abort(403);
        }

        $club->members()->detach($user->id);

        return back()->with('success', 'Member removed from club successfully!');
    }

    // Download members list as PDF
    public function downloadMembersPDF(Club $club)
    {
        $auth_user = Auth::user();

        // Only executives, advisors, and admins can download members list
        $isExecutive = $club->executives()->where('user_id', $auth_user->id)->exists();
        $isAdvisor = $club->faculty_advisor_id == $auth_user->id;
        
        if (!$isExecutive && !$isAdvisor && !$auth_user->hasRole('admin')) {
            abort(403);
        }

        $members = $club->members()->wherePivot('status', 'approved')->get();
        
        $pdf = Pdf::loadView('clubs.members-pdf', [
            'club' => $club,
            'members' => $members,
            'generatedAt' => now()
        ]);

        return $pdf->download($club->name . '_members_' . now()->format('Y-m-d') . '.pdf');
    }
}
