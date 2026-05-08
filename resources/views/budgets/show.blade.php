@extends('layouts.app')

@section('title', $event->title . ' - Budget - UniClub Hub')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2>{{ $event->title }} - Budget & Expenses</h2>
            <p class="text-muted">Manage event budget and track expenses</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <!-- Budget Overview -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Budget Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="stat-card text-center">
                                <h6 class="text-muted">Total Estimated</h6>
                                <h3 class="text-primary">${{ number_format($totalEstimated, 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card text-center">
                                <h6 class="text-muted">Total Expenses</h6>
                                <h3 class="text-success">${{ number_format($totalExpenses, 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stat-card text-center">
                                <h6 class="text-muted">Remaining</h6>
                                <h3 class="{{ $totalEstimated - $totalExpenses >= 0 ? 'text-info' : 'text-danger' }}">
                                    ${{ number_format($totalEstimated - $totalExpenses, 2) }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Items -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Budget Items</h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addBudgetModal">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>
                <div class="card-body">
                    @if ($budgetItems->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Description</th>
                                        <th>Estimated Amount</th>
                                        <th>Actual Amount</th>
                                        <th>Difference</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($budgetItems as $item)
                                        <tr>
                                            <td>
                                                <strong>{{ $item->item_description }}</strong>
                                                @if ($item->notes)
                                                    <br><small class="text-muted">{{ $item->notes }}</small>
                                                @endif
                                            </td>
                                            <td>${{ number_format($item->estimated_amount, 2) }}</td>
                                            <td>
                                                @if ($item->actual_amount)
                                                    ${{ number_format($item->actual_amount, 2) }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->actual_amount)
                                                    @php
                                                        $diff = $item->actual_amount - $item->estimated_amount;
                                                    @endphp
                                                    <span class="badge {{ $diff >= 0 ? 'bg-danger' : 'bg-success' }}">
                                                        {{ $diff >= 0 ? '+' : '' }}${{ number_format($diff, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-warning edit-budget-btn" 
                                                    data-id="{{ $item->id }}"
                                                    data-description="{{ $item->item_description }}"
                                                    data-estimated="{{ $item->estimated_amount }}"
                                                    data-actual="{{ $item->actual_amount }}"
                                                    data-notes="{{ $item->notes }}"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#editBudgetModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form method="POST" action="{{ route('events.budget.delete', [$event, $item]) }}" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this budget item?');">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No budget items added yet.</p>
                    @endif
                </div>
            </div>

            <!-- Expense Proofs -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Expense Proofs</h5>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#uploadExpenseModal">
                        <i class="fas fa-cloud-upload-alt"></i> Upload Receipt
                    </button>
                </div>
                <div class="card-body">
                    @if ($expenseProofs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th>Type</th>
                                        <th>Budget Item</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expenseProofs as $proof)
                                        <tr>
                                            <td>
                                                <strong>{{ $proof->description }}</strong>
                                                @if ($proof->notes)
                                                    <br><small class="text-muted">{{ $proof->notes }}</small>
                                                @endif
                                            </td>
                                            <td><strong>${{ number_format($proof->amount, 2) }}</strong></td>
                                            <td>
                                                <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $proof->file_type)) }}</span>
                                            </td>
                                            <td>
                                                @if ($proof->budgetItem)
                                                    {{ $proof->budgetItem->item_description }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $proof->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('events.expenses.download', [$event, $proof]) }}" 
                                                    class="btn btn-sm btn-info" 
                                                    title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <form method="POST" action="{{ route('events.expenses.delete', [$event, $proof]) }}" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this proof?');">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No expense proofs uploaded yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Event Details</h5>
                </div>
                <div class="card-body small">
                    <p><strong>Club:</strong> {{ $event->club->name }}</p>
                    <p><strong>Status:</strong> 
                        @if ($event->status === 'pending_approval')
                            <span class="badge bg-warning">Pending</span>
                        @elseif ($event->status === 'approved')
                            <span class="badge bg-success">Approved</span>
                        @elseif ($event->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </p>
                    <p><strong>Proposed Date:</strong> {{ $event->proposed_date->format('M d, Y') }}</p>
                    <p><strong>Venue:</strong> {{ $event->venue?->name ?? 'Not assigned' }}</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('events.show', $event) }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-arrow-left"></i> Back to Event
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Budget Item Modal -->
<div class="modal fade" id="addBudgetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('events.budget.store', $event) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Budget Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="item_description" class="form-label">Description</label>
                        <input type="text" class="form-control @error('item_description') is-invalid @enderror" 
                            id="item_description" name="item_description" required>
                        @error('item_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="estimated_amount" class="form-label">Estimated Amount</label>
                        <input type="number" class="form-control @error('estimated_amount') is-invalid @enderror" 
                            id="estimated_amount" name="estimated_amount" step="0.01" min="0" required>
                        @error('estimated_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Budget Item Modal -->
<div class="modal fade" id="editBudgetModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editBudgetForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Budget Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_item_description" class="form-label">Description</label>
                        <input type="text" class="form-control" id="edit_item_description" name="item_description" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_estimated_amount" class="form-label">Estimated Amount</label>
                        <input type="number" class="form-control" id="edit_estimated_amount" name="estimated_amount" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_actual_amount" class="form-label">Actual Amount (Optional)</label>
                        <input type="number" class="form-control" id="edit_actual_amount" name="actual_amount" step="0.01" min="0">
                    </div>
                    <div class="mb-3">
                        <label for="edit_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="edit_notes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload Expense Modal -->
<div class="modal fade" id="uploadExpenseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('events.expenses.store', $event) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload Expense Proof</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" 
                            id="description" name="description" required>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                            id="amount" name="amount" step="0.01" min="0" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="budget_item_id" class="form-label">Link to Budget Item (Optional)</label>
                        <select class="form-select" id="budget_item_id" name="budget_item_id">
                            <option value="">-- Not Linked --</option>
                            @foreach ($budgetItems as $item)
                                <option value="{{ $item->id }}">{{ $item->item_description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="file_type" class="form-label">Document Type</label>
                        <select class="form-select @error('file_type') is-invalid @enderror" id="file_type" name="file_type" required>
                            <option value="">Select Type</option>
                            <option value="receipt">Receipt</option>
                            <option value="invoice">Invoice</option>
                            <option value="proof_of_purchase">Proof of Purchase</option>
                            <option value="other">Other</option>
                        </select>
                        @error('file_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">File (PDF, JPG, PNG - Max 5MB)</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" 
                            id="file" name="file" accept=".pdf,.jpg,.jpeg,.png,.gif" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="expense_notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="expense_notes" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Upload Proof</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.edit-budget-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const id = this.dataset.id;
        const description = this.dataset.description;
        const estimated = this.dataset.estimated;
        const actual = this.dataset.actual;
        const notes = this.dataset.notes;

        document.getElementById('edit_item_description').value = description;
        document.getElementById('edit_estimated_amount').value = estimated;
        document.getElementById('edit_actual_amount').value = actual || '';
        document.getElementById('edit_notes').value = notes || '';

        document.getElementById('editBudgetForm').action = `/events/{{ $event->id }}/budget-items/${id}`;
    });
});
</script>
@endsection
