<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penjualan_id')->constrained('penjualan','id')->onDelete('cascade');
            
            // Hapus onDelete('cascade') dan ubah menjadi nullable() + nullOnDelete()
            $table->foreignId('produk_id')->nullable()->constrained('produk','id')->nullOnDelete();
            
            $table->integer('kuantitas');
            $table->integer('harga_satuan');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_penjualan');
    }
};