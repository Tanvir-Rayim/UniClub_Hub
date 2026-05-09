@extends('layouts.app')

@section('title', $isAdmin ? 'All Events - UniClub Hub' : 'My Event Proposals - UniClub Hub')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            @if($isAdmin)
                <h2 class="mb-0">All Events 📋</h2>
                <p class="text-muted">System-wide overview of all event proposals and approved events</p>
            @else
                <h2 class="mb-0">My Event Proposals</h2>
                <p class="text-muted">Manage your club event proposals</p>
            @endif
        </div>
        <div class="col-md-4 text-end">
            @if(!$isAdmin)
                <a href="{{ route('events.create') }}" class="btn btn-success">
                    + Create New Proposal
                </a>
            @endif
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

    {{-- Admin: summary badges --}}
    @if($isAdmin)
    <div class="row g-3 mb-4">
        <div class="col-auto">
            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                ⏳ Pending: {{ $events->where('status', 'pending_approval')->count() }}
            </span>
        </div>
        <div class="col-auto">
            <span class="badge bg-success fs-6 px-3 py-2">
                ✅ Advisor Approved: {{ $events->where('advisor_approval_status', 'approved')->count() }}
            </span>
        </div>
        <div class="col-auto">
            <span class="badge bg-danger fs-6 px-3 py-2">
                ❌ Rejected: {{ $events->where('status', 'rejected')->count() }}
            </span>
        </div>
        <div class="col-auto">
            <span class="badge bg-primary fs-6 px-3 py-2">
                📅 Total: {{ $eventCount }}
            </span>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">
                        {{ $isAdmin ? 'All Event Proposals & Approved Events' : 'Event Proposals' }}
                        <span class="badge bg-info">{{ $eventCount }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if ($events->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Event Title</th>
                                        <th>Club</th>
                                        <th>Venue</th>
                                        <th>Proposed Date</th>
                                        <th>Budget</th>
                                        @if($isAdmin)
                                            <th>Submitted By</th>
                                            <th>Advisor Decision</th>
                                        @endif
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($events as $event)
                                        <tr>
                                            <td class="ps-3">
                                                <strong>{{ $event->title }}</strong>
                                            </td>
                                            <td>{{ $event->club->name }}</td>
                                            <td>
                                                @if ($event->venue)
                                                    {{ $event->venue->name }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ $event->proposed_date->format('M d, Y') }}</td>
                                            <td>
                                                @if ($event->budget)
                                                    ${{ number_format($event->budget, 2) }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            @if($isAdmin)
                                                <td>
                                                    @if($event->creator)
                                                        <span class="badge bg-light text-dark border">{{ $event->creator->name }}</span>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($event->advisor_approval_status === 'approved')
                                                        <span class="badge bg-success">✅ Approved</span>
                                                    @elseif ($event->advisor_approval_status === 'rejected')
                                                        <span class="badge bg-danger">❌ Rejected</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">⏳ Pending</span>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>
                                                @if ($event->status === 'pending_approval')
                                                    <span class="badge bg-warning text-dark">Pending Approval</span>
                                                @elseif ($event->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif ($event->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($event->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    @if(!$isAdmin)
                                                        <a href="{{ route('events.budget.show', $event) }}" class="btn btn-sm btn-success" title="Budget">
                                                            <i class="fas fa-money-bill-wave"></i> Budget
                                                        </a>
                                                        @if ($event->status === 'draft')
                                                            <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-warning">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            @if($isAdmin)
                                <p class="text-muted mb-0">No events have been submitted yet.</p>
                            @else
                                <p class="text-muted mb-3">You haven't created any event proposals yet.</p>
                                <a href="{{ route('events.create') }}" class="btn btn-success">
                                    Create Your First Proposal
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            @if($isAdmin)
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    ← Back to Admin Dashboard
                </a>
            @else
                <a href="{{ route('executive.dashboard') }}" class="btn btn-outline-secondary">
                    ← Back to Dashboard
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
