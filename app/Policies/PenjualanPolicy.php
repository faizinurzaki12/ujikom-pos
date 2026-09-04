<?php

namespace App\Policies;

use App\Models\Penjualan;
use App\Models\User;

class PenjualanPolicy
{
    /**
     * Menentukan apakah user bisa melihat detail transaksi.
     */
    public function view(User $user, Penjualan $penjualan): bool 
    {
        // Admin bisa melihat semua, Kasir hanya bisa melihat miliknya sendiri
        if ($user->role?->name === 'admin') {
            return true;
        }

        return $user->role?->name === 'kasir' && $penjualan->user_id === $user->id;
    }

    /**
     * Menentukan apakah user bisa mengedit transaksi.
     * Tombol edit hanya muncul jika status masih OPEN.
     */
    public function update(User $user, Penjualan $penjualan): bool 
    {
        // Hanya bisa diedit jika status masih OPEN
        if ($penjualan->status !== 'OPEN') {
            return false;
        }

        // Admin atau Kasir pemilik transaksi yang berhak mengedit
        if ($user->role?->name === 'admin') {
            return true;
        }

        return $user->role?->name === 'kasir' && $penjualan->user_id === $user->id;
    }

    /**
     * Menentukan apakah user bisa menghapus/membatalkan transaksi.
     */
    public function delete(User $user, Penjualan $penjualan): bool 
    {
        return $user->role?->name === 'admin' && $penjualan->status === 'OPEN';
    }
}