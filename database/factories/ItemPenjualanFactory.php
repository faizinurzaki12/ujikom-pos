<?php

namespace Database\Factories;

use App\Models\ItemPenjualan;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemPenjualan>
 */
class ItemPenjualanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = ItemPenjualan::class;

    public function definition(): array
    {
        $produk = Produk::inRandomOrder()->first();
        $qty = $this->faker->numberBetween(1, 10);

        return [
            'produk_id' => $produk->id,
            'kuantitas' => $qty,
            'harga_satuan' => $produk->harga_jual,
            'subtotal' => $produk->harga_jual * $qty,
        ];
    }
}
