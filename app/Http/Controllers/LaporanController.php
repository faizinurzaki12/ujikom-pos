<?php

namespace App\Http\Controllers;

use App\Services\LaporanPenjualanService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class LaporanController extends Controller
{
    public function __construct(
        protected LaporanPenjualanService $laporanService
    ) {}

    public function bulanan(Request $request)
    {
        $this->authorize('viewAny', \App\Models\User::class);
        $bulan = (int) $request->query('bulan', now()->month);
        $tahun = (int) $request->query('tahun', now()->year);

        $daftarTahun = $this->laporanService->daftarTahunTransaksi();
        if ($daftarTahun->isEmpty()) {
            $daftarTahun = collect([now()->year]);
        }

        return view('laporan.bulanan', [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'namaBulanTahun' => Carbon::create($tahun, $bulan, 1)->translatedFormat('F Y'),
            'ringkasan' => $this->laporanService->rekapBulanan($bulan, $tahun),
            'rekapHarian' => $this->laporanService->rekapHarianDalamBulan($bulan, $tahun),
            'produkTerlaris' => $this->laporanService->produkTerlarisBulanan($bulan, $tahun),
            'daftarTahun' => $daftarTahun,
        ]);
    }
}