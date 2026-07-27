<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ItemPenjualan extends Model
{

    use HasFactory;
    protected $table =  'item_penjualan';
    protected $fillable = [
        'penjualan_id',
        'produk_id',
        'kuantitas',
        'harga_satuan',
        'subtotal'
    ];

    // karena 1 penjualan memiliki banyak item penjualan dan juga produk lalu banyak item
    // maka direlasikan ke modelsnya dan di definisikan relasinya
    
    //relasi produk
    public function produk() {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    // ini relasi ke penjualan 
    public function penjualan() {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}
