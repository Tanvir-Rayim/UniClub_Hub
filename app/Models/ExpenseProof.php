<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseProof extends Model
{
    use HasFactory;

    protected $table = 'expense_proofs';
    protected $fillable = [
        'event_id',
        'budget_item_id',
        'description',
        'amount',
        'file_path',
        'file_type',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function budgetItem()
    {
        return $this->belongsTo(EventBudget::class, 'budget_item_id');
    }

    // Methods
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
