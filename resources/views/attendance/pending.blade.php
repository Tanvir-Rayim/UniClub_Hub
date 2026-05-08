@extends('layouts.app')

@section('title', 'Mark Attendance - UniClub Hub')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>Mark Attendance</h2>
            <p class="text-muted">View and mark your attendance for upcoming club events</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        @forelse ($pendingEvents as $event)
            @php
                $userAttendance = $event->attendances->where('user_id', auth()->id())->first();
                $isMarked = $userAttendance && $userAttendance->marked_attended;
            @endphp
            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <!-- Card Header with Status -->
                    <div class="card-header @if ($isMarked) bg-success text-white @else bg-light @endif">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ $event->title }}</h5>
                            @if ($isMarked)
                                <span class="badge bg-white text-success">
                                    <i class="fas fa-check-circle"></i> Marked
                                </span>
                            @else
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body">
                        <!-- Event Details -->
                        <div class="mb-3">
                            <p class="mb-2">
                                <strong><i class="fas fa-calendar"></i> Date:</strong>
                                <br>
                                {{ $event->proposed_date->format('l, F d, Y') }}
                                <br>
                                <span class="text-muted small">{{ $event->proposed_date->format('h:i A') }}</span>
                            </p>

                            <p class="mb-2">
                                <strong><i class="fas fa-users"></i> Club:</strong>
                                <br>
                                {{ $event->club->name }}
                            </p>

                            @if ($event->venue)
                                <p class="mb-2">
                                    <strong><i class="fas fa-map-marker-alt"></i> Venue:</strong>
                                    <br>
                                    {{ $event->venue->name }}
                                </p>
                            @endif

                            @if ($event->description)
                                <p class="mb-2">
                                    <strong><i class="fas fa-info-circle"></i> Description:</strong>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($event->description, 100) }}</small>
                                </p>
                            @endif
                        </div>

                        <!-- Attendance Status -->
                        @if ($isMarked)
                            <div class="alert alert-success mb-3">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>Attendance Marked</strong>
                                <br>
                                <small>You marked your attendance on {{ $userAttendance->marked_at->format('M d, Y \a\t h:i A') }}</small>
                            </div>
                        @else
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-bell me-2"></i>
                                <strong>Mark Your Attendance</strong>
                                <br>
                                <small>Please confirm your attendance by clicking the button below.</small>
                            </div>
                        @endif
                    </div>

                    <!-- Card Footer with Action Button -->
                    <div class="card-footer bg-white">
                        @if ($isMarked)
                            <button class="btn btn-success w-100" disabled>
                                <i class="fas fa-check"></i> Already Marked
                            </button>
                        @else
                            <form method="POST" action="{{ route('attendance.mark', $event) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-check-circle"></i> Mark Attendance
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('events.show', $event) }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-external-link-alt"></i> View Event Details
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-check" style="font-size: 3rem; color: #ccc;"></i>
                        <h5 class="mt-3 text-muted">No Pending Attendance</h5>
                        <p class="text-muted">You don't have any pending attendance to mark right now.</p>
                        <a href="{{ route('clubs.show', ['club' => auth()->user()->clubs->first()]) }}" class="btn btn-primary mt-3" @if (!auth()->user()->clubs->count()) disabled @endif>
                            <i class="fas fa-arrow-left"></i> Back to Club
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($pendingEvents->hasPages())
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-center mt-4">
                    {{ $pendingEvents->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .stat-card {
        padding: 15px;
        border-radius: 8px;
    }
</style>
@endsection
