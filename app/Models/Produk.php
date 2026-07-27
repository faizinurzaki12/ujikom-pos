<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Produk extends Model
{
    // ini untuk di daftarkan factory kita di models 
    use HasFactory;
    protected $table = 'produk';

    protected $fillable = [
        'user_id',
        'foto',
        'nama',
        'harga_beli',
        'harga_jual',
        'stok'
    ];

    //  1 produk memiliki banyak item penjualannya
    public function itemPenjualan() {
        return $this->hasMany(ItemPenjualan::class,'produk_id');
    }

    // 1 link user memiliki banyak produk 
    // user 
    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }
}
