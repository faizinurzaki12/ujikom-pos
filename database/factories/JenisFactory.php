<?php

namespace Database\Factories;

use App\Models\Jenis;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Jenis>
 */
class JenisFactory extends Factory
{
    protected $model = Jenis::class;

    public function definition(): array
    {
        return [
            'user_id'    => User::factory(),
            'nama_jenis' => fake()->unique()->randomElement([
                'Makanan', 'Minuman', 'Elektronik', 'Pakaian',
                'Kosmetik', 'Peralatan Rumah', 'Alat Tulis', 'Mainan',
            ]),
        ];
    }
}