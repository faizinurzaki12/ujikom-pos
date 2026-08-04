<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class LaporanPenjualanService
{
    public function ringkasanHariIni(): array
    {
        $data = DB::table('penjualan')
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'COMPLETED')
            ->selectRaw('
                COUNT(*) as total_transaksi,
                SUM(total_pembayaran) as total_penjualan,
                SUM(CASE WHEN metode_pembayaran = "CASH" THEN total_pembayaran ELSE 0 END) as total_cash,
                SUM(CASE WHEN metode_pembayaran != "CASH" THEN total_pembayaran ELSE 0 END) as total_non_tunai
            ')
            ->first();

        return [
            'total_transaksi' => $data->total_transaksi ?? 0,
            'total_penjualan' => $data->total_penjualan ?? 0,
            'total_cash' => $data->total_cash ?? 0,
            'total_non_tunai' => $data->total_non_tunai ?? 0,
        ];
    }

    public function produkTerlarisHariIni(int $limit = 5)
    {
        return DB::table('item_penjualan')
            ->join('penjualan', 'penjualan.id', '=', 'item_penjualan.penjualan_id')
            ->join('produk', 'produk.id', '=', 'item_penjualan.produk_id')
            ->whereDate('penjualan.created_at', Carbon::today())
            ->where('penjualan.status', 'COMPLETED')
            ->groupBy('produk.id', 'produk.nama', 'produk.stok')
            ->select(
                'produk.nama',
                'produk.stok',
                DB::raw('SUM(item_penjualan.kuantitas) as total_terjual')
            )
            ->orderByDesc('total_terjual')
            ->limit($limit)
            ->get();
    }

        public function rekapBulanan(int $bulan, int $tahun): array
        {
            $data = DB::table('penjualan')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->where('status', 'COMPLETED')
                ->selectRaw('
                    COUNT(*) as total_transaksi,
                    SUM(total_pembayaran) as total_penjualan,
                    SUM(CASE WHEN metode_pembayaran = "CASH" THEN total_pembayaran ELSE 0 END) as total_cash,
                    SUM(CASE WHEN metode_pembayaran != "CASH" THEN total_pembayaran ELSE 0 END) as total_non_tunai
                ')
                ->first();

            return [
                'total_transaksi' => $data->total_transaksi ?? 0,
                'total_penjualan' => $data->total_penjualan ?? 0,
                'total_cash' => $data->total_cash ?? 0,
                'total_non_tunai' => $data->total_non_tunai ?? 0,
            ];
        }

                public function rekapHarianDalamBulan(int $bulan, int $tahun)
        {
            // Subquery: total kuantitas item per transaksi
            $itemPerPenjualan = DB::table('item_penjualan')
                ->select('penjualan_id', DB::raw('SUM(kuantitas) as total_item'))
                ->groupBy('penjualan_id');

            return DB::table('penjualan')
                ->leftJoinSub($itemPerPenjualan, 'items', function ($join) {
                    $join->on('items.penjualan_id', '=', 'penjualan.id');
                })
                ->whereMonth('penjualan.created_at', $bulan)
                ->whereYear('penjualan.created_at', $tahun)
                ->where('penjualan.status', 'COMPLETED')
                ->selectRaw('
                    DATE(penjualan.created_at) as tanggal,
                    COUNT(*) as total_transaksi,
                    SUM(penjualan.total_pembayaran) as total_penjualan,
                    SUM(CASE WHEN penjualan.metode_pembayaran = "CASH" THEN penjualan.total_pembayaran ELSE 0 END) as total_cash,
                    SUM(CASE WHEN penjualan.metode_pembayaran != "CASH" THEN penjualan.total_pembayaran ELSE 0 END) as total_non_tunai,
                    SUM(items.total_item) as total_kuantitas
                ')
                ->groupBy(DB::raw('DATE(penjualan.created_at)'))
                ->orderBy('tanggal', 'asc')
                ->get();
        }

        public function produkTerlarisBulanan(int $bulan, int $tahun, int $limit = 10)
        {
            return DB::table('item_penjualan')
                ->join('penjualan', 'penjualan.id', '=', 'item_penjualan.penjualan_id')
                ->join('produk', 'produk.id', '=', 'item_penjualan.produk_id')
                ->whereMonth('penjualan.created_at', $bulan)
                ->whereYear('penjualan.created_at', $tahun)
                ->where('penjualan.status', 'COMPLETED')
                ->groupBy('produk.id', 'produk.nama', 'produk.stok')
                ->select(
                    'produk.nama',
                    'produk.stok',
                    DB::raw('SUM(item_penjualan.kuantitas) as total_terjual')
                )
                ->orderByDesc('total_terjual')
                ->limit($limit)
                ->get();
        }

        public function daftarTahunTransaksi()
        {
            return DB::table('penjualan')
                ->selectRaw('DISTINCT YEAR(created_at) as tahun')
                ->orderByDesc('tahun')
                ->pluck('tahun');
        }
}