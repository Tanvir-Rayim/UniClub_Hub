@extends('layouts.app')

@section('title', 'Clubs - UniClub Hub')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>Available Clubs</h2>
        </div>
        <div class="col-md-4 text-end">
            @auth
                @if (Auth::user()->hasRole('admin'))
                    <a href="{{ route('clubs.create') }}" class="btn btn-primary">
                        + Create New Club
                    </a>
                @endif
            @endauth
        </div>
    </div>

    <div class="row">
        @forelse ($clubs as $club)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $club->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($club->description, 100) }}</p>
                        
                        <div class="mb-3">
                            <span class="badge bg-info">{{ $club->getActiveMembersCount() }} members</span>
                            @if ($club->advisors)
                                <span class="badge bg-secondary">{{ $club->advisors->name }}</span>
                            @endif
                        </div>

                        <a href="{{ route('clubs.show', $club) }}" class="btn btn-sm btn-primary w-100">
                            View Details
                        </a>
                    </div>
                    <div class="card-footer bg-light">
                        <small class="text-muted">Created {{ $club->created_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    No clubs available at the moment.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="row mt-4">
        <div class="col-12">
            {{ $clubs->links() }}
        </div>
    </div>
</div>
@endsection
