@extends('layouts.app')

@section('title', $club->name . ' - Members - UniClub Hub')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2>{{ $club->name }} - Members</h2>
            <p class="text-muted">Manage club members and their positions</p>
        </div>
        <div class="col-md-4 text-end">
            @if ($canEditMembers)
                <a href="{{ route('clubs.members.download-pdf', $club) }}" class="btn btn-success me-2">
                    <i class="fas fa-download me-1"></i> Download PDF
                </a>
            @endif
            <a href="{{ route('clubs.show', $club) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Club
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($members->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>University ID</th>
                                <th>Email</th>
                                <th>Position</th>
                                <th>Joined</th>
                                @if ($canEditMembers)
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($members as $member)
                                <tr>
                                    <td>
                                        <strong>{{ $member->name }}</strong>
                                    </td>
                                    <td>{{ $member->university_id }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>
                                        @if ($member->pivot->position)
                                            <span class="badge bg-info">
                                                {{ ucwords(str_replace('_', ' ', $member->pivot->position)) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($member->pivot->joined_at)
                                            @if (is_string($member->pivot->joined_at))
                                                {{ \Carbon\Carbon::parse($member->pivot->joined_at)->format('M d, Y') }}
                                            @else
                                                {{ $member->pivot->joined_at->format('M d, Y') }}
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    @if ($canEditMembers)
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('clubs.members.edit', [$club, $member]) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form method="POST" action="{{ route('clubs.members.remove', [$club, $member]) }}" style="display:inline;" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Remove" onclick="return confirm('Are you sure you want to remove this member?');">
                                                        <i class="fas fa-trash"></i> Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $members->links() }}
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    No approved members yet.
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
