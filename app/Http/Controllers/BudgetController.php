<?php
 
namespace App\Http\Controllers;
 
use App\Models\Event;
use App\Models\EventBudget;
use App\Models\ExpenseProof;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
 
class BudgetController extends Controller
{
    // View event budgets
    public function show(Event $event)
    {
        $this->authorize('view', $event);
 
        $budgetItems = $event->budgetItems()->get();
        $expenseProofs = $event->expenseProofs()->get();
 
        return view('budgets.show', [
            'event' => $event,
            'budgetItems' => $budgetItems,
            'expenseProofs' => $expenseProofs,
            'totalEstimated' => $budgetItems->sum('estimated_amount'),
            'totalExpenses' => $expenseProofs->sum('amount'),
        ]);
    }
 
    // Store new budget item
    public function storeBudgetItem(Request $request, Event $event)
    {
        $this->authorize('update', $event);
 
        $validated = $request->validate([
            'item_description' => 'required|string|max:255',
            'estimated_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string'
        ]);
 
        EventBudget::create([
            'event_id' => $event->id,
            'item_description' => $validated['item_description'],
            'estimated_amount' => $validated['estimated_amount'],
            'notes' => $validated['notes'] ?? null
        ]);
 
        return back()->with('success', 'Budget item added successfully!');
    }
 
    // Update budget item
    public function updateBudgetItem(Request $request, Event $event, EventBudget $budget)
    {
        $this->authorize('update', $event);
 
        if ($budget->event_id !== $event->id) {
            abort(404);
        }
 
        $validated = $request->validate([
            'item_description' => 'required|string|max:255',
            'estimated_amount' => 'required|numeric|min:0',
            'actual_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);
 
        $budget->update($validated);
 
        return back()->with('success', 'Budget item updated successfully!');
    }
 
    // Delete budget item
    public function deleteBudgetItem(Event $event, EventBudget $budget)
    {
        $this->authorize('update', $event);
 
        if ($budget->event_id !== $event->id) {
            abort(404);
        }
 
        $budget->delete();
 
        return back()->with('success', 'Budget item deleted successfully!');
    }
 
    // Store expense proof
    public function storeExpenseProof(Request $request, Event $event)
    {
        $this->authorize('update', $event);
 
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'budget_item_id' => 'nullable|exists:event_budgets,id',
            'file_type' => 'required|in:receipt,invoice,proof_of_purchase,other',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,gif|max:5120', // 5MB max
            'notes' => 'nullable|string'
        ]);
 
        // Store file
        $filePath = $request->file('file')->store('expense-proofs', 'public');
 
        ExpenseProof::create([
            'event_id' => $event->id,
            'budget_item_id' => $validated['budget_item_id'] ?? null,
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'file_path' => $filePath,
            'file_type' => $validated['file_type'],
            'notes' => $validated['notes'] ?? null
        ]);
 
        return back()->with('success', 'Expense proof uploaded successfully!');
    }
 
    // Delete expense proof
    public function deleteExpenseProof(Event $event, ExpenseProof $proof)
    {
        $this->authorize('update', $event);
 
        if ($proof->event_id !== $event->id) {
            abort(404);
        }
 
        // Delete file from storage
        if (Storage::disk('public')->exists($proof->file_path)) {
            Storage::disk('public')->delete($proof->file_path);
        }
 
        $proof->delete();
 
        return back()->with('success', 'Expense proof deleted successfully!');
    }
 
    // Download expense proof
    public function downloadExpenseProof(Event $event, ExpenseProof $proof)
    {
        $this->authorize('view', $event);
 
        if ($proof->event_id !== $event->id) {
            abort(404);
        }
 
        return Storage::disk('public')->download($proof->file_path);
    }
 
    // Advisor: Release financials for an event
    public function releaseFinancials(Event $event)
    {
        $user = Auth::user();
        
        // Only advisor of the club or admin can release financials
        if ($event->club->faculty_advisor_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Only the faculty advisor can release financials for this club.');
        }
 
        if ($event->status !== 'approved') {
            return back()->with('error', 'Financials can only be released for officially approved events.');
        }
 
        if ($event->financial_release_status) {
            return back()->with('info', 'Financials have already been released for this event.');
        }
 
        $event->update([
            'financial_release_status' => true,
            'financial_released_at' => now()
        ]);
 
        return back()->with('success', 'Financial release authorized! The club can now proceed with expenses.');
    }
 
    // Admin: View global financial summary
    public function adminSummary()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
 
        $clubs = \App\Models\Club::with(['events' => function($q) {
            $q->where('status', 'approved');
        }])->get();
 
        $clubData = $clubs->map(function($club) {
            return [
                'name' => $club->name,
                'total_approved_events' => $club->events->count(),
                'total_budget' => $club->events->sum('budget'),
                'released_budget' => $club->events->where('financial_release_status', true)->sum('budget'),
                'pending_release' => $club->events->where('financial_release_status', false)->count()
            ];
        });
 
        $recentEvents = Event::with(['club'])
            ->where('status', 'approved')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
 
        return view('admin.financial-summary', [
            'clubData' => $clubData,
            'recentEvents' => $recentEvents,
            'totalApprovedBudget' => Event::where('status', 'approved')->sum('budget'),
            'totalReleasedBudget' => Event::where('financial_release_status', true)->sum('budget'),
        ]);
    }
}
