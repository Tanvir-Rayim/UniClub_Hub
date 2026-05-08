@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-6 fw-bold">📬 Event Notifications</h1>
            <p class="text-muted mb-0">Manage pending event proposals and updates</p>
        </div>
        @if($pendingCount > 0)
            <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                <i class="fas fa-check"></i> Mark All as Read
            </button>
        @endif
    </div>

    <!-- Tabs for filtering -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item" role="presentation">
            <button 
                class="nav-link active" 
                id="all-tab" 
                data-bs-toggle="tab" 
                data-bs-target="#all" 
                type="button" 
                role="tab">
                <i class="fas fa-inbox"></i> All Notifications
                <span class="badge bg-secondary ms-2">{{ $notifications->total() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button 
                class="nav-link" 
                id="pending-tab" 
                data-bs-toggle="tab" 
                data-bs-target="#pending" 
                type="button" 
                role="tab">
                <i class="fas fa-hourglass-half"></i> Pending
                @if($pendingCount > 0)
                    <span class="badge bg-warning ms-2">{{ $pendingCount }}</span>
                @endif
            </button>
        </li>
    </ul>

    <!-- Notifications List -->
    <div class="tab-content">
        <!-- All Notifications Tab -->
        <div class="tab-pane fade show active" id="all" role="tabpanel">
            @if($notifications->count() > 0)
                <div class="row g-3">
                    @foreach($notifications as $notification)
                        <div class="col-12">
                            <div class="card notification-card {{ $notification->status === 'pending' ? 'border-warning' : '' }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <!-- Left Side: Icon & Content -->
                                        <div class="flex-grow-1 d-flex gap-3">
                                            <!-- Notification Icon -->
                                            <div class="flex-shrink-0">
                                                <i class="{{ $notification->type_icon }} fa-lg" 
                                                   style="color: {{ $notification->status === 'pending' ? '#ffc107' : '#17a2b8' }}"></i>
                                            </div>

                                            <!-- Content -->
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <h6 class="mb-0 fw-bold">{{ $notification->type_text }}</h6>
                                                    <span class="badge bg-{{ $notification->status === 'pending' ? 'warning' : 'info' }}">
                                                        {{ $notification->status_text }}
                                                    </span>
                                                </div>

                                                <!-- Event & Club Info -->
                                                <p class="mb-2">
                                                    <strong>Event:</strong> {{ $notification->event->title }}<br>
                                                    <strong>Club:</strong> {{ $notification->club->name }}
                                                </p>

                                                <!-- Message -->
                                                @if($notification->message)
                                                    <p class="text-muted mb-2">{{ $notification->message }}</p>
                                                @endif

                                                <!-- Details -->
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt"></i> 
                                                    {{ $notification->created_at->format('M d, Y H:i') }}
                                                    @if($notification->read_at)
                                                        <span class="ms-2">
                                                            <i class="fas fa-check"></i> Read: {{ $notification->read_at->format('M d, Y H:i') }}
                                                        </span>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>

                                        <!-- Right Side: Actions -->
                                        <div class="flex-shrink-0 ms-3">
                                            <div class="btn-group-vertical" role="group">
                                                <!-- View Event Button -->
                                                <a href="{{ route('events.show', $notification->event) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View Event
                                                </a>

                                                <!-- Mark as Read (if pending) -->
                                                @if($notification->status === 'pending')
                                                    <button 
                                                        class="btn btn-sm btn-outline-success" 
                                                        onclick="markNotificationAsRead({{ $notification->id }})">
                                                        <i class="fas fa-check"></i> Mark Read
                                                    </button>
                                                @endif

                                                <!-- Archive Button -->
                                                <button 
                                                    class="btn btn-sm btn-outline-secondary" 
                                                    onclick="archiveNotification({{ $notification->id }})">
                                                    <i class="fas fa-archive"></i> Archive
                                                </button>

                                                <!-- Delete Button -->
                                                <button 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="deleteNotification({{ $notification->id }})">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
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
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No notifications yet</h5>
                    <p class="text-muted">Event proposals will appear here</p>
                </div>
            @endif
        </div>

        <!-- Pending Notifications Tab -->
        <div class="tab-pane fade" id="pending" role="tabpanel">
            @if($pendingCount > 0)
                <div class="row g-3">
                    @foreach($notifications->where('status', 'pending') as $notification)
                        <div class="col-12">
                            <div class="card notification-card border-warning">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1 d-flex gap-3">
                                            <div class="flex-shrink-0">
                                                <i class="{{ $notification->type_icon }} fa-lg" style="color: #ffc107;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <h6 class="mb-0 fw-bold">{{ $notification->type_text }}</h6>
                                                    <span class="badge bg-warning">Pending Review</span>
                                                </div>
                                                <p class="mb-2">
                                                    <strong>Event:</strong> {{ $notification->event->title }}<br>
                                                    <strong>Club:</strong> {{ $notification->club->name }}
                                                </p>
                                                @if($notification->message)
                                                    <p class="text-muted mb-2">{{ $notification->message }}</p>
                                                @endif
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-alt"></i> 
                                                    {{ $notification->created_at->format('M d, Y H:i') }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ms-3">
                                            <div class="btn-group-vertical" role="group">
                                                <a href="{{ route('events.show', $notification->event) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View Event
                                                </a>
                                                <button 
                                                    class="btn btn-sm btn-outline-success" 
                                                    onclick="markNotificationAsRead({{ $notification->id }})">
                                                    <i class="fas fa-check"></i> Mark Read
                                                </button>
                                                <button 
                                                    class="btn btn-sm btn-outline-secondary" 
                                                    onclick="archiveNotification({{ $notification->id }})">
                                                    <i class="fas fa-archive"></i> Archive
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="text-muted">All caught up! ✨</h5>
                    <p class="text-muted">No pending notifications</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Styles -->
<style>
    .notification-card {
        transition: all 0.3s ease;
        border-left: 4px solid #e9ecef;
    }

    .notification-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .notification-card.border-warning {
        border-left-color: #ffc107 !important;
        background-color: rgba(255, 193, 7, 0.05);
    }

    .btn-group-vertical {
        gap: 0.25rem;
    }

    .btn-group-vertical .btn {
        border-radius: 0.375rem;
        padding: 0.4rem 0.6rem;
        font-size: 0.85rem;
    }
</style>

<!-- Scripts -->
<script>
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

function markAllAsRead() {
    if (confirm('Mark all notifications as read?')) {
        fetch('/advisor/notifications/mark-all-as-read', {
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
}

function archiveNotification(notificationId) {
    if (confirm('Archive this notification?')) {
        fetch(`/advisor/notifications/${notificationId}/archive`, {
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
}

function deleteNotification(notificationId) {
    if (confirm('Delete this notification permanently?')) {
        fetch(`/advisor/notifications/${notificationId}`, {
            method: 'DELETE',
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
}
</script>
@endsection
