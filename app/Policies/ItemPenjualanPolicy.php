<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ItemPenjualan;
class ItemPenjualanPolicy
{
    /**
     * Create a new policy instance.
     */
    public function delete(User $user, ItemPenjualan $itemPenjualan):bool {
        return $user->role?->name === 'admin';
    }
}
