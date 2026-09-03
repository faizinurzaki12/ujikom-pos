<?php

namespace App\Policies;

use App\Models\Jenis;
use App\Models\User;

class JenisPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array(strtolower($user->role?->name ?? ''), ['admin', 'kasir'], true);
    }

    public function view(User $user, Jenis $jenis): bool
    {
        return in_array(strtolower($user->role?->name ?? ''), ['admin', 'kasir'], true);
    }

    public function create(User $user): bool
    {
        return strtolower($user->role?->name ?? '') === 'admin';
    }

    public function update(User $user, Jenis $jenis): bool
    {
        return strtolower($user->role?->name ?? '') === 'admin';
    }

    public function delete(User $user, Jenis $jenis): bool
    {
        return strtolower($user->role?->name ?? '') === 'admin';
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