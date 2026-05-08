@extends('layouts.app')

@section('title', $club->name . ' - UniClub Hub')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ $club->name }}</h2>
            <p class="text-muted">{{ $club->description }}</p>
        </div>
        <div class="col-md-4 text-end">
            @auth
                @if (Auth::user()->hasRole('admin'))
                    <a href="{{ route('clubs.edit', $club) }}" class="btn btn-warning">Edit</a>
                @endif
            @endauth
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">About This Club</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Active Members:</strong></td>
                            <td><span class="badge bg-info">{{ $club->getActiveMembersCount() }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Pending Applications:</strong></td>
                            <td><span class="badge bg-warning">{{ $club->getPendingMembersCount() }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Faculty Advisor:</strong></td>
                            <td>{{ $club->advisors?->name ?? 'Not Assigned' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Created By:</strong></td>
                            <td>{{ $club->creator?->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                <span class="badge bg-{{ $club->is_active ? 'success' : 'danger' }}">
                                    {{ $club->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Members</h5>
                </div>
                <div class="card-body">
                    @if ($club->members->count() > 0)
                        <div class="list-group">
                            @foreach ($club->members as $member)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h6 class="mb-1">{{ $member->name }}</h6>
                                            <p class="mb-0 text-muted small">{{ $member->university_id }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <span class="badge bg-{{ $member->pivot->status == 'approved' ? 'success' : 'warning' }}">
                                                {{ ucfirst($member->pivot->status) }}
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            @auth
                                                @if (Auth::user()->id == $club->created_by && $member->pivot->status == 'pending')
                                                    <form method="POST" action="{{ route('clubs.member-status', [$club, $member]) }}" style="display:inline;">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No members yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    @auth
                        @if (Auth::user()->isStudent())
                            @if (!Auth::user()->clubs->contains($club->id))
                                <form method="POST" action="{{ route('clubs.apply', $club) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary w-100">
                                        Apply for Membership
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-info">
                                    <small>You are already a member of this club.</small>
                                </div>
                            @endif
                        @elseif (Auth::user()->isExecutive())
                            <div class="alert alert-info">
                                <small>Executives are assigned to clubs by advisors, not through membership applications.</small>
                            </div>
                        @endif

                        @if (Auth::user()->id == $club->created_by || Auth::user()->hasRole('admin'))
                            <a href="{{ route('clubs.applications', $club) }}" class="btn btn-secondary w-100">
                                View Applications
                            </a>
                            <a href="{{ route('clubs.members', $club) }}" class="btn btn-info w-100 mt-2">
                                Manage Members
                            </a>
                        @elseif (Auth::user()->isExecutive() && Auth::user()->executives->contains('club_id', $club->id))
                            <a href="{{ route('clubs.members', $club) }}" class="btn btn-info w-100">
                                View & Manage Members
                            </a>
                        @elseif (Auth::user()->isAdvisor() && $club->faculty_advisor_id == Auth::user()->id)
                            <a href="{{ route('clubs.members', $club) }}" class="btn btn-info w-100">
                                View & Manage Members
                            </a>
                        @endif

                        @if (Auth::user()->isAdvisor() && $club->faculty_advisor_id == Auth::user()->id || Auth::user()->hasRole('admin'))
                            <a href="{{ route('clubs.executives.edit', $club) }}" class="btn btn-info w-100 mt-2">
                                Manage Executives
                            </a>
                        @endif
                    @else
                        <p class="text-muted text-center py-4">
                            <a href="{{ route('login') }}">Login</a> to join this club
                        </p>
                    @endauth
                </div>
            </div>

            @auth
                @if (Auth::user()->hasRole('admin'))
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Admin Actions</h5>
                        </div>
                        <div class="card-body">
                            <a href="{{ route('clubs.edit', $club) }}" class="btn btn-warning w-100 mb-2">
                                Edit Club
                            </a>
                            <form method="POST" action="{{ route('clubs.update', $club) }}" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="name" value="{{ $club->name }}">
                                <button type="submit" class="btn btn-danger w-100">
                                    Deactivate Club
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>
@endsection
