<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceNotification extends Model
{
    use HasFactory;

    protected $table = 'attendance_notifications';
    protected $fillable = [
        'event_id',
        'sent_by_id',
        'sent_at',
        'message'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function sentBy()
    {
        return $this->belongsTo(User::class, 'sent_by_id');
    }
}
