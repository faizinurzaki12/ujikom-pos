<?php

namespace Database\Factories;

use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Penjualan>
 */
class PenjualanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Penjualan::class;
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'total_pembayaran' => 0, // akan diupdate di seeder
            'metode_pembayaran' => $this->faker->randomElement([
                'CASH', 'TRANSFER', 'QRIS'
            ]),
            'status' => $this->faker->randomElement(['OPEN', 'COMPLETED']),
        ];
    }
}
