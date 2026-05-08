@extends('layouts.app')

@section('title', $event->title . ' - Attendance - UniClub Hub')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>{{ $event->title }} - Attendance</h2>
            <p class="text-muted">Track member attendance for this event</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Attendance Overview -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Attendance Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stat-card text-center">
                                <h6 class="text-muted">Total Members</h6>
                                <h3 class="text-primary">{{ $totalCount }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card text-center">
                                <h6 class="text-muted">Present</h6>
                                <h3 class="text-success">{{ $attendedCount }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card text-center">
                                <h6 class="text-muted">Absent</h6>
                                <h3 class="text-danger">{{ $totalCount - $attendedCount }}</h3>
                            </div>
                        </div>
                    </div>
                    @if ($totalCount > 0)
                        <div class="mt-3">
                            <div class="progress" style="height: 25px;">
                                @php
                                    $percentage = ($attendedCount / $totalCount) * 100;
                                @endphp
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%;" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ number_format($percentage, 1) }}%
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Attendance Notification Status -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Notification Status</h5>
                    @if (!$notificationSent)
                        <form method="POST" action="{{ route('attendance.notify', $event) }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-bell me-1"></i> Send Attendance Notification
                            </button>
                        </form>
                    @endif
                </div>
                <div class="card-body">
                    @if ($notificationSent)
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Notification Sent</strong>
                            @if ($lastNotification)
                                <br><small class="text-muted">Sent on {{ $lastNotification->sent_at->format('M d, Y \a\t h:i A') }} by {{ $lastNotification->sentBy->name }}</small>
                            @endif
                        </div>
                        <p class="text-muted">All club members have been notified to mark their attendance.</p>
                    @else
                        <p class="text-muted">Click the button above to send an attendance notification to all club members.</p>
                    @endif
                </div>
            </div>

            <!-- Member Attendance List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Member Attendance</h5>
                </div>
                <div class="card-body">
                    @if ($attendances->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Member Name</th>
                                        <th>University ID</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Marked At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendances as $attendance)
                                        <tr>
                                            <td>
                                                <strong>{{ $attendance->user->name }}</strong>
                                            </td>
                                            <td>{{ $attendance->user->university_id }}</td>
                                            <td>{{ $attendance->user->email }}</td>
                                            <td>
                                                @if ($attendance->marked_attended)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check"></i> Present
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-times"></i> Not Marked
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($attendance->marked_at)
                                                    {{ $attendance->marked_at->format('M d, Y h:i A') }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $attendances->links() }}
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No attendance records yet. Send a notification first.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Event Info</h5>
                </div>
                <div class="card-body small">
                    <p><strong>Club:</strong> {{ $event->club->name }}</p>
                    <p><strong>Date:</strong> {{ $event->proposed_date->format('M d, Y h:i A') }}</p>
                    <p><strong>Expected Audience:</strong> {{ $event->expected_audience ?? 'Not specified' }}</p>
                    <p><strong>Status:</strong>
                        @if ($event->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @endif
                    </p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('events.show', $event) }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Back to Event
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
