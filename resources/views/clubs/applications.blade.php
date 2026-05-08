@extends('layouts.app')

@section('title', 'Membership Applications - UniClub Hub')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>{{ $club->name }} - Membership Applications</h2>
            <p class="text-muted">Review and approve/reject membership applications</p>
        </div>
    </div>

    @if ($applications->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>University ID</th>
                                <th>Email</th>
                                <th>Applied On</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $member)
                                <tr>
                                    <td>{{ $member->name }}</td>
                                    <td>{{ $member->university_id }}</td>
                                    <td>{{ $member->email }}</td>
                                    <td>{{ $member->pivot->created_at->diffForHumans() }}</td>
                                    <td>
                                        <select id="position-{{ $member->id }}" class="form-select form-select-sm" style="width: auto;">
                                            <option value="">Select Position</option>
                                            <option value="member">Member</option>
                                            <option value="secretary">Secretary</option>
                                            <option value="treasurer">Treasurer</option>
                                            <option value="vice_president">Vice President</option>
                                        </select>
                                    </td>
                                    <td>
                                        <form method="POST" action="{{ route('clubs.member-status', [$club, $member]) }}" style="display:inline;" class="approve-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <input type="hidden" name="position" class="position-input" value="">
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form method="POST" action="{{ route('clubs.member-status', [$club, $member]) }}" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                {{ $applications->links() }}
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info text-center" role="alert">
                    No pending applications at the moment.
                </div>
                <div class="text-center">
                    <a href="{{ route('clubs.show', $club) }}" class="btn btn-outline-primary">Back to Club</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.approve-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            // Get the select element in the same row
            const selectElement = this.closest('tr').querySelector('select');
            const positionInput = this.querySelector('.position-input');
            positionInput.value = selectElement.value;
        });
    });
});
</script>
