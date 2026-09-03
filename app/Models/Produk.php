<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes

class Produk extends Model
{
    use HasFactory, SoftDeletes; // Pasang SoftDeletes di sini

    protected $table = 'produk';

    protected $fillable = [
        'user_id',
        'jenis_id',
        'foto',
        'nama',
        'harga_beli',
        'harga_jual',
        'stok'
    ];

    // Relasi ke Jenis (Many to One)
    public function jenis() {
        return $this->belongsTo(Jenis::class, 'jenis_id');
    }
    
    // 1 produk memiliki banyak item penjualannya
    public function itemPenjualan() {
        return $this->hasMany(ItemPenjualan::class,'produk_id');
    }

    // 1 link user memiliki banyak produk 
    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}