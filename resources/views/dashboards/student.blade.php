@extends('layouts.app')

@section('title', 'Student Dashboard - UniClub Hub')

@section('content')
<div class="container-fluid px-4 py-5">

    {{-- Header --}}
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="display-5 fw-bold mb-1">Student Dashboard 🎓</h1>
                    <p class="text-muted fs-6">Welcome back, {{ $user->name }}! Here is your campus life at a glance.</p>
                </div>
                <div class="text-end d-flex gap-2">
                    <a href="{{ route('student.events.calendar') }}" class="btn btn-primary btn-lg shadow-sm">
                        <i class="fas fa-calendar-alt me-2"></i>Upcoming Events
                    </a>
                    <a href="{{ route('clubs.index') }}" class="btn btn-outline-primary btn-lg shadow-sm">
                        <i class="fas fa-compass me-2"></i>Explore Clubs
                    </a>
                </div>
            </div>
            <hr class="my-4">
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #667eea; border-radius:14px;">
                <div class="card-body text-center p-4">
                    <div class="display-5 fw-bold text-primary mb-1">{{ $clubCount }}</div>
                    <p class="text-muted mb-0 fw-semibold">Clubs Joined</p>
                    <small class="text-muted">Active memberships</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #f5576c; border-radius:14px;">
                <div class="card-body text-center p-4">
                    <div class="display-5 fw-bold text-danger mb-1">{{ $pendingApplications }}</div>
                    <p class="text-muted mb-0 fw-semibold">Pending Applications</p>
                    <small class="text-muted">Awaiting club approval</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #43e97b; border-radius:14px;">
                <div class="card-body text-center p-4">
                    <div class="display-5 fw-bold text-success mb-1">{{ $registeredEvents }}</div>
                    <p class="text-muted mb-0 fw-semibold">Events Registered</p>
                    <small class="text-muted">Active tickets held</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100" style="border-top:4px solid #4facfe; border-radius:14px;">
                <div class="card-body text-center p-4">
                    <div class="display-5 fw-bold text-info mb-1">{{ $upcomingRegistered }}</div>
                    <p class="text-muted mb-0 fw-semibold">Upcoming Attended</p>
                    <small class="text-muted">Future events you'll attend</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Club limit warning --}}
    @if($clubCount >= 4)
        <div class="alert alert-warning shadow-sm mb-4" style="border-radius:12px;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Club Limit Reached:</strong> You are in {{ $clubCount }}/4 clubs. Leave a club before applying to a new one.
        </div>
    @elseif($clubCount >= 3)
        <div class="alert alert-info shadow-sm mb-4" style="border-radius:12px;">
            <i class="fas fa-info-circle me-2"></i>
            You are in {{ $clubCount }}/4 clubs — you can join <strong>{{ 4 - $clubCount }} more</strong>.
        </div>
    @endif

    <div class="row g-4">

        {{-- Left: Clubs + Upcoming Events --}}
        <div class="col-lg-8">

            {{-- Your Clubs --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius:16px; overflow:hidden;">
                <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center gap-3"
                     style="background:#fff; border-bottom:1px solid #eee;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-users text-primary me-2"></i>Your Clubs
                        <span class="badge bg-primary ms-2">{{ $clubCount }}/4</span>
                    </h5>
                    <div class="flex-grow-1 mx-lg-4">
                        <form action="{{ route('clubs.index') }}" method="GET" class="d-flex shadow-sm" style="border-radius: 8px; overflow: hidden;">
                            <input type="text" name="search" class="form-control form-control-sm border-0" placeholder="Search clubs..." style="background: #f8f9fa;">
                            <button type="submit" class="btn btn-sm btn-primary px-3 border-0">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>
                    <a href="{{ route('clubs.index') }}" class="btn btn-sm btn-outline-primary">Browse All</a>
                </div>
                <div class="card-body p-0">
                    @if ($clubs->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($clubs as $club)
                                <a href="{{ route('clubs.show', $club) }}"
                                   class="list-group-item list-group-item-action p-4 border-bottom border-light">
                                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 fw-bold text-dark">{{ $club->name }}</h6>
                                        <span class="badge bg-light text-secondary border">
                                            <i class="far fa-clock me-1"></i>
                                            Joined {{ $club->pivot->joined_at ? \Carbon\Carbon::parse($club->pivot->joined_at)->diffForHumans() : 'Recently' }}
                                        </span>
                                    </div>
                                    <p class="mb-0 text-muted small">{{ Str::limit($club->description, 120) }}</p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="display-6 text-muted mb-3"><i class="fas fa-folder-open"></i></div>
                            <h5 class="text-muted">No Clubs Yet</h5>
                            <p class="text-muted mb-4">You haven't joined any clubs yet. Get involved on campus!</p>
                            <a href="{{ route('clubs.index') }}" class="btn btn-outline-primary px-4">
                                Browse Available Clubs
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Upcoming Events Preview --}}
            <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
                <div class="card-header py-3 d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, #667eea, #764ba2); border:none;">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>Upcoming Events
                    </h5>
                    <a href="{{ route('student.events.calendar') }}" class="btn btn-sm btn-light text-primary fw-semibold">
                        View All
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($upcomingEvents->count() > 0)
                        @foreach($upcomingEvents as $event)
                            <div class="p-4 border-bottom border-light d-flex align-items-center gap-3">
                                <div class="text-center flex-shrink-0"
                                     style="width:52px; height:52px; background:linear-gradient(135deg,#667eea,#764ba2); border-radius:12px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                    <div class="text-white fw-bold" style="font-size:1rem; line-height:1;">{{ $event->proposed_date->format('d') }}</div>
                                    <div class="text-white" style="font-size:0.65rem; line-height:1.2;">{{ $event->proposed_date->format('M') }}</div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold">{{ $event->title }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-users me-1"></i>{{ $event->club->name }}
                                        &nbsp;·&nbsp;
                                        <i class="fas fa-clock me-1"></i>{{ $event->proposed_date->diffForHumans() }}
                                    </small>
                                </div>
                                <a href="{{ route('student.events.calendar') }}" class="btn btn-sm btn-outline-primary flex-shrink-0">
                                    Join
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-times fa-2x mb-2 d-block"></i>
                            No upcoming events right now.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Account Info + Quick Actions --}}
        <div class="col-lg-4">

            {{-- Account Info --}}
            <div class="card border-0 shadow-sm mb-4 bg-light" style="border-radius:16px;">
                <div class="card-header py-3 bg-light" style="border-bottom: 2px solid #e2e8f0;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-id-card text-secondary me-2"></i>Account Info
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-3">
                            <small class="text-muted d-block mb-1">University ID</small>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-hashtag text-secondary me-2"></i>
                                <span class="fw-semibold">{{ $user->university_id }}</span>
                            </div>
                        </li>
                        <li class="mb-3">
                            <small class="text-muted d-block mb-1">Email Address</small>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope text-secondary me-2"></i>
                                <span class="fw-semibold">{{ $user->email }}</span>
                            </div>
                        </li>
                        <li>
                            <small class="text-muted d-block mb-1">Account Role</small>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-graduate text-secondary me-2"></i>
                                <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card border-0 shadow-sm" style="border-radius:16px;">
                <div class="card-header py-3" style="background: #fffbeb; border-bottom: 2px solid #fef3c7; border-radius: 16px 16px 0 0;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-bolt text-warning me-2"></i>Quick Actions
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('student.events.calendar') }}" class="btn btn-primary text-start px-3 py-2" style="border-radius:10px;">
                            <i class="fas fa-calendar-alt me-2" style="width:20px;"></i>Upcoming Events
                        </a>
                        <a href="{{ route('student.tickets') }}" class="btn btn-outline-success text-start px-3 py-2" style="border-radius:10px;">
                            <i class="fas fa-ticket-alt me-2" style="width:20px;"></i>My Tickets
                            @if($registeredEvents > 0)
                                <span class="badge bg-success float-end">{{ $registeredEvents }}</span>
                            @endif
                        </a>
                        <a href="{{ route('attendance.pending') }}" class="btn btn-outline-info text-start px-3 py-2" style="border-radius:10px;">
                            <i class="fas fa-check-circle me-2" style="width:20px;"></i>Mark Attendance
                        </a>
                        <a href="{{ route('clubs.index') }}" class="btn btn-outline-primary text-start px-3 py-2" style="border-radius:10px;">
                            <i class="fas fa-search me-2" style="width:20px;"></i>Browse Clubs
                        </a>
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary text-start px-3 py-2" style="border-radius:10px;">
                            <i class="fas fa-user me-2" style="width:20px;"></i>View Profile
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary text-start px-3 py-2" style="border-radius:10px;">
                            <i class="fas fa-user-edit me-2" style="width:20px;"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-500 { font-weight: 500; }
    .card { transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; }
    .card:hover { transform: translateY(-2px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.12) !important; }
    .list-group-item-action { transition: background-color 0.15s; }
    .list-group-item-action:hover { background-color: #f0f4ff; }
</style>
@endsection