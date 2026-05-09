<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $table = 'event_registrations';

    protected $fillable = [
        'event_id',
        'user_id',
        'ticket_code',
        'status',
        'registered_at',
        'cancelled_at',
        'cancellation_deadline',
    ];

    protected $casts = [
        'registered_at'         => 'datetime',
        'cancelled_at'          => 'datetime',
        'cancellation_deadline' => 'datetime',
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

    // Helpers
    public function isActive(): bool
    {
        return $this->status === 'registered';
    }

    public function isCancellable(): bool
    {
        return $this->isActive() && now()->lt($this->cancellation_deadline);
    }

    // Generate a unique ticket code
    public static function generateTicketCode(): string
    {
        do {
            $code = 'TKT-' . strtoupper(substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6));
        } while (self::where('ticket_code', $code)->exists());

        return $code;
    }
}
