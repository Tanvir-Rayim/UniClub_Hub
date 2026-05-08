<?php

namespace App\Policies;

use App\Models\Club;
use App\Models\User;

class ClubPolicy
{
    public function assignExecutives(User $user, Club $club): bool
    {
        return $user->isAdmin() || $user->isAdvisor() && $club->faculty_advisor_id === $user->id;
    }
    public function view(User $user, Club $club): bool
    {
        return true;
    }
}
