<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvisorEventNotification extends Model
{
    use HasFactory;

    protected $table = 'advisor_event_notifications';

    protected $fillable = [
        'advisor_id',
        'event_id',
        'club_id',
        'status',
        'notification_type',
        'message',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // ============================================================================
    // RELATIONSHIPS
    // ============================================================================

    /**
     * Get the advisor who received this notification
     */
    public function advisor()
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    /**
     * Get the event related to this notification
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the club related to this notification
     */
    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    // ============================================================================
    // ACCESSORS & MUTATORS
    // ============================================================================

    /**
     * Get the status badge class for the notification
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'badge-warning',
            'read' => 'badge-info',
            'archived' => 'badge-secondary',
            default => 'badge-secondary'
        };
    }

    /**
     * Get the status badge text for the notification
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending Review',
            'read' => 'Reviewed',
            'archived' => 'Archived',
            default => 'Unknown'
        };
    }

    /**
     * Get the notification type label
     */
    public function getTypeTextAttribute()
    {
        return match($this->notification_type) {
            'new_proposal' => 'New Event Proposal',
            'budget_update' => 'Budget Update',
            'attendance_sync' => 'Attendance Sync',
            default => 'Notification'
        };
    }

    /**
     * Get the notification type icon
     */
    public function getTypeIconAttribute()
    {
        return match($this->notification_type) {
            'new_proposal' => 'fas fa-file-alt',
            'budget_update' => 'fas fa-calculator',
            'attendance_sync' => 'fas fa-check-circle',
            default => 'fas fa-bell'
        };
    }

    // ============================================================================
    // SCOPES
    // ============================================================================

    /**
     * Get only pending notifications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get only read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    /**
     * Get only archived notifications
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Get only new proposal notifications
     */
    public function scopeNewProposals($query)
    {
        return $query->where('notification_type', 'new_proposal');
    }

    /**
     * Get notifications for a specific advisor
     */
    public function scopeForAdvisor($query, $advisorId)
    {
        return $query->where('advisor_id', $advisorId);
    }

    /**
     * Get notifications for a specific club
     */
    public function scopeForClub($query, $clubId)
    {
        return $query->where('club_id', $clubId);
    }

    /**
     * Get notifications for a specific event
     */
    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    /**
     * Order by most recent first
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // ============================================================================
    // METHODS
    // ============================================================================

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
        return $this;
    }

    /**
     * Mark notification as pending
     */
    public function markAsPending()
    {
        $this->update([
            'status' => 'pending',
            'read_at' => null
        ]);
        return $this;
    }

    /**
     * Archive the notification
     */
    public function archive()
    {
        $this->update([
            'status' => 'archived'
        ]);
        return $this;
    }

    /**
     * Check if notification has been read
     */
    public function isRead()
    {
        return $this->status === 'read' || $this->read_at !== null;
    }

    /**
     * Check if notification is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }
}
