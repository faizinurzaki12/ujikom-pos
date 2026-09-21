@extends('layouts.app')

@section('title', 'Dashboard Toko HP danzz')

@section('content')
<div class="container-fluid px-0 dashboard-flex">
    <div class="row-stat text-start">
        <h5 class="fw-bold mb-2">
            Ringkasan Hari Ini
            <small class="text-muted fs-6 d-block d-md-inline">({{ $tanggalHariIni->translatedFormat('l, d F Y') }})</small>
        </h5>
    </div>

    @can('viewAny', App\Models\User::class)
    <div class="row row-stat g-2">
        <div class="col-6 col-md-3">
            <div class="card-sales compact sales shadow-sm">
                <div class="text-sales">Total Penjualan</div>
                <div class="text-sale">Rp {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-sales compact sales shadow-sm">
                <div class="text-sales">Jumlah Transaksi</div>
                <div class="text-sale">{{ $ringkasan['total_transaksi'] }} Transaksi</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-sales compact card-payment-tunai shadow-sm">
                <div class="text-sales">Pembayaran Tunai</div>
                <div class="text-sale">Rp {{ number_format($ringkasan['total_cash'], 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-sales compact card-payment-nontunai shadow-sm">
                <div class="text-sales">Non-Tunai</div>
                <div class="text-sale">Rp {{ number_format($ringkasan['total_non_tunai'], 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    @endcan

    <div class="row-stat mt-1">
        <h6 class="fw-bold text-dark mb-1 fs-6">Critical Inventory Status</h6>
    </div>

    <div class="row g-2">
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm p-2 bg-white rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-1 px-1">
                    <span class="text-secondary fw-semibold fs-7">Stok Rendah</span>
                    <span class="badge bg-primary text-dark">Perlu Perhatian</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Produk</th>
                                <th class="text-center">Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produkStokRendah as $index => $produk)
                                <tr>
                                    <td>{{ $produkStokRendah->firstItem() + $index }}</td>
                                    <td class="fw-medium">{{ $produk->nama }}</td>
                                    <td class="text-center"><span class="badge bg-danger-subtle text-danger px-2 py-1">{{ $produk->stok }} Pcs</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-muted text-center py-2">Seluruh produk aman.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm p-2 bg-white rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-1 px-1">
                    <span class="text-secondary fw-semibold fs-7">Stok Habis</span>
                    <span class="badge bg-primary text-dark">Darurat</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Produk</th>
                                <th class="text-center">Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produkStokHabis as $index => $produk)
                                <tr>
                                    <td>{{ $produkStokHabis->firstItem() + $index }}</td>
                                    <td class="fw-medium">{{ $produk->nama }}</td>
                                    <td class="text-center"><span class="badge bg-danger px-2 py-1">Habis ({{ $produk->stok }})</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-muted text-center py-2">Seluruh produk aman.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Bagian Best Seller (Produk Terlaris Bulan Ini) --}}
    <div class="row-stat mt-3">
        <h6 class="fw-bold text-dark mb-1 fs-6">Best Seller</h6>
    </div>

    <div class="row g-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-2 bg-white rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-1 px-1">
                    <span class="text-secondary fw-semibold fs-7">Produk Terlaris Bulan Ini</span>
                    <span class="badge bg-primary text-white">Top Products</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0 text-nowrap">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Produk</th>
                                <th class="text-center">Stok Tersedia</th>
                                <th class="text-center">Unit Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produkTerlarisBulanan as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-medium">{{ $item->nama }}</td>
                                    <td class="text-center">{{ $item->stok }} Pcs</td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success px-2 py-1">
                                            {{ $item->total_terjual }} Terjual
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-muted text-center py-2">Belum ada data penjualan bulan ini.</td>
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