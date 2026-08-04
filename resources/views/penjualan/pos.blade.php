@extends('layouts.app')

@section('title', $mode === 'edit' ? 'Edit Penjualan' : 'Tambah Penjualan')

@section('content')

<div class="position-relative" style="min-height: 80vh;">

    <!-- ================= SPINNER (Loading State) ================= -->
    <div id="loading-state" class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-white" style="z-index: 1000;">
        <div class="spinner-border text-primary mb-2" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <span class="text-muted fw-medium">Memuat halaman...</span>
    </div>

    <!-- ================= KONTEN ASLI LARAVEL (Disembunyikan saat Loading) ================= -->
    <div id="content-state" class="d-none">
        <div class="container pb-4" style="max-width: 1200px">
            <!-- Tombol Kembali -->
            <div class="mb-3">
                <a href="{{ route('penjualan.index') }}" class="text-decoration-none text-secondary fw-semibold small">
                    &larr; Kembali ke Daftar Penjualan
                </a>
            </div>

            @if(session('errors'))
                <div class="alert alert-danger">
                    {{ session('errors') }}
                </div>
            @endif

            <h4 class="mb-3 fw-bold">
                {{ $mode === 'edit' ? 'Edit Penjualan' : 'Tambah Penjualan' }}
            </h4>

            <div class="row g-4">

                {{-- ================== PRODUK ================== --}}
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-3" style="max-height: 70vh; overflow-y: auto">

                            <!-- Form Search Produk -->
                            <div class="mb-3">
                                <form method="GET" action="{{ route('penjualan.create') }}">
                                    <input type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        class="form-control"
                                        placeholder="Cari produk...">
                                </form>
                            </div>

                            @foreach($products as $product)
                            <form method="POST" action="{{ route('itempenjualan.store') }}" class="row mb-2 align-items-center">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                <div class="col-7">
                                    <button type="submit" class="btn btn-outline-primary w-100 text-start p-2 {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">
                                        <div class="d-flex align-items-center gap-2">
                                            {{-- Gambar produk --}}
                                            <img src="{{ asset('storage/'.$product->foto) }}"
                                                alt="Gambar"
                                                class="rounded-circle"
                                                style="width:45px; height:45px; object-fit:cover;">

                                            {{-- Nama & harga --}}
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $product->nama }}</div>
                                                <small class="text-muted">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</small>
                                            </div>
                                        </div>
                                    </button>
                                </div>

                                <div class="col-3">
                                    <input type="number" name="quantity" value="1" min="1"
                                        class="form-control {{ $sale->status === 'COMPLETED' ? 'readonly' : '' }}">
                                </div>

                                <div class="col-2">
                                    <button type="submit" class="btn btn-primary w-100 {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">+</button>
                                </div>
                            </form>
                            @endforeach

                        </div>
                    </div>
                </div>

                {{-- ================== KERANJANG ================== --}}
                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 fw-bold">Keranjang Belanja</h5>
                        </div>

                        <div class="p-3">
                            <div class="table-responsive mb-3">
                                <table class="table table-bordered mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th style="width: 90px">Qty</th>
                                            <th>Subtotal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($sale->itemPenjualan as $item)
                                        <tr>
                                            <td>{{ $item->produk->nama }}</td>
                                            <td>Rp {{ number_format($item->produk->harga_jual, 0, ',', '.') }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('itempenjualan.update', $item->id) }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number" name="quantity"
                                                        value="{{ $item->kuantitas }}"
                                                        class="form-control form-control-sm"
                                                        onchange="this.form.submit();">
                                                </form>
                                            </td>
                                            <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                            <td>
                                                @can('delete', $item)
                                                <form method="POST" action="{{ route('itempenjualan.destroy', $item->id) }}" onsubmit="return confirm('Yakin ingin menghapus item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                                </form>
                                                @endcan
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">Keranjang kosong</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted fw-semibold">Total Pembayaran:</span>
                                <strong class="fs-5 text-dark">Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</strong>
                            </div>

                            <form method="POST" action="{{ route('penjualan.update', $sale->id) }}"
                                onsubmit="return confirm('Yakin ingin checkout?');">
                                @csrf
                                @method('PUT')
                                <select name="payment_method" class="form-select mb-3">
                                    <option value="">Pilih Pembayaran</option>
                                    <option value="CASH">Cash</option>
                                    <option value="QRIS">QRIS</option>
                                </select>

                                <button type="submit" class="btn btn-success w-100 py-2 fw-semibold {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">
                                    Checkout
                                </button>
                            </form>

                            @can('delete', $sale)
                            <form action="{{ route('penjualan.destroy', $sale->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin membatalkan transaksi?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100 mt-2 {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">
                                    Batalkan Transaksi
                                </button>
                            </form>
                            @endcan
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- Script Pure Real Network Loading (sama seperti halaman Detail Produk) -->
<script>
    const mainContent = document.querySelector('.main-content');

    // Matikan auto-scroll browser ke posisi terakhir saat reload
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }
    if (mainContent) {
        mainContent.scrollTop = 0;
    }
    const loadingState = document.getElementById("loading-state");
    const contentState = document.getElementById("content-state");

    window.addEventListener("load", function () {
        loadingState.classList.add("d-none");
        contentState.classList.remove("d-none");
    });
</script>

@endsection