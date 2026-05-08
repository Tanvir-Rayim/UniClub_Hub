@extends('layouts.app')

@section('title', 'Edit Club - UniClub Hub')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Club</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('clubs.update', $club) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Club Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $club->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4">{{ old('description', $club->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="faculty_advisor_id" class="form-label">Faculty Advisor</label>
                            <select class="form-control @error('faculty_advisor_id') is-invalid @enderror" 
                                    id="faculty_advisor_id" name="faculty_advisor_id">
                                <option value="">-- Select Advisor --</option>
                                @foreach ($advisors as $advisor)
                                    <option value="{{ $advisor->id }}" {{ old('faculty_advisor_id', $club->faculty_advisor_id) == $advisor->id ? 'selected' : '' }}>
                                        {{ $advisor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('faculty_advisor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Club</button>
                            <a href="{{ route('clubs.show', $club) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
