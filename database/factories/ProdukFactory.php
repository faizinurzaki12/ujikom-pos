<?php

namespace Database\Factories;

use App\Models\Produk;
use App\Models\Jenis;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends Factory<Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hargaBeli = $this->faker->numberBetween(10_000, 500_000);
        return [
            'user_id' => User::where('role_id' , 1)->inRandomOrder()->value('id'),
            'jenis_id' => Jenis::inRandomOrder()->first()?->id ?? Jenis::factory(),
            'foto' => 'produk/' . $this->faker->uuid . '.jpg',
            'nama' => $this->faker->words(3, true),
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaBeli + $this->faker->numberBetween(500_000 ,10_000),
            'stok' => $this->faker->numberBetween(1,500),
        ];
    }
}
