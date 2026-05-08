<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $fillable = [
        'name',
        'university_id',
        'email',
        'password',
        'phone',
        'role',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function clubs()
    {
        return $this->belongsToMany(Club::class, 'club_members', 'user_id', 'club_id');
    }

    public function advisedClubs()
    {
        return $this->hasMany(Club::class, 'faculty_advisor_id');
    }

    public function assignedClubs()
    {
        return $this->belongsToMany(Club::class, 'club_executives', 'user_id', 'club_id')
                    ->withPivot('position')
                    ->withTimestamps();
    }

    public function eventNotifications()
    {
        return $this->hasMany(AdvisorEventNotification::class, 'advisor_id');
    }

    // Mutators
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Methods
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isExecutive()
    {
        return $this->role === 'executive';
    }

    public function isAdvisor()
    {
        return $this->role === 'advisor';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
