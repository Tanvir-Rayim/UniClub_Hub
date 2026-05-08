@extends('layouts.app')

@section('title', 'Edit Member - ' . $club->name . ' - UniClub Hub')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Member - {{ $club->name }}</h4>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h5>{{ $member->name }}</h5>
                        <p class="text-muted">{{ $member->university_id }} | {{ $member->email }}</p>
                    </div>

                    <form method="POST" action="{{ route('clubs.members.update', [$club, $member]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="position" class="form-label">Position</label>
                            <select id="position" name="position" class="form-select" required>
                                <option value="">Select Position</option>
                                <option value="member" @selected($position === 'member')>Member</option>
                                <option value="secretary" @selected($position === 'secretary')>Secretary</option>
                                <option value="treasurer" @selected($position === 'treasurer')>Treasurer</option>
                                <option value="vice_president" @selected($position === 'vice_president')>Vice President</option>
                            </select>
                            @error('position')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                            <a href="{{ route('clubs.members', $club) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
