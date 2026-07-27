<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{

    use HasFactory;
    
    protected $table = 'penjualan';

    protected $fillable = [
        'user_id',
        'total_pembayaran',
        'metode_pembayaran',
        'status'
    ];

    // 1 penjualan memiliki banyak item penjualan ->relasi ke item penjualan 
    public function itemPenjualan() {
        return $this->hasMany(ItemPenjualan::class,'penjualan_id');
    }

    // 1 link user memiliki banyak produk 
    // user 
    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
