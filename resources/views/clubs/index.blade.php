@extends('layouts.app')

@section('title', 'Clubs - UniClub Hub')

@section('content')
<div class="container">
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm" style="border-radius: 15px; background: #fff;">
                <div class="card-body p-4">
                    <form action="{{ route('clubs.index') }}" method="GET" class="row g-3">
                        <div class="col-lg-4 col-md-12">
                            <label class="form-label small fw-bold text-muted">Search Clubs</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-primary"></i></span>
                                <input type="text" name="search" class="form-control border-start-0" 
                                       placeholder="Keyword (name, description...)" value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small fw-bold text-muted">Advisor</label>
                            <select name="advisor_id" class="form-select">
                                <option value="">All Advisors</option>
                                @foreach($advisors as $advisor)
                                    <option value="{{ $advisor->id }}" {{ request('advisor_id') == $advisor->id ? 'selected' : '' }}>
                                        {{ $advisor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <label class="form-label small fw-bold text-muted">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Recently Added</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name (A-Z)</option>
                                <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Most Members</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-12 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                                Filter
                            </button>
                            @if(request()->anyFilled(['search', 'advisor_id', 'sort']))
                                <a href="{{ route('clubs.index') }}" class="btn btn-outline-secondary shadow-sm">
                                    <i class="fas fa-times"></i>
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
