@extends('layouts.app')

@section('title', 'Dashboard POS')

@section('content')
<div class="container-fluid px-0">
    <!-- Header Tanggal -->
    <div class="mb-4 px-2 px-md-0 text-start">
        <h5 class="fw-bold mb-3">
            Ringkasan Hari Ini
            <small class="text-muted fs-5 d-block d-md-inline">
                ({{ $tanggalHariIni->translatedFormat('l, d F Y') }})
            </small>
        </h5>
    </div>

    @can('viewAny', App\Models\User::class)
        <!-- Today's Sales -->
        <div class="row mb-3">
            <div class="col-md-12">
                <h3 class="fw-bold text-primary mb-3 fs-4">Today's Sales</h3>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <div class="card-sales sales shadow-sm">
                    <div class="text-sales">Total Penjualan Hari Ini</div>
                    <div class="text-sale">Rp {{ number_format($ringkasan['total_penjualan'], 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="col-12 col-md-6 mb-3">
                <div class="card-sales sales shadow-sm">
                    <div class="text-sales">Jumlah Transaksi Hari Ini</div>
                    <div class="text-sale">{{ $ringkasan['total_transaksi'] }} Transaksi</div>
                </div>
            </div>
        </div>

        <!-- Cash & Payment Status -->
        <div class="row mb-3">
            <div class="col-md-12">
                <h3 class="fw-bold text-success mb-3 fs-4">Cash &amp; Payment Status</h3>
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
    @endcan

    <!-- Critical Inventory Status -->
    <div class="row mb-2">
        <div class="col-md-12 mb-0">
            <h3 class="fs-4 fw-bold text-dark">Critical Inventory Status</h3>
        </div>

        <!-- Tabel Stok Rendah -->
        <div class="col-12 col-md-6 mb-3">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-secondary fs-6 mb-0 fw-semibold">⚠️ Daftar Produk Stok Rendah</h5>
                    <span class="badge bg-warning text-dark">Perlu Perhatian</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col" class="text-center">Sisa Stok</th>
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
                                <tr>
                                    <td colspan="3" class="text-muted text-center py-3">
                                        Seluruh produk berada dalam kondisi stok aman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $produkStokRendah->links() }}
                </div>
            </div>
        </div>

        <!-- Tabel Produk Habis -->
        <div class="col-12 col-md-6 mb-3">
            <div class="card border-0 shadow-sm p-3 bg-white rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-secondary fs-6 mb-0 fw-semibold">❌ Produk Habis Stok</h5>
                    <span class="badge bg-danger">Urgent</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col" class="text-center">Sisa Stok</th>
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
                                <tr>
                                    <td colspan="3" class="text-muted text-center py-3">
                                        Seluruh produk berada dalam kondisi stok aman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $produkStokHabis->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Best Seller Products -->
    <div class="row">
        <div class="col-md-12 mb-2">
            <h3 class="fs-4 fw-bold text-dark">Best Seller Products</h3>
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
                                        Belum ada data penjualan tercatat.
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