@extends('layouts.app')

@section('title', $event->title . ' - UniClub Hub')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">{{ $event->title }}</h2>
            <p class="text-muted">{{ $event->club->name }}</p>
        </div>
        <div class="col-md-4 text-end">
            @if ($event->status === 'draft')
                <a href="{{ route('events.edit', $event) }}" class="btn btn-warning">
                    Edit
                </a>
                <form action="{{ route('events.destroy', $event) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this proposal?')">
                        Delete
                    </button>
                </form>
            @endif
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
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Event Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Club:</strong></td>
                            <td>{{ $event->club->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Venue:</strong></td>
                            <td>
                                @if ($event->venue)
                                    {{ $event->venue->name }} (Capacity: {{ $event->venue->capacity }})
                                @else
                                    <span class="text-muted">Not selected</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Proposed Date:</strong></td>
                            <td>{{ $event->proposed_date->format('F d, Y \a\t g:i A') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Budget:</strong></td>
                            <td>
                                @if ($event->budget)
                                    ${{ number_format($event->budget, 2) }}
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Expected Audience:</strong></td>
                            <td>
                                @if ($event->expected_audience)
                                    {{ $event->expected_audience }} attendees
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status:</strong></td>
                            <td>
                                @if ($event->advisor_approval_status === 'pending')
                                    <span class="badge bg-warning">Pending Advisor Review</span>
                                @elseif ($event->advisor_approval_status === 'approved' && $event->status === 'pending_approval')
                                    <span class="badge bg-info">Pending Admin Approval</span>
                                @elseif ($event->status === 'pending_approval')
                                    <span class="badge bg-warning">Pending Approval</span>
                                @elseif ($event->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif ($event->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($event->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Financial Release:</strong></td>
                            <td>
                                @if ($event->financial_release_status)
                                    <span class="badge bg-success">Authorized ({{ $event->financial_released_at->format('M d, Y') }})</span>
                                @else
                                    <span class="badge bg-secondary">Locked</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Submitted By:</strong></td>
                            <td>{{ $event->creator->name }}</td>
                        </tr>
                    </table>

                    <hr>

                    <h6 class="mb-3">Description</h6>
                    <p>{{ $event->description }}</p>

                    @if ($event->advisor_remarks)
                        <hr>
                        <div class="alert alert-info">
                            <h6>Advisor Remarks:</h6>
                            <p class="mb-0">{{ $event->advisor_remarks }}</p>
                        </div>
                    @endif

                    @if ($event->status === 'rejected' && $event->rejection_reason && $event->rejection_reason !== $event->advisor_remarks)
                        <div class="alert alert-danger">
                            <h6>Rejection Reason:</h6>
                            <p class="mb-0">{{ $event->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @php
                $canViewFeedback = auth()->user()->hasRole('admin') || auth()->user()->hasRole('executive') || auth()->user()->hasRole('advisor');
                $feedbacks = $event->feedbacks()->latest()->get();
                $avgRating = $feedbacks->avg('rating');
            @endphp

            @if($canViewFeedback && $event->proposed_date->isPast())
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Anonymous Student Feedback</h5>
                    @if($feedbacks->count() > 0)
                        <span class="badge bg-primary">
                            <i class="fas fa-star me-1"></i> {{ number_format($avgRating, 1) }} / 5.0 ({{ $feedbacks->count() }} ratings)
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    @if($feedbacks->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($feedbacks as $feedback)
                                <li class="list-group-item px-0 {{ $loop->last ? 'border-bottom-0 pb-0' : '' }}">
                                    <div class="d-flex justify-content-between mb-1">
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $feedback->rating ? '' : 'text-muted opacity-25' }}"></i>
                                            @endfor
                                        </div>
                                        <small class="text-muted">{{ $feedback->created_at->diffForHumans() }}</small>
                                    </div>
                                    @if($feedback->feedback_text)
                                        <p class="mb-0 text-muted fst-italic">"{{ $feedback->feedback_text }}"</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">No feedback has been submitted for this event yet.</p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('events.budget.show', $event) }}" class="btn btn-info w-100 mb-2">
                        <i class="fas fa-money-bill-wave me-1"></i> Manage Budget & Expenses
                    </a>

                    @if ($event->status === 'approved' && (auth()->user()->id === $event->created_by || auth()->user()->hasRole('admin')))
                        <a href="{{ route('attendance.show', $event) }}" class="btn btn-success w-100 mb-2">
                            <i class="fas fa-users me-1"></i> Manage Attendance
                        </a>
                    @elseif ($event->status === 'approved')
                        <form method="POST" action="{{ route('attendance.mark', $event) }}" style="display:inline;">
                            @csrf
                            @php
                                $userAttendance = $event->attendances->where('user_id', auth()->id())->first();
                                $isMarked = $userAttendance && $userAttendance->marked_attended;
                            @endphp
                            @if ($isMarked)
                                <button type="button" class="btn btn-success w-100 mb-2" disabled>
                                    <i class="fas fa-check me-1"></i> Attendance Marked
                                </button>
                            @else
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="fas fa-check-circle me-1"></i> Mark Attendance
                                </button>
                            @endif
                        </form>
                    @endif

                    <a href="{{ route('events.index') }}" class="btn btn-outline-primary w-100 mb-4">
                        Back to My Proposals
                    </a>

                    @php
                        $isAdvisor = $event->club->advisors && $event->club->advisors->id === auth()->id();
                        $isAdmin = auth()->user()->hasRole('admin');
                    @endphp

                    @if ($isAdvisor && $event->advisor_approval_status === 'pending')
                    <hr>
                    <h5 class="mb-3">Advisor Review</h5>
                    <!-- Review Actions -->
                    <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                        Approve & Forward
                    </button>
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        Reject
                    </button>

                    <!-- Approve Modal -->
                    <div class="modal fade" id="approveModal" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('events.approve', $event) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Approve Event Proposal</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Approving this proposal will forward it to the Admin for final approval.</p>
                                        <div class="mb-3">
                                            <label for="advisor_remarks_approve" class="form-label">Remarks (Optional)</label>
                                            <textarea class="form-control" name="advisor_remarks" id="advisor_remarks_approve" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">Approve</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Reject Modal -->
                    <div class="modal fade" id="rejectModal" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('events.reject', $event) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reject Event Proposal</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Please provide a reason or constructive remarks for the rejection.</p>
                                        <div class="mb-3">
                                            <label for="advisor_remarks_reject" class="form-label">Remarks / Rejection Reason</label>
                                            <textarea class="form-control" name="advisor_remarks" id="advisor_remarks_reject" rows="4" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Reject Proposal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    </div>
                    @endif
 
                    @if ($isAdvisor && $event->status === 'approved' && !$event->financial_release_status)
                        <hr>
                        <h5 class="mb-3">Finance Management</h5>
                        <form action="{{ route('events.budget.release', $event) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fas fa-hand-holding-usd me-1"></i> Release Event Funds
                            </button>
                        </form>
                    @endif

                    @if ($isAdmin && $event->status === 'pending_approval' && $event->advisor_approval_status === 'approved')
                    <hr>
                    <h5 class="mb-3">Admin Final Review</h5>
                    <!-- Review Actions -->
                    <button type="button" class="btn btn-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#adminApproveModal">
                        Approve Officially
                    </button>
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#adminRejectModal">
                        Reject
                    </button>

                    <!-- Admin Approve Modal -->
                    <div class="modal fade" id="adminApproveModal" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('events.admin-approve', $event) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Approve Event Proposal</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>This will officially approve the event and make it visible to students.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-success">Confirm Approval</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Admin Reject Modal -->
                    <div class="modal fade" id="adminRejectModal" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('events.admin-reject', $event) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Reject Event Proposal</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Please provide a reason for rejecting this event proposal.</p>
                                        <div class="mb-3">
                                            <label for="admin_rejection_reason" class="form-label">Rejection Reason</label>
                                            <textarea class="form-control" name="rejection_reason" id="admin_rejection_reason" rows="4" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-danger">Reject Proposal</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
