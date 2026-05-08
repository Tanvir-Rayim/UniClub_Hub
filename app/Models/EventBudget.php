<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventBudget extends Model
{
    use HasFactory;

    protected $table = 'event_budgets';
    protected $fillable = [
        'event_id',
        'item_description',
        'estimated_amount',
        'actual_amount',
        'notes'
    ];

    protected $casts = [
        'estimated_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function expenseProofs()
    {
        return $this->hasMany(ExpenseProof::class, 'budget_item_id');
    }

    // Methods
    public function getTotalExpensesAttribute()
    {
        return $this->expenseProofs()->sum('amount');
    }

    public function getDifferenceAttribute()
    {
        return $this->actual_amount ? $this->actual_amount - $this->estimated_amount : null;
    }
}
