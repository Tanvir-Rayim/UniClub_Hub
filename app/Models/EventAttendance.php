<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventAttendance extends Model
{
    use HasFactory;

    protected $table = 'event_attendances';
    protected $fillable = [
        'event_id',
        'user_id',
        'marked_attended',
        'marked_at',
        'notes'
    ];

    protected $casts = [
        'marked_attended' => 'boolean',
        'marked_at' => 'datetime',
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
