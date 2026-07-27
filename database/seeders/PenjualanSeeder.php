<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Penjualan;
use App\Models\ItemPenjualan;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {

            Penjualan::factory()
                ->count(50)
                ->create()
                ->each(function ($penjualan) {

                    $items = ItemPenjualan::factory()
                        ->count(rand(1, 5))
                        ->make([
                            'penjualan_id' => $penjualan->id,
                        ]);

                    $total = $items->sum('subtotal');

                    $penjualan->itemPenjualan()->saveMany($items);

                    $penjualan->update([
                        'total_pembayaran' => $total,
                    ]);
                });
        });

    }
}
