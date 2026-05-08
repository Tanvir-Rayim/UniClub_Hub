@extends('layouts.app')

@section('title', 'My Event Proposals - UniClub Hub')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-0">My Event Proposals</h2>
            <p class="text-muted">Manage your club event proposals</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('events.create') }}" class="btn btn-success">
                + Create New Proposal
            </a>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Event Proposals 
                        <span class="badge bg-info">{{ $eventCount }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if ($events->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Event Title</th>
                                        <th>Club</th>
                                        <th>Venue</th>
                                        <th>Proposed Date</th>
                                        <th>Budget</th>
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
                                                @if ($event->venue)
                                                    {{ $event->venue->name }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $event->proposed_date->format('M d, Y') }}
                                            </td>
                                            <td>
                                                @if ($event->budget)
                                                    ${{ number_format($event->budget, 2) }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($event->status === 'pending_approval')
                                                    <span class="badge bg-warning">Pending Approval</span>
                                                @elseif ($event->status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif ($event->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($event->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-info">
                                                    View
                                                </a>
                                                <a href="{{ route('events.budget.show', $event) }}" class="btn btn-sm btn-success" title="Budget">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </a>
                                                @if ($event->status === 'draft')
                                                    <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-warning">
                                                        Edit
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted mb-3">You haven't created any event proposals yet.</p>
                            <a href="{{ route('events.create') }}" class="btn btn-success">
                                Create Your First Proposal
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <a href="{{ route('executive.dashboard') }}" class="btn btn-outline-secondary">
                Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection
