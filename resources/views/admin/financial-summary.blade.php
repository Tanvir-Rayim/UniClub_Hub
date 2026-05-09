@extends('layouts.app')
 
@section('title', 'Financial Summary - Admin Dashboard')
 
@section('content')
<div class="container-fluid px-4 py-5">
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="display-5 fw-bold mb-1">Financial Summary 💰</h1>
                    <p class="text-muted fs-6">Global budget and fund release overview across all clubs.</p>
                </div>
                <div class="text-end">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>
            <hr class="my-4">
        </div>
    </div>
 
    <div class="row g-4 mb-5">
        <div class="col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #10b981;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted mb-0">Total Approved Budget</h6>
                        <i class="fas fa-check-circle text-success fs-4"></i>
                    </div>
                    <h2 class="fw-bold mb-1">${{ number_format($totalApprovedBudget, 2) }}</h2>
                    <p class="text-muted small mb-0">Combined budget of all officially approved event proposals.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-top: 4px solid #3b82f6;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-muted mb-0">Total Released Funds</h6>
                        <i class="fas fa-hand-holding-usd text-primary fs-4"></i>
                    </div>
                    <h2 class="fw-bold mb-1">${{ number_format($totalReleasedBudget, 2) }}</h2>
                    <p class="text-muted small mb-0">Funds authorized for release by faculty advisors.</p>
                </div>
            </div>
        </div>
    </div>
 
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header py-3 bg-white border-bottom">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-university me-2 text-primary"></i>Club-wise Financial Breakdown</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Club Name</th>
                                    <th>Approved Events</th>
                                    <th>Total Budget</th>
                                    <th>Released Funds</th>
                                    <th>Pending Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clubData as $club)
                                    <tr>
                                        <td class="ps-4"><strong>{{ $club['name'] }}</strong></td>
                                        <td>{{ $club['total_approved_events'] }}</td>
                                        <td>${{ number_format($club['total_budget'], 2) }}</td>
                                        <td>
                                            <div class="progress" style="height: 6px; width: 100px;">
                                                @php
                                                    $percent = $club['total_budget'] > 0 ? ($club['released_budget'] / $club['total_budget']) * 100 : 0;
                                                @endphp
                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%"></div>
                                            </div>
                                            <small class="text-muted">${{ number_format($club['released_budget'], 2) }}</small>
                                        </td>
                                        <td>
                                            @if($club['pending_release'] > 0)
                                                <span class="badge bg-warning text-dark">{{ $club['pending_release'] }} Pending Release</span>
                                            @else
                                                <span class="badge bg-light text-muted border">None</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
 
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header py-3 bg-white border-bottom">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-history me-2 text-info"></i>Recent Approved Events</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($recentEvents as $event)
                            <div class="list-group-item p-3">
                                <div class="d-flex w-100 justify-content-between mb-1">
                                    <h6 class="mb-0 fw-bold">{{ $event->title }}</h6>
                                    <small class="text-muted">${{ number_format($event->budget, 0) }}</small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-primary">{{ $event->club->name }}</small>
                                    @if($event->financial_release_status)
                                        <span class="badge bg-success-soft text-success border border-success px-2 py-1" style="font-size: 0.7rem;">Released</span>
                                    @else
                                        <span class="badge bg-warning-soft text-warning border border-warning px-2 py-1" style="font-size: 0.7rem;">Awaiting Release</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="{{ route('events.index') }}" class="text-decoration-none small">View All Events <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
 
<style>
    .bg-success-soft { background-color: #ecfdf5; }
    .bg-warning-soft { background-color: #fffbeb; }
    .table thead th { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; }
</style>
@endsection
