@extends('layouts.app')

@section('title', 'Admin Dashboard - UniClub Hub')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="display-5 fw-bold mb-1">Administrator Dashboard 🛡️</h1>
                    <p class="text-muted fs-6">System Overview & Management</p>
                </div>
                <div class="text-end">
                    <a href="{{ route('clubs.create') }}" class="btn btn-primary btn-lg mb-2">
                        <i class="fas fa-plus-circle me-2"></i> Create New Club
                    </a>
                </div>
            </div>
            <hr class="my-4">
        </div>
    </div>

    <!-- Key Statistics -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #667eea;">
                <div class="card-body text-center">
                    <div class="display-5 fw-bold text-primary mb-2">{{ $totalUsers }}</div>
                    <p class="text-muted mb-0">Total Users</p>
                    <small class="text-muted">Registered in system</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #f5576c;">
                <div class="card-body text-center">
                    <div class="display-5 fw-bold text-danger mb-2">{{ $totalClubs }}</div>
                    <p class="text-muted mb-0">Total Clubs</p>
                    <small class="text-muted">Active and inactive</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #4facfe;">
                <div class="card-body text-center">
                    <div class="display-5 fw-bold text-info mb-2">{{ $totalAdvisors }}</div>
                    <p class="text-muted mb-0">Faculty Advisors</p>
                    <small class="text-muted">Advising clubs</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #43e97b;">
                <div class="card-body text-center">
                    <div class="display-5 fw-bold text-success mb-2">{{ $totalEvents }}</div>
                    <p class="text-muted mb-0">Total Events</p>
                    <small class="text-muted">
                        <span class="badge bg-warning text-dark">{{ $pendingProposalsCount }} pending</span>
                        <span class="badge bg-success">{{ $advisorApprovedCount }} approved</span>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Bar -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3" style="background: #f8f9fa; border-bottom: 2px solid #eee;">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-bolt me-2 text-warning"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary w-100 py-2">
                                <i class="fas fa-users-cog me-2"></i>
                                <span class="d-block small">Manage Users</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('clubs.index') }}" class="btn btn-outline-info w-100 py-2">
                                <i class="fas fa-users me-2"></i>
                                <span class="d-block small">All Clubs</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('admin.venues.index') }}" class="btn btn-outline-success w-100 py-2">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                <span class="d-block small">Manage Venues</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('events.index') }}" class="btn btn-outline-danger w-100 py-2">
                                <i class="fas fa-calendar-alt me-2"></i>
                                <span class="d-block small">All Events</span>
                                @if($pendingProposalsCount > 0)
                                    <span class="badge bg-warning text-dark">{{ $pendingProposalsCount }} pending</span>
                                @endif
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-warning w-100 py-2">
                                <i class="fas fa-user me-2"></i>
                                <span class="d-block small">My Profile</span>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-6">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary w-100 py-2">
                                <i class="fas fa-edit me-2"></i>
                                <span class="d-block small">Edit Profile</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="row g-4">
        <!-- Management Section -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background: #eef2ff; border-bottom: 2px solid #e2e8f0;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>System Statistics
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Metric</th>
                                    <th>Value</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-4"><strong>Total Users</strong></td>
                                    <td><span class="badge bg-primary rounded-pill px-3">{{ $totalUsers }}</span></td>
                                    <td class="text-muted"><small>All registered users across all roles</small></td>
                                </tr>
                                <tr>
                                    <td class="ps-4"><strong>Total Clubs</strong></td>
                                    <td><span class="badge bg-success rounded-pill px-3">{{ $totalClubs }}</span></td>
                                    <td class="text-muted"><small>Active and inactive clubs in UniClub Hub</small></td>
                                </tr>
                                <tr>
                                    <td class="ps-4"><strong>Faculty Advisors</strong></td>
                                    <td><span class="badge bg-info rounded-pill px-3">{{ $totalAdvisors }}</span></td>
                                    <td class="text-muted"><small>Faculty members appointed for clubs</small></td>
                                </tr>
                                <tr>
                                    <td class="ps-4"><strong>Events</strong></td>
                                    <td><span class="badge bg-warning text-dark rounded-pill px-3">{{ $totalEvents }}</span></td>
                                    <td class="text-muted"><small>Total event proposals and approved events</small></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Management Tools Module -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header py-3" style="background: #f0f9ff; border-bottom: 2px solid #bae6fd;">
                            <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-users me-2 text-info"></i>Club Management</h5>
                        </div>
                        <div class="card-body text-center py-4">
                            <i class="fas fa-users-class text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-4 small">Oversee all club operations, creation, and memberships across the university.</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('clubs.create') }}" class="btn btn-primary">Create New Club</a>
                                <a href="{{ route('clubs.index') }}" class="btn btn-outline-secondary">View All Clubs</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header py-3" style="background: #fff1f2; border-bottom: 2px solid #fecdd3;">
                            <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-user-shield me-2 text-danger"></i>User Management</h5>
                        </div>
                        <div class="card-body text-center py-4">
                            <i class="fas fa-id-card text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-4 small">Manage registered users, their roles, and enforce system security protocols.</p>
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-danger">Manage Users</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Event Proposals --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #f5a623;">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #f5a623, #f7c26b); border-bottom: none;">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-hourglass-half me-2"></i>Pending Event Proposals
                        <span class="badge bg-white text-warning ms-2">{{ $pendingProposalsCount }}</span>
                    </h5>
                    <a href="{{ route('events.index') }}" class="btn btn-sm btn-light text-warning fw-semibold">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($pendingProposals->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Event</th>
                                        <th>Club</th>
                                        <th>Submitted By</th>
                                        <th>Proposed Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pendingProposals as $event)
                                        <tr>
                                            <td class="ps-3"><strong>{{ $event->title }}</strong></td>
                                            <td><span class="badge bg-info text-dark">{{ $event->club->name }}</span></td>
                                            <td>{{ $event->creator->name ?? '—' }}</td>
                                            <td>{{ $event->proposed_date->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                            <p class="mb-0">No pending event proposals.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Advisor-Approved Events --}}
            <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid #43e97b;">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #43e97b, #38f9d7); border-bottom: none;">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-check-circle me-2"></i>Advisor-Approved Events
                        <span class="badge bg-white text-success ms-2">{{ $advisorApprovedCount }}</span>
                    </h5>
                    <a href="{{ route('events.index') }}" class="btn btn-sm btn-light text-success fw-semibold">View All</a>
                </div>
                <div class="card-body p-0">
                    @if($advisorApprovedEvents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Event</th>
                                        <th>Club</th>
                                        <th>Submitted By</th>
                                        <th>Event Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($advisorApprovedEvents as $event)
                                        <tr>
                                            <td class="ps-3"><strong>{{ $event->title }}</strong></td>
                                            <td><span class="badge bg-info text-dark">{{ $event->club->name }}</span></td>
                                            <td>{{ $event->creator->name ?? '—' }}</td>
                                            <td>{{ $event->proposed_date->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-success">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-calendar-times text-muted fa-2x mb-2"></i>
                            <p class="mb-0">No advisor-approved events yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar: Info & Capabilities -->

        <div class="col-lg-4">
            <!-- Account Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3" style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-user-circle me-2 text-primary"></i>Admin Identity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="small text-muted d-block mb-1">Full Name</label>
                            <p class="fw-500 mb-0">{{ auth()->user()->name }}</p>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted d-block mb-1">University ID</label>
                            <p class="fw-500 mb-0">{{ auth()->user()->university_id }}</p>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted d-block mb-1">Email Address</label>
                            <p class="fw-500 mb-0">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted d-block mb-1">Role Configuration</label>
                            <span class="badge bg-danger">Administrator</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Key Features -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3" style="background: #f1f5f9; border-bottom: 2px solid #cbd5e1;">
                    <h5 class="mb-0 text-dark fw-bold">
                        <i class="fas fa-cogs me-2 text-secondary"></i>Admin Capabilities
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                <div>
                                    <small class="fw-500">Role Management</small>
                                    <p class="small text-muted mb-0">Assign or revoke executive/advisor roles</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                <div>
                                    <small class="fw-500">Venue Management</small>
                                    <p class="small text-muted mb-0">Add and configure campus venues</p>
                                </div>
                            </div>
                        </li>
                        <li class="mb-3">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-success me-2 mt-1"></i>
                                <div>
                                    <small class="fw-500">System Oversight</small>
                                    <p class="small text-muted mb-0">Full access to all clubs and events</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-500 {
        font-weight: 500;
    }
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
