@extends('layouts.app')

@section('title', 'Event Proposal Notifications - UniClub Hub')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-0">Event Proposal Notifications</h2>
            <p class="text-muted">Review pending event proposals from clubs under your supervision</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pending Event Proposals 
                        <span class="badge bg-warning">{{ $eventCount }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if ($events->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Event Name</th>
                                        <th>Club</th>
                                        <th>Description</th>
                                        <th>Proposed Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($events as $event)
                                        <tr>
                                            <td>
                                                <strong>{{ $event->title }}</strong>
                                            </td>
                                            <td>
                                                {{ $event->club->name }}
                                            </td>
                                            <td>
                                                {{ Str::limit($event->description, 50) }}
                                            </td>
                                            <td>
                                                {{ $event->proposed_date->format('M d, Y') }}
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">Pending Approval</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-info me-1">
                                                    View
                                                </a>
                                                <form action="{{ route('events.approve', $event) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success me-1">
                                                        Approve
                                                    </button>
                                                </form>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $event->id }}">
                                                    Reject
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $event->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Event Proposal</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('events.reject', $event) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="rejection_reason" class="form-label">Reason for Rejection (Optional)</label>
                                                                <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">Reject Proposal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted mb-0">No pending event proposals at this time.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <a href="{{ route('advisor.dashboard') }}" class="btn btn-outline-secondary">
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
