<?php

namespace App\Policies;

use App\Models\Jenis;
use App\Models\User;

class JenisPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role?->name, ['admin', 'kasir'], true);
    }

    public function view(User $user, Jenis $jenis): bool
    {
        return in_array($user->role?->name, ['admin', 'kasir'], true);
    }

    // INI YANG HILANG -- makanya /jenis/create kena 403
    public function create(User $user): bool
    {
        return $user->role?->name === 'admin';
    }

    public function update(User $user, Jenis $jenis): bool
    {
        return $user->role?->name === 'admin';
    }

    public function delete(User $user, Jenis $jenis): bool
    {
        return $user->role?->name === 'admin';
    }

    public function restore(User $user, Jenis $jenis): bool
    {
        return false;
    }

    public function forceDelete(User $user, Jenis $jenis): bool
    {
        return false;
    }
}