@extends('layouts.app')

@section('title', 'Edit Event Proposal - UniClub Hub')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-0">Edit Event Proposal</h2>
            <p class="text-muted">Update your event proposal</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Event Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('events.update', $event) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="club_id" class="form-label">Select Club <span class="text-danger">*</span></label>
                            <select name="club_id" id="club_id" class="form-select @error('club_id') is-invalid @enderror" required>
                                <option value="">-- Choose a club --</option>
                                @foreach ($clubs as $club)
                                    <option value="{{ $club->id }}" {{ $event->club_id == $club->id ? 'selected' : '' }}>
                                        {{ $club->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('club_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Event Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" 
                                value="{{ old('title', $event->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Event Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                rows="4" required>{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="venue_id" class="form-label">Select Venue (Optional)</label>
                            <select name="venue_id" id="venue_id" class="form-select @error('venue_id') is-invalid @enderror">
                                <option value="">-- No Venue Selected --</option>
                                @foreach (\App\Models\Venue::where('is_active', true)->get() as $venue)
                                    <option value="{{ $venue->id }}" {{ $event->venue_id == $venue->id ? 'selected' : '' }}>
                                        {{ $venue->name }} (Capacity: {{ $venue->capacity }})
                                    </option>
                                @endforeach
                            </select>
                            @error('venue_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="proposed_date" class="form-label">Proposed Date <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="proposed_date" id="proposed_date" 
                                    class="form-control @error('proposed_date') is-invalid @enderror" 
                                    value="{{ old('proposed_date', $event->proposed_date->format('Y-m-d\TH:i')) }}" required>
                                @error('proposed_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="budget" class="form-label">Budget (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="budget" id="budget" class="form-control @error('budget') is-invalid @enderror" 
                                        value="{{ old('budget', $event->budget) }}" min="0" step="0.01">
                                </div>
                                @error('budget')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="expected_audience" class="form-label">Expected Audience (Optional)</label>
                            <input type="number" name="expected_audience" id="expected_audience" 
                                class="form-control @error('expected_audience') is-invalid @enderror" 
                                value="{{ old('expected_audience', $event->expected_audience) }}" min="1">
                            @error('expected_audience')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                Update Proposal
                            </button>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Status</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><strong>Current Status:</strong></p>
                    <span class="badge bg-secondary">Draft</span>
                    <hr>
                    <small class="text-muted">You can only edit proposals in draft status.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
