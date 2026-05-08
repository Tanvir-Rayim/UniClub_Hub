<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    use HasFactory;

    protected $table = 'clubs';
    protected $fillable = [
        'name',
        'description',
        'faculty_advisor_id',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function members()
    {
        return $this->belongsToMany(User::class, 'club_members', 'club_id', 'user_id')
                    ->withPivot('status', 'joined_at', 'position')
                    ->withTimestamps()
                    ->using(ClubMember::class);
    }

    public function advisors()
    {
        return $this->belongsTo(User::class, 'faculty_advisor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function executives()
    {
        return $this->belongsToMany(User::class, 'club_executives', 'club_id', 'user_id')
                    ->withPivot('position')
                    ->withTimestamps();
    }

    public function advisorNotifications()
    {
        return $this->hasMany(AdvisorEventNotification::class);
    }

    // Methods
    public function getActiveMembersCount()
    {
        return $this->members()->wherePivot('status', 'approved')->count();
    }

    public function getPendingMembersCount()
    {
        return $this->members()->wherePivot('status', 'pending')->count();
    }
}
