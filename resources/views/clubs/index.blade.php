@extends('layouts.app')

@section('title', 'Clubs - UniClub Hub')

@section('content')
<div class="container">
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);">
                <div class="card-body p-4">
                    <form action="{{ route('clubs.index') }}" method="GET" class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-white border-end-0" style="border-radius: 10px 0 0 10px;">
                                    <i class="fas fa-search text-primary"></i>
                                </span>
                                <input type="text" name="search" class="form-control border-start-0 py-3" 
                                       placeholder="Search for clubs by name..." value="{{ request('search') }}"
                                       style="border-radius: 0 10px 10px 0; font-size: 1rem;">
                            </div>
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1 shadow-sm" style="border-radius: 10px;">
                                Search Clubs
                            </button>
                            @if(request('search'))
                                <a href="{{ route('clubs.index') }}" class="btn btn-outline-secondary btn-lg shadow-sm" style="border-radius: 10px;">
                                    Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
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
