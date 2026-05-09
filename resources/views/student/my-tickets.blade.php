@extends('layouts.app')

@section('title', 'My Tickets - UniClub Hub')

@section('content')
<div class="container-fluid px-4 py-5">

    {{-- Header --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-5 fw-bold mb-1">🎟️ My Tickets</h1>
                    <p class="text-muted fs-6">Manage your event registrations and tickets</p>
                </div>
                <a href="{{ route('student.events.calendar') }}" class="btn btn-primary btn-lg shadow-sm">
                    <i class="fas fa-calendar-alt me-2"></i>Browse Events
                </a>
            </div>
            <hr class="my-4">
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-4 mb-5">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #667eea; border-radius:14px;">
                <div class="card-body text-center p-4">
                    <div class="display-5 fw-bold text-primary mb-1">{{ $activeCount }}</div>
                    <p class="text-muted mb-0 fw-semibold">Active Registrations</p>
                    <small class="text-muted">Events you're registered for</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #43e97b; border-radius:14px;">
                <div class="card-body text-center p-4">
                    <div class="display-5 fw-bold text-success mb-1">{{ $upcomingCount }}</div>
                    <p class="text-muted mb-0 fw-semibold">Upcoming Events</p>
                    <small class="text-muted">Future events you're attending</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tickets Table --}}
    <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
        <div class="card-header d-flex justify-content-between align-items-center py-3"
             style="background: linear-gradient(135deg, #667eea, #764ba2); border:none;">
            <h5 class="mb-0 text-white fw-bold">
                <i class="fas fa-ticket-alt me-2"></i>All Tickets
                <span class="badge bg-white text-primary ms-2">{{ $tickets->count() }}</span>
            </h5>
        </div>
        <div class="card-body p-0">
            @if($tickets->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:0.92rem;">
                        <thead style="background:#f8f9fa;">
                            <tr>
                                <th class="ps-4 py-3">Ticket Code</th>
                                <th>Event</th>
                                <th>Club</th>
                                <th>Venue</th>
                                <th>Event Date</th>
                                <th>Status</th>
                                <th>Cancel Deadline</th>
                                <th class="pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                @php
                                    $isPast = $ticket->event && $ticket->event->proposed_date->isPast();
                                    $hasAttended = $ticket->event ? $ticket->event->attendances()->where('user_id', auth()->id())->where('marked_attended', true)->exists() : false;
                                    $hasFeedback = $ticket->event ? $ticket->event->feedbacks()->where('user_id', auth()->id())->exists() : false;
                                @endphp
                                <tr class="{{ $ticket->status === 'cancelled' ? 'table-secondary' : '' }}">
                                    <td class="ps-4 py-3">
                                        <span class="badge px-3 py-2 fw-bold"
                                              style="background:#eef2ff; color:#4338ca; font-family:monospace; font-size:0.85rem; letter-spacing:1px;">
                                            {{ $ticket->ticket_code }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <strong>{{ $ticket->event ? $ticket->event->title : 'N/A' }}</strong>
                                    </td>
                                    <td>
                                        @if($ticket->event && $ticket->event->club)
                                            <span class="badge bg-info text-dark">{{ $ticket->event->club->name }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $ticket->event && $ticket->event->venue ? $ticket->event->venue->name : 'TBA' }}
                                    </td>
                                    <td>
                                        @if($ticket->event)
                                            <div class="fw-semibold">{{ $ticket->event->proposed_date->format('M d, Y') }}</div>
                                            <div class="text-muted" style="font-size:0.78rem;">
                                                {{ $isPast ? '(Past)' : $ticket->event->proposed_date->diffForHumans() }}
                                            </div>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->status === 'registered')
                                            <span class="badge px-3 py-2" style="background:#d4edda; color:#155724;">
                                                <i class="fas fa-check-circle me-1"></i>Registered
                                            </span>
                                        @else
                                            <span class="badge px-3 py-2" style="background:#f8d7da; color:#721c24;">
                                                <i class="fas fa-times-circle me-1"></i>Cancelled
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->status === 'registered' && $ticket->cancellation_deadline)
                                            @if($ticket->isCancellable())
                                                <span class="text-success small fw-semibold">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Until {{ $ticket->cancellation_deadline->format('M d, g:i A') }}
                                                </span>
                                            @else
                                                <span class="text-danger small">
                                                    <i class="fas fa-lock me-1"></i>Expired
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="pe-4">
                                        @if($ticket->status === 'registered' && $ticket->isCancellable())
                                            <form action="{{ route('student.tickets.cancel', $ticket) }}" method="POST"
                                                  onsubmit="return confirm('Cancel your registration for this event?')">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-times me-1"></i>Cancel
                                                </button>
                                            </form>
                                        @elseif($ticket->status === 'registered' && !$ticket->isCancellable() && !$isPast)
                                            <button class="btn btn-sm btn-secondary" disabled title="Cancellation deadline passed">
                                                <i class="fas fa-lock me-1"></i>Locked
                                            </button>
                                        @elseif($isPast && $hasAttended && !$hasFeedback)
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#feedbackModal{{ $ticket->event->id }}">
                                                <i class="fas fa-star me-1"></i>Leave Feedback
                                            </button>
                                        @elseif($isPast && $hasFeedback)
                                            <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Feedback Submitted</span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                </tr>

                                @if($isPast && $hasAttended && !$hasFeedback)
                                <!-- Feedback Modal -->
                                <div class="modal fade" id="feedbackModal{{ $ticket->event->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('events.feedback.store', $ticket->event) }}">
                                                @csrf
                                                <div class="modal-header bg-light">
                                                    <h5 class="modal-title">Leave Anonymous Feedback</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>How was your experience at <strong>{{ $ticket->event->title }}</strong>?</p>
                                                    <div class="mb-3">
                                                        <label class="form-label">Rating</label>
                                                        <select name="rating" class="form-select" required>
                                                            <option value="">Select rating...</option>
                                                            <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                                                            <option value="4">⭐⭐⭐⭐ (Good)</option>
                                                            <option value="3">⭐⭐⭐ (Average)</option>
                                                            <option value="2">⭐⭐ (Poor)</option>
                                                            <option value="1">⭐ (Terrible)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Feedback (Optional)</label>
                                                        <textarea name="feedback_text" class="form-control" rows="3" placeholder="Tell us what you liked or how we can improve..."></textarea>
                                                        <div class="form-text">Your feedback will be completely anonymous.</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4" style="font-size:4rem;">🎟️</div>
                    <h5 class="text-muted fw-semibold">No Tickets Yet</h5>
                    <p class="text-muted mb-4">Register for upcoming events to see your tickets here.</p>
                    <a href="{{ route('student.events.calendar') }}" class="btn btn-primary px-4">
                        <i class="fas fa-calendar-alt me-2"></i>Browse Upcoming Events
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
</div>

<style>
    .table tbody tr {
        transition: background-color 0.15s;
    }
    .table tbody tr:hover {
        background-color: #f0f4ff !important;
    }
</style>
@endsection
