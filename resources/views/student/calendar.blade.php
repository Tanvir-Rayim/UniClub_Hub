@extends('layouts.app')

@section('title', 'Upcoming Events - UniClub Hub')

@section('content')
<div class="container-fluid px-4 py-5">

    {{-- Header --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="display-5 fw-bold mb-1">📅 Upcoming Events</h1>
                    <p class="text-muted fs-6">Browse and register for upcoming club events across campus</p>
                </div>
                <a href="{{ route('student.tickets') }}" class="btn btn-outline-primary btn-lg shadow-sm">
                    <i class="fas fa-ticket-alt me-2"></i> My Tickets
                </a>
            </div>
            <hr class="my-4">
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

    {{-- Events Grid --}}
    @if($events->count() > 0)
        <div class="row g-4">
            @foreach($events as $event)
                @php
                    $isRegistered  = in_array($event->id, $registeredEventIds);
                    $daysUntil     = now()->diffInDays($event->proposed_date, false);
                    $isThisWeek    = $daysUntil <= 7;
                    $isThisMonth   = $daysUntil <= 30;
                @endphp
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 event-card" style="border-radius: 16px; overflow: hidden;">

                        {{-- Colored top bar per club --}}
                        <div style="height: 6px; background: linear-gradient(90deg, #667eea, #764ba2);"></div>

                        <div class="card-body p-4">
                            {{-- Badges --}}
                            <div class="d-flex gap-2 mb-3 flex-wrap">
                                @if($isRegistered)
                                    <span class="badge px-3 py-2" style="background:#d4edda; color:#155724; font-size:0.75rem;">
                                        <i class="fas fa-check me-1"></i>Registered
                                    </span>
                                @endif
                                @if($isThisWeek)
                                    <span class="badge px-3 py-2" style="background:#fff3cd; color:#856404; font-size:0.75rem;">
                                        <i class="fas fa-fire me-1"></i>This Week
                                    </span>
                                @elseif($isThisMonth)
                                    <span class="badge px-3 py-2" style="background:#d1ecf1; color:#0c5460; font-size:0.75rem;">
                                        <i class="fas fa-calendar me-1"></i>This Month
                                    </span>
                                @endif
                            </div>

                            {{-- Title --}}
                            <h5 class="fw-bold mb-1" style="color:#2d3748;">{{ $event->title }}</h5>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-users me-1"></i>{{ $event->club->name }}
                            </p>

                            {{-- Description --}}
                            <p class="text-muted small mb-4" style="line-height:1.6;">
                                {{ Str::limit($event->description, 100) }}
                            </p>

                            {{-- Details --}}
                            <div class="row g-2 mb-4">
                                <div class="col-6">
                                    <div class="p-2 rounded" style="background:#f8f9fa;">
                                        <div class="small text-muted mb-1"><i class="fas fa-calendar-day me-1"></i>Date</div>
                                        <div class="fw-semibold small">{{ $event->proposed_date->format('M d, Y') }}</div>
                                        <div class="text-muted" style="font-size:0.7rem;">{{ $event->proposed_date->format('g:i A') }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 rounded" style="background:#f8f9fa;">
                                        <div class="small text-muted mb-1"><i class="fas fa-map-marker-alt me-1"></i>Venue</div>
                                        <div class="fw-semibold small">
                                            {{ $event->venue ? $event->venue->name : 'TBA' }}
                                        </div>
                                    </div>
                                </div>
                                @if($event->expected_audience)
                                <div class="col-6">
                                    <div class="p-2 rounded" style="background:#f8f9fa;">
                                        <div class="small text-muted mb-1"><i class="fas fa-chair me-1"></i>Capacity</div>
                                        <div class="fw-semibold small">{{ number_format($event->expected_audience) }}</div>
                                    </div>
                                </div>
                                @endif
                                <div class="col-6">
                                    <div class="p-2 rounded" style="background:#f8f9fa;">
                                        <div class="small text-muted mb-1"><i class="fas fa-clock me-1"></i>In</div>
                                        <div class="fw-semibold small text-primary">{{ $event->proposed_date->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Join Button --}}
                            @if($isRegistered)
                                <button class="btn w-100 py-2" disabled
                                    style="background:#d4edda; color:#155724; border-radius:10px; border:none; font-weight:600;">
                                    <i class="fas fa-check-circle me-2"></i>Already Registered
                                </button>
                            @else
                                <form action="{{ route('student.events.register', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn w-100 py-2 join-btn"
                                        style="background: linear-gradient(135deg, #667eea, #764ba2); color:#fff; border:none; border-radius:10px; font-weight:600; transition: all 0.2s;">
                                        <i class="fas fa-plus-circle me-2"></i>Join Event
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <div class="mb-4" style="font-size:4rem;">🎭</div>
            <h4 class="text-muted fw-semibold">No Upcoming Events</h4>
            <p class="text-muted">Check back soon — club events will appear here once approved by advisors.</p>
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-primary mt-2">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    @endif

    {{-- Back link --}}
    @if($events->count() > 0)
    <div class="mt-5">
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>
    @endif
</div>

<style>
    .event-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .event-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(102, 126, 234, 0.18) !important;
    }
    .join-btn:hover {
        opacity: 0.88;
        transform: scale(1.02);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
</style>
@endsection
