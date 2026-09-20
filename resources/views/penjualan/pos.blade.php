@extends('layouts.app')

@section('title', $mode === 'edit' ? 'Edit Penjualan' : 'Tambah Penjualan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/penjualan/pos.css') }}">
@endpush

@section('content')
<div class="container-fluid px-0 pb-4 pos-container">
    <div class="mb-3">
        <a href="{{ route('penjualan.index') }}" class="text-decoration-none text-secondary fw-semibold small">
            &larr; Kembali ke Daftar Penjualan
        </a>
    </div>

    <h4 class="mb-3 fw-bold">{{ $mode === 'edit' ? 'Edit Penjualan' : 'Tambah Penjualan' }}</h4>

    <div class="row g-3">
        <!-- Daftar Produk -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3 product-scroll-wrapper">
                    <form method="GET" action="{{ route('penjualan.create') }}" class="mb-3">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari produk...">
                    </form>

                    @foreach($products as $product)
                    <form method="POST" action="{{ route('itempenjualan.store') }}" class="row g-2 mb-2 align-items-center pos-product-item">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="col-7 col-sm-8">
                            <button type="submit" class="btn btn-outline-primary w-100 text-start p-2 {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ asset('storage/'.$product->foto) }}" alt="Gambar" class="rounded-circle flex-shrink-0 product-thumb">
                                    <div class="text-truncate">
                                        <div class="fw-semibold text-dark text-truncate">{{ $product->nama }}</div>
                                        <small class="text-muted d-block">Rp {{ number_format($product->harga_jual, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                            </button>
                        </div>

                        <div class="col-3 col-sm-2">
                            <input type="number" name="quantity" value="1" min="1" class="form-control text-center p-1 pos-qty-input {{ $sale->status === 'COMPLETED' ? 'readonly' : '' }}">
                        </div>

                        <div class="col-2 col-sm-2">
                            <button type="submit" class="btn btn-primary w-100 {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">+</button>
                        </div>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Keranjang Belanja -->
        <div class="col-12 col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Keranjang Belanja</h5>
                </div>

                <div class="p-3">
                    <div class="table-responsive cart-table-wrapper mb-3">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th style="width: 75px">Qty</th>
                                    <th>Subtotal</th>
                                    <th style="width: 50px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sale->itemPenjualan as $item)
                                <tr>
                                    <td data-label="Produk" class="text-truncate" style="max-width: 120px;">{{ $item->produk->nama }}</td>
                                    <td data-label="Harga" class="text-nowrap">Rp {{ number_format($item->produk->harga_jual, 0, ',', '.') }}</td>
                                    <td data-label="Qty">
                                        <form method="POST" action="{{ route('itempenjualan.update', $item->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="quantity" value="{{ $item->kuantitas }}" class="form-control form-control-sm text-center px-1" onchange="this.form.submit();">
                                        </form>
                                    </td>
                                    <td data-label="Subtotal" class="text-nowrap">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    <td data-label="Aksi">
                                        @can('delete', $item)
                                        <form method="POST" action="{{ route('itempenjualan.destroy', $item->id) }}" onsubmit="return confirm('Yakin hapus item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm p-1"><i class="bi bi-trash"></i></button>
                                        </form>
                                        @endcan
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">Keranjang masih kosong</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted fw-semibold">Total Pembayaran:</span>
                        <strong class="fs-5 text-dark">Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</strong>
                    </div>

                    <form method="POST" action="{{ route('penjualan.update', $sale->id) }}" onsubmit="return handleCheckoutSubmit(event);" id="checkoutForm" data-total="{{ $sale->total_pembayaran }}">
                        @csrf
                        @method('PUT')

                        <select name="payment_method" id="paymentMethod" class="form-select mb-3" onchange="togglePaymentInputs()">
                            <option value="">Pilih Pembayaran</option>
                            <option value="CASH" {{ old('payment_method') === 'CASH' ? 'selected' : '' }}>Cash (Tunai)</option>
                            <option value="QRIS" {{ old('payment_method') === 'QRIS' ? 'selected' : '' }}>QRIS (Simulasi)</option>
                        </select>

                        <div id="cashInputWrapper" class="mb-3 d-none">
                            <div class="p-3 border rounded bg-light mb-3">
                                <div class="mb-3">
                                    <label for="inputUang" class="form-label small fw-semibold">Uang Dibayar</label>
                                    <input type="text" name="uang_dibayar" id="inputUang" class="form-control" placeholder="Masukkan jumlah uang tunai..." value="{{ old('uang_dibayar') }}" autocomplete="off" oninput="formatRupiahInput(this); hitungKembalian();">
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Kembalian</span>
                                    <div class="text-end">
                                        <div id="textKembalian" class="fw-bold">Rp.0</div>
                                        <div id="pesanUangKurang" class="text-danger small fw-semibold d-none">Uang Kurang</div>
                                    </div>
                                </div>
                                <input type="hidden" name="kembalian" id="kembalianInput" value="0">
                            </div>
                        </div>

                        <div id="qrisInputWrapper" class="mb-3 d-none">
                            <div class="p-3 border rounded bg-light text-center">
                                <span class="badge bg-primary mb-2">Sistem Pembayaran QRIS</span>
                                <h6 class="fw-bold text-dark mb-1">Pindai Kode QR</h6>
                                <div class="d-inline-block p-2 bg-white rounded shadow-sm border mb-2">
                                    <img src="{{ asset(env('QRIS_IMAGE_PATH', 'assets/img/qris-saya.png')) }}" alt="QRIS Toko" class="img-fluid" style="max-width: 180px;">
                                </div>
                                <div class="fw-bold text-success mb-1">Total Tagihan: Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</div>
                            </div>
                        </div>

                        <button type="submit" id="btnSubmitCheckout" class="btn btn-success w-100 py-2 fw-semibold {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">
                            <span id="btnText">Checkout</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                        </button>
                    </form>

                    @can('delete', $sale)
                    <form action="{{ route('penjualan.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan transaksi?');">
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

<script>
    function formatRupiahInput(element) {
        let value = element.value.replace(/[^0-9]/g, '');
        element.value = value ? parseInt(value, 10).toLocaleString('id-ID') : '';
    }

    function togglePaymentInputs() {
        const paymentMethod = document.getElementById('paymentMethod').value;
        const cashWrapper = document.getElementById('cashInputWrapper');
        const qrisWrapper = document.getElementById('qrisInputWrapper');
        
        cashWrapper.classList.add('d-none');
        qrisWrapper.classList.add('d-none');

        if (paymentMethod === 'CASH') {
            cashWrapper.classList.remove('d-none');
            hitungKembalian();
        } else if (paymentMethod === 'QRIS') {
            qrisWrapper.classList.remove('d-none');
        }
    }

    function hitungKembalian() {
        const totalHarga = parseFloat(document.getElementById('checkoutForm').getAttribute('data-total')) || 0;
        const inputUang = parseFloat(document.getElementById('inputUang').value.replace(/[^0-9]/g, '')) || 0;
        const selisih = inputUang - totalHarga;

        const textKembalian = document.getElementById('textKembalian');
        const pesanUangKurang = document.getElementById('pesanUangKurang');

        if (inputUang === 0) {
            textKembalian.innerText = 'Rp.0';
            pesanUangKurang.classList.add('d-none');
            return;
        }

        if (selisih >= 0) {
            textKembalian.innerText = 'Rp.' + selisih.toLocaleString('id-ID');
            pesanUangKurang.classList.add('d-none');
        } else {
            textKembalian.innerText = 'Rp.0';
            pesanUangKurang.innerText = 'Uang Kurang Rp.' + Math.abs(selisih).toLocaleString('id-ID');
            pesanUangKurang.classList.remove('d-none');
        }
    }

    function handleCheckoutSubmit(e) {
        const paymentMethod = document.getElementById('paymentMethod').value;
        if (paymentMethod === 'CASH') {
            const totalHarga = parseFloat(document.getElementById('checkoutForm').getAttribute('data-total')) || 0;
            const inputUang = parseFloat(document.getElementById('inputUang').value.replace(/[^0-9]/g, '')) || 0;
            if (inputUang < totalHarga) {
                e.preventDefault();
                alert('Jumlah uang pembayaran tunai kurang!');
                return false;
            }
        }

        if (!confirm('Proses checkout transaksi ini?')) {
            e.preventDefault();
            return false;
        }
        return true;
    }

    document.addEventListener('DOMContentLoaded', togglePaymentInputs);
</script>
@endsection