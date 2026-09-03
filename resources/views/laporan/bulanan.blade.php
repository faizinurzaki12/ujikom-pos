@extends('layouts.app')

@section('title', 'Rekap Bulanan')

@section('content')
<div class="dashboard-flex">
    <!-- Header & Filter Bulan/Tahun -->
    <div class="row-stat">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-2">
            <h5 class="fw-bold mb-0 text-truncate">
                Rekap Bulanan
                <small class="text-muted fs-6 d-block d-sm-inline">({{ $namaBulanTahun }})</small>
            </h5>

            <form method="GET" action="{{ route('laporan.bulanan') }}" class="d-flex gap-2 flex-wrap flex-sm-nowrap">
                <select name="bulan" class="form-select form-select-sm">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>

                <select name="tahun" class="form-select form-select-sm">
                    @foreach($daftarTahun as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary btn-sm text-nowrap w-100 w-sm-auto">
                    <i class="bi bi-search me-1"></i> Tampilkan
                </button>
            </form>
        </div>

        <!-- Ringkasan Total Bulanan -->
        <div class="row g-2 mb-2">
            <div class="col-12">
                <h6 class="fw-bold text-primary mb-1 fs-6">Ringkasan Bulan Ini</h6>
            </div>
            <div class="col-6 col-md-3">
                <div class="card-sales sales compact shadow-sm">
                    <div class="text-sales">Total Penjualan</div>
                    <div class="text-sale">Rp {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card-sales sales compact shadow-sm">
                    <div class="text-sales">Jumlah Transaksi</div>
                    <div class="text-sale">{{ $ringkasan['total_transaksi'] }} Transaksi</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card-sales card-payment-tunai compact shadow-sm">
                    <div class="text-sales">Total Tunai</div>
                    <div class="text-sale">Rp {{ number_format($ringkasan['total_cash'], 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card-sales card-payment-nontunai compact shadow-sm">
                    <div class="text-sales">Total Non-Tunai</div>
                    <div class="text-sale">Rp {{ number_format($ringkasan['total_non_tunai'], 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Area Tabel Responsive Grid -->
    <div class="row row-table-flex g-3">
        <!-- Tabel Rekap Harian dalam Bulan -->
        <div class="col-xl-7 col-12 d-flex flex-column">
            <div class="card card-table-flex border-0 shadow-sm p-3 bg-white rounded-3">
                <h6 class="fw-bold text-dark mb-2 fs-6">Rekap Harian</h6>
                
                <!-- Container Utama Tabel Harian -->
                <div class="rekap-container">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0 text-nowrap">
                            <thead class="table-dark sticky-top">
                                <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col" class="text-center">Jml Transaksi</th>
                                    <th scope="col" class="text-center">Kuantitas</th>
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
                        </table>
                    </div>

                    <!-- Row Total Dikunci di Dasar Kartu -->
                    @if($rekapHarian->isNotEmpty())
                    <div class="total-bottom-bar border-top pt-2 mt-auto">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0 text-nowrap">
                                <tfoot>
                                    <tr class="fw-bold">
                                        <td>Total</td>
                                        <td class="text-center">{{ $ringkasan['total_transaksi'] }}</td>
                                        <td class="text-center">{{ $rekapHarian->sum('total_kuantitas') }} Pcs</td>
                                        <td class="text-end">Rp {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($ringkasan['total_cash'], 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($ringkasan['total_non_tunai'], 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabel Produk Terlaris Bulanan -->
        <div class="col-xl-5 col-12 d-flex flex-column">
            <div class="card card-table-flex border-0 shadow-sm p-3 bg-white rounded-3 h-100">
                <h6 class="fw-bold text-dark mb-2 fs-6">Produk Terlaris Bulan Ini</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-light sticky-top">
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
                                        <span class="badge bg-success-subtle text-success fw-bold px-3 py-1">{{ $produk->total_terjual }} Terjual</span>
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