@extends('layouts.app')

@section('title', 'Dashboard POS')

@section('content')
<div class="container-fluid px-0 dashboard-flex">

    <!-- Header Tanggal -->
    <div class="row-stat px-2 px-md-0 text-start">
        <h5 class="fw-bold mb-2">
            Ringkasan Hari Ini
            <small class="text-muted fs-6 d-block d-md-inline">
                ({{ $tanggalHariIni->translatedFormat('l, d F Y') }})
            </small>
        </h5>
    </div>

    @can('viewAny', App\Models\User::class)
        <!-- Today's Sales & Cash Payment -->
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

    <!-- Critical Inventory Status -->
    <div class="row-stat mt-1">
        <h6 class="fw-bold text-dark mb-1 fs-6">Critical Inventory Status</h6>
    </div>
    <div class="row g-2 align-items-start">
        <div class="col-12 col-md-6">
            <div class="card card-table-flex border-0 shadow-sm p-2 bg-white rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-1 px-1">
                    <span class="text-secondary mb-0 fw-semibold fs-7">⚠️ Stok Rendah</span>
                    <span class="badge bg-warning text-dark">Perlu Perhatian</span>
                </div>
                <div class="table-responsive table-scroll-limited">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-dark sticky-top">
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
                @if($produkStokRendah->hasPages())
                    <div class="mt-1 fs-7">{{ $produkStokRendah->links() }}</div>
                @endif
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card card-table-flex border-0 shadow-sm p-2 bg-white rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-1 px-1">
                    <span class="text-secondary mb-0 fw-semibold fs-7">❌ Habis Stok</span>
                    <span class="badge bg-danger">Urgent</span>
                </div>
                <div class="table-responsive table-scroll-limited">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-dark sticky-top">
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
                @if($produkStokHabis->hasPages())
                    <div class="mt-1 fs-7">{{ $produkStokHabis->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Best Seller Products -->
    <div class="row-stat mt-1">
        <h6 class="fw-bold text-dark mb-1 fs-6">Best Seller Products</h6>
    </div>
    <div class="row g-2">
        <div class="col-12">
            <div class="card card-table-flex border-0 shadow-sm p-2 bg-white rounded-3">
                <div class="table-responsive">
                    <table class="table table-sm table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr class="table-dark">
                                <th>Nama Produk</th>
                                <th>Stok Tersedia</th>
                                <th class="text-center">Unit Terjual</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produkTerlaris as $produk)
                                <tr>
                                    <td class="fw-medium">{{ $produk->nama }}</td>
                                    <td>{{ $produk->stok }} Pcs</td>
                                    <td class="text-center"><span class="badge bg-success-subtle text-success fw-bold px-3 py-1">{{ $produk->total_terjual }} Terjual</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-muted text-center py-3">Belum ada data.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection