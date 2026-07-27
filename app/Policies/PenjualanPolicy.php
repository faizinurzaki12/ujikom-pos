<?php

namespace App\Policies;

use App\Models\Penjualan;
use App\Models\User;

class PenjualanPolicy
{
    /**
     * Create a new policy instance.
     */
    public function delete(User $user, Penjualan $penjualan):bool 
    {
        return $user->role?->name === 'admin'
        && $penjualan->status === 'OPEN';
    }

    public function view(User $user, Penjualan $penjualan):bool 
    {
        return $user->role?->name === 'admin'
        && $penjualan->status === 'OPEN';    
    }
}
