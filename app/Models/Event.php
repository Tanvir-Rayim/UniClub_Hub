<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';
    protected $fillable = [
        'title',
        'description',
        'club_id',
        'venue_id',
        'proposed_date',
        'budget',
        'expected_audience',
        'status',
        'advisor_approval_status',
        'advisor_remarks',
        'rejection_reason',
        'created_by'
    ];

    protected $casts = [
        'proposed_date' => 'datetime',
        'budget' => 'float',
    ];

    // Relationships
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function venue()
    {
        return $this->belongsTo(Venue::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function budgetItems()
    {
        return $this->hasMany(EventBudget::class);
    }

    public function expenseProofs()
    {
        return $this->hasMany(ExpenseProof::class);
    }

    public function attendances()
    {
        return $this->hasMany(EventAttendance::class);
    }

    public function attendanceNotifications()
    {
        return $this->hasMany(AttendanceNotification::class);
    }

    public function advisorNotifications()
    {
        return $this->hasMany(AdvisorEventNotification::class);
    }

    public function registrations()
    {
        return $this->hasMany(\App\Models\EventRegistration::class);
    }

    // Methods
    public function getTotalEstimatedBudgetAttribute()
    {
        return $this->budgetItems()->sum('estimated_amount');
    }

    public function getTotalActualBudgetAttribute()
    {
        return $this->budgetItems()->sum('actual_amount');
    }

    public function getTotalExpensesAttribute()
    {
        return $this->expenseProofs()->sum('amount');
    }

    public function getAttendanceCountAttribute()
    {
        return $this->attendances()->where('marked_attended', true)->count();
    }

    public function getExpectedAttendanceAttribute()
    {
        return $this->attendances()->count();
    }

    public function feedbacks()
    {
        return $this->hasMany(EventFeedback::class);
    }
}
