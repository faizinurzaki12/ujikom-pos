<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Menentukan siapa yang boleh melihat data administratif
     * (daftar users, ringkasan penjualan, rekap bulanan, dll).
     * Hanya admin yang diizinkan; kasir tidak.
     */
    public function viewAny(User $user): bool
    {
        return $user->role?->name === 'admin';
    }
}