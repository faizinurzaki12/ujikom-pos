<?php

namespace App\Services;
use App\Models\Produk;
class MonitoringStokService
{
    public function produkStokRendah(int $batas = 5, int $perPage = 5)
    {
        return Produk::where('stok', '>', 0)
            ->where('stok', '<=', $batas)
            ->orderBy('stok', 'asc')
            ->paginate($perPage, ['*'], 'stok_rendah_page');
    }

    public function produkStokHabis(int $perPage = 5)
    {
        return Produk::where('stok', 0)
            ->orderBy('nama')
            ->paginate($perPage, ['*'], 'stok_habis_page');
    }

}
