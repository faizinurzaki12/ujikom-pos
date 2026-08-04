@extends('layouts.app')

@section('title', 'Rekap Bulanan')

@section('content')
<div class="container-fluid px-0">
    <!-- Header & Filter Bulan/Tahun -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <h5 class="fw-bold mb-0">
            Rekap Bulanan
            <small class="text-muted fs-5 d-block d-md-inline">({{ $namaBulanTahun }})</small>
        </h5>

        <form method="GET" action="{{ route('laporan.bulanan') }}" class="d-flex gap-2">
            <select name="bulan" class="form-select">
                @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                    </option>
                @endforeach
            </select>

            <select name="tahun" class="form-select">
                @foreach($daftarTahun as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary text-nowrap">
                <i class="bi bi-search me-1"></i> Tampilkan
            </button>
        </form>
    </div>

    <!-- Ringkasan Total Bulanan -->
    <div class="row mb-3">
        <div class="col-md-12">
            <h3 class="fw-bold text-primary mb-3 fs-4">Ringkasan Bulan Ini</h3>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <div class="card-sales sales shadow-sm">
                <div class="text-sales">Total Penjualan</div>
                <div class="text-sale">Rp {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <div class="card-sales sales shadow-sm">
                <div class="text-sales">Jumlah Transaksi</div>
                <div class="text-sale">{{ $ringkasan['total_transaksi'] }} Transaksi</div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <div class="card-sales card-payment-tunai shadow-sm">
                <div class="text-sales">Total Pembayaran Tunai</div>
                <div class="text-sale">Rp {{ number_format($ringkasan['total_cash'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
            <div class="card-sales card-payment-nontunai shadow-sm">
                <div class="text-sales">Total Pembayaran Non-Tunai</div>
                <div class="text-sale">Rp {{ number_format($ringkasan['total_non_tunai'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Tabel Rekap Harian dalam Bulan -->
    <div class="row mb-3">
        <div class="col-md-12 mb-2">
            <h3 class="fs-4 fw-bold text-dark">Rekap Harian</h3>
        </div>
        <div class="col-md-12">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Tanggal</th>
                                <th scope="col" class="text-center">Jumlah Transaksi</th>
                                <th scope="col" class="text-center">Kuantitas Terjual</th>
                                <th scope="col" class="text-end">Total Penjualan</th>
                                <th scope="col" class="text-end">Tunai</th>
                                <th scope="col" class="text-end">Non-Tunai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rekapHarian as $hari)
                                <tr>
                                    <td class="fw-medium">
                                        {{ \Carbon\Carbon::parse($hari->tanggal)->translatedFormat('d F Y (l)') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary px-2 py-1">{{ $hari->total_transaksi }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning-subtle text-warning fw-bold px-2 py-1">{{ $hari->total_kuantitas ?? 0 }} Pcs</span>
                                    </td>
                                    <td class="text-end">Rp {{ number_format($hari->total_penjualan, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($hari->total_cash, 0, ',', '.') }}</td>
                                    <td class="text-end">Rp {{ number_format($hari->total_non_tunai, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-muted text-center py-3">
                                        Belum ada transaksi pada bulan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($rekapHarian->isNotEmpty())
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td>Total</td>
                                <td class="text-center">{{ $ringkasan['total_transaksi'] }}</td>
                                <td class="text-center">{{ $rekapHarian->sum('total_kuantitas') }} Pcs</td>
                                <td class="text-end">Rp {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($ringkasan['total_cash'], 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($ringkasan['total_non_tunai'], 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Produk Terlaris Bulanan -->
    <div class="row">
        <div class="col-md-12 mb-2">
            <h3 class="fs-4 fw-bold text-dark">Produk Terlaris Bulan Ini</h3>
        </div>
        <div class="col-md-12">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="table-dark">
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Stok Tersedia</th>
                                <th scope="col" class="text-center">Unit Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produkTerlaris as $produk)
                                <tr>
                                    <td class="fw-medium">{{ $produk->nama }}</td>
                                    <td>{{ $produk->stok }} Pcs</td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success fw-bold px-3 py-2">{{ $produk->total_terjual }} Terjual</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-muted text-center py-3">
                                        Belum ada data penjualan pada bulan ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection