<?php

namespace App\Policies;

use App\Models\User;

class DashboardPolicy
{
    // Role Admin
    public function viewAny(User $user)
    {
        return $user->role?->name === 'admin';
    }
}

