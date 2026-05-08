@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">⏳ Pending Event Proposals</h1>
            <p class="text-muted mb-0">{{ $notifications->total() }} proposals awaiting your approval</p>
        </div>
        @if($notifications->count() > 0)
            <a href="{{ route('advisor.notifications.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list"></i> View All Notifications
            </a>
        @endif
    </div>

    @if($notifications->count() > 0)
        <div class="row g-3">
            @foreach($notifications as $notification)
                <div class="col-12">
                    <div class="card notification-card border-warning shadow-sm">
                        <div class="card-body p-0">
                            <div class="d-flex h-100">
                                <!-- Left Section: Status Indicator -->
                                <div class="p-3 text-center bg-warning bg-opacity-10" style="min-width: 100px; display: flex; flex-direction: column; justify-content: center;">
                                    <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                                    <small class="text-muted mt-2">Pending</small>
                                </div>

                                <!-- Middle Section: Content -->
                                <div class="p-3 flex-grow-1">
                                    <div class="mb-2">
                                        <h5 class="mb-1 fw-bold">
                                            <i class="fas fa-file-alt text-primary"></i>
                                            {{ $notification->event->title }}
                                        </h5>
                                        <p class="mb-2 text-muted">
                                            <strong>Club:</strong> 
                                            <a href="{{ route('clubs.show', $notification->club) }}" class="text-decoration-none">
                                                {{ $notification->club->name }}
                                            </a>
                                        </p>
                                    </div>

                                    <!-- Event Details -->
                                    <div class="row mb-3 g-2">
                                        <div class="col-md-3">
                                            <small class="text-muted d-block"><i class="fas fa-calendar"></i> Date</small>
                                            <small class="fw-bold">{{ $notification->event->proposed_date->format('M d, Y') }}</small>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block"><i class="fas fa-money-bill"></i> Budget</small>
                                            <small class="fw-bold">
                                                {{ $notification->event->budget ? '$' . number_format($notification->event->budget, 2) : 'Not specified' }}
                                            </small>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block"><i class="fas fa-users"></i> Expected</small>
                                            <small class="fw-bold">{{ $notification->event->expected_audience ?? 'Not specified' }}</small>
                                        </div>
                                        <div class="col-md-3">
                                            <small class="text-muted d-block"><i class="fas fa-user"></i> Submitted by</small>
                                            <small class="fw-bold">{{ $notification->event->creator->name }}</small>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    @if($notification->event->description)
                                        <p class="text-muted mb-0 small">
                                            <strong>Description:</strong><br>
                                            {{ Str::limit($notification->event->description, 150) }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Right Section: Actions -->
                                <div class="p-3 text-center" style="min-width: 200px; display: flex; flex-direction: column; justify-content: center; gap: 0.5rem;">
                                    <a href="{{ route('events.show', $notification->event) }}" 
                                       class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> View Details
                                    </a>
                                    <form action="{{ route('events.approve', $notification->event) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm w-100">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <button class="btn btn-outline-danger btn-sm" onclick="showRejectModal({{ $notification->event->id }})">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                    <button 
                                        class="btn btn-outline-secondary btn-sm" 
                                        onclick="markNotificationAsRead({{ $notification->id }})">
                                        <i class="fas fa-check"></i> Mark Read
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $notifications->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
            <h5 class="text-muted">All proposals reviewed! ✨</h5>
            <p class="text-muted mb-3">You've reviewed all pending event proposals</p>
            <a href="{{ route('advisor.notifications.index') }}" class="btn btn-outline-primary">
                <i class="fas fa-inbox"></i> View All Notifications
            </a>
        </div>
    @endif
</div>

<!-- Rejection Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger bg-opacity-10">
                <h5 class="modal-title">Reject Event Proposal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-3">Please provide a reason for rejection:</p>
                    <textarea 
                        name="rejection_reason" 
                        class="form-control" 
                        rows="4" 
                        placeholder="Enter rejection reason..." 
                        required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Reject Proposal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    .notification-card {
        transition: all 0.3s ease;
        border-left: 4px solid #ffc107;
    }

    .notification-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transform: translateY(-2px);
    }

    .card-body {
        border-radius: 0.375rem;
    }
</style>

<!-- Scripts -->
<script>
const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));

function showRejectModal(eventId) {
    const form = document.getElementById('rejectForm');
    form.action = `/events/${eventId}/reject`;
    rejectModal.show();
}

function markNotificationAsRead(notificationId) {
    fetch(`/advisor/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection
