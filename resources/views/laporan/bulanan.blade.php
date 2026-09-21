@extends('layouts.app')

@section('title', 'Rekap Bulanan & Mingguan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/laporan/index.css') }}">
@endpush

@section('content')
<div class="dashboard-flex" id="laporanPrintArea">
    <div class="row-stat mb-2 no-print">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2">
            <h5 class="fw-bold mb-0 text-truncate">
                Rekap Bulanan &amp; Mingguan
                <small class="text-muted fs-6 d-block d-sm-inline">({{ $namaBulanTahun }})</small>
            </h5>

            <div class="d-flex gap-2 flex-wrap flex-sm-nowrap align-items-center">
                <form action="{{ route('laporan.bulanan') }}" method="GET" class="d-flex gap-2 flex-wrap flex-sm-nowrap">
                    <select name="bulan" class="form-select form-select-sm" style="min-width: 130px;">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Illuminate\Support\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>

                    <select name="tahun" class="form-select form-select-sm" style="min-width: 90px;">
                        @foreach($daftarTahun as $thn)
                            <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>
                                {{ $thn }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary btn-sm text-nowrap">
                        <i class="bi bi-search me-1"></i> Tampilkan
                    </button>
                </form>

                <button type="button" class="btn btn-outline-dark btn-sm text-nowrap" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i> Cetak Laporan
                </button>
            </div>
        </div>
    </div>

    {{-- Judul yang hanya tampil saat print/export --}}
    <div class="print-only mb-3">
        <h4 class="fw-bold mb-0">Rekap Bulanan &amp; Mingguan</h4>
        <div class="text-muted">Periode: {{ $namaBulanTahun }}</div>
    </div>

    <!-- Ringkasan Kategori -->
    <div class="row-stat mb-2">
        <div class="row g-2">
            <div class="col-6 col-md-3">
                <div class="card-sales sales compact shadow-sm bg-white rounded-3 border">
                    <div class="text-sales">Total Penjualan</div>
                    <div class="text-sale">Rp {{ number_format($ringkasan['total_penjualan'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card-sales sales compact shadow-sm bg-white rounded-3 border">
                    <div class="text-sales">Jumlah Transaksi</div>
                    <div class="text-sale">{{ $ringkasan['total_transaksi'] ?? 0 }} Transaksi</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card-sales card-payment-tunai compact shadow-sm bg-white rounded-3 border">
                    <div class="text-sales">Total Tunai</div>
                    <div class="text-sale">Rp {{ number_format($ringkasan['total_cash'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card-sales card-payment-nontunai compact shadow-sm bg-white rounded-3 border">
                    <div class="text-sales">Total Non-Tunai</div>
                    <div class="text-sale">Rp {{ number_format($ringkasan['total_non_tunai'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rekap Harian & Rekap Mingguan -->
    <div class="row g-2 row-table-flex mb-2" style="flex: 3;">

        <!-- Rekap Harian -->
        <div class="col-lg-6 col-12">
            <div class="card card-table-flex border-0 shadow-sm p-2 bg-white rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="fw-bold text-dark mb-0 fs-7">Rekap Harian</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 text-nowrap fs-7">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th>Tanggal</th>
                                <th class="text-center">Jml Transaksi</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-end">Total Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rekapHarian as $harian)
                                <tr>
                                    <td data-label="Tanggal" class="fw-medium">
                                        {{ \Illuminate\Support\Carbon::parse($harian->tanggal)->translatedFormat('d F Y (l)') }}
                                    </td>
                                    <td data-label="Jml Transaksi" class="text-center">
                                        <span class="badge bg-primary-subtle text-primary px-2 py-1">
                                            {{ $harian->total_transaksi }}
                                        </span>
                                    </td>
                                    <td data-label="Kuantitas" class="text-center">
                                        <span class="badge bg-warning-subtle text-warning fw-bold px-2 py-1">
                                            {{ $harian->total_kuantitas ?? 0 }} Pcs
                                        </span>
                                    </td>
                                    <td data-label="Total Penjualan" class="text-end fw-semibold">
                                        Rp {{ number_format($harian->total_penjualan, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Tidak ada data harian.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($rekapHarian->isNotEmpty())
                            <tfoot>
                                <tr class="fw-bold border-top table-total-row">
                                    <td>Total Harian</td>
                                    <td data-label="Jml Transaksi" class="text-center">{{ $rekapHarian->sum('total_transaksi') }}</td>
                                    <td data-label="Kuantitas" class="text-center">{{ $rekapHarian->sum('total_kuantitas') }} Pcs</td>
                                    <td data-label="Total Penjualan" class="text-end">Rp {{ number_format($rekapHarian->sum('total_penjualan'), 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- Rekap Mingguan -->
        <div class="col-lg-6 col-12">
            <div class="card card-table-flex border-0 shadow-sm p-2 bg-white rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="fw-bold text-dark mb-0 fs-7">Rekap Mingguan</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 text-nowrap fs-7">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th>Minggu Ke-</th>
                                <th>Rentang Tanggal</th>
                                <th class="text-center">Jml Transaksi</th>
                                <th class="text-center">Kuantitas</th>
                                <th class="text-end">Total Penjualan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($rekapMingguan) && count($rekapMingguan) > 0)
                                @foreach($rekapMingguan as $minggu)
                                    <tr>
                                        <td data-label="Minggu Ke-" class="fw-medium">Minggu {{ $minggu['minggu_ke'] }}</td>
                                        <td data-label="Rentang Tanggal">{{ $minggu['rentang_tanggal'] }}</td>
                                        <td data-label="Jml Transaksi" class="text-center">
                                            <span class="badge {{ $minggu['jumlah_transaksi'] > 0 ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary' }} px-2 py-1">
                                                {{ $minggu['jumlah_transaksi'] }}
                                            </span>
                                        </td>
                                        <td data-label="Kuantitas" class="text-center">
                                            @if($minggu['total_kuantitas'] > 0)
                                                <span class="badge bg-warning-subtle text-warning fw-bold px-2 py-1">
                                                    {{ $minggu['total_kuantitas'] }} Pcs
                                                </span>
                                            @else
                                                0 Pcs
                                            @endif
                                        </td>
                                        <td data-label="Total Penjualan" class="text-end {{ $minggu['total_penjualan'] > 0 ? 'fw-semibold' : '' }}">
                                            Rp {{ number_format($minggu['total_penjualan'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Tidak ada data mingguan.</td>
                                </tr>
                            @endif
                        </tbody>
                        @if(isset($rekapMingguan) && count($rekapMingguan) > 0)
                            <tfoot>
                                <tr class="fw-bold border-top table-total-row">
                                    <td colspan="2">Total Mingguan</td>
                                    <td data-label="Jml Transaksi" class="text-center">{{ collect($rekapMingguan)->sum('jumlah_transaksi') }}</td>
                                    <td data-label="Kuantitas" class="text-center">{{ collect($rekapMingguan)->sum('total_kuantitas') }} Pcs</td>
                                    <td data-label="Total Penjualan" class="text-end">Rp {{ number_format(collect($rekapMingguan)->sum('total_penjualan'), 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Tabel Best Seller -->
    <div class="row g-2 row-table-flex" style="flex: 2;">
        <div class="col-12">
            <div class="card card-table-flex border-0 shadow-sm p-2 bg-white rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h6 class="fw-bold text-dark mb-0 fs-7">Best Seller (Produk Terlaris Bulan Ini)</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 text-nowrap fs-7">
                        <thead class="table-dark sticky-top">
                            <tr>
                                <th>Nama Produk</th>
                                <th class="text-center">Stok Tersedia</th>
                                <th class="text-center">Unit Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($produkTerlaris as $produk)
                                <tr>
                                    <td data-label="Nama Produk" class="fw-medium">{{ $produk->nama }}</td>
                                    <td data-label="Stok Tersedia" class="text-center">{{ $produk->stok ?? 0 }} Pcs</td>
                                    <td data-label="Unit Terjual" class="text-center">
                                        <span class="badge bg-success-subtle text-success fw-bold px-3 py-1">
                                            {{ $produk->total_terjual }} Terjual
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-2">Belum ada penjualan produk bulan ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- style untuk rekapan -->
<style>
    .print-only { display: none; }

    @media print {
        body * { visibility: hidden; }
        #laporanPrintArea, #laporanPrintArea * { visibility: visible; }
        #laporanPrintArea {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
        }

        .no-print { display: none !important; }
        .print-only { display: block !important; }

        /* Tabel yang tadinya scroll horizontal, saat print dipaksa muat semua kolom */
        .table-responsive { overflow: visible !important; }
        .table { font-size: 10.5px !important; }

        .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }

        @page { size: A4 landscape; margin: 12mm; }
    }
</style>
@endsection