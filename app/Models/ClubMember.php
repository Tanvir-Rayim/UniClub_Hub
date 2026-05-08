<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClubMember extends Pivot
{
    protected $table = 'club_members';
    
    protected $casts = [
        'joined_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
