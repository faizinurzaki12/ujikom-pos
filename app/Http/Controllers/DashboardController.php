<?php

namespace App\Http\Controllers;
use App\Services\LaporanPenjualanService;
use App\Services\MonitoringStokService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    // disini membuat variabel untuk meggunakan service yang sudah ada
    public function __construct(
        protected LaporanPenjualanService $laporanService,
        protected MonitoringStokService $stokService
    ) {}

    public function index()
    {
        // nama function yang ada di laporan penjualan servis
        $ringkasan = $this->laporanService->ringkasanHariIni();

        // panggil nama function yang sudah ada di monitoring dan mengirimkan ke data halaman dashboard
        return view('dashboard', [
            'tanggalHariIni' => Carbon::now(),
            'ringkasan' => $ringkasan,
            'produkTerlaris' => $this->laporanService->produkTerlarisHariIni(),
            'produkStokRendah' => $this->stokService->produkStokRendah(),
            'produkStokHabis' => $this->stokService->produkStokHabis(),
        ]);
    }

}
