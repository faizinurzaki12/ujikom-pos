@extends('layouts.app')

@section('title', $mode === 'edit' ? 'Edit Penjualan' : 'Tambah Penjualan')

@section('content')

<div class="container pb-4" style="max-width: 1200px">
    <!-- Tombol Kembali -->
    <div class="mb-3">
        <a href="{{ route('penjualan.index') }}" class="text-decoration-none text-secondary fw-semibold small">
            &larr; Kembali ke Daftar Penjualan
        </a>
    </div>

    @if(session('errors') && !is_object(session('errors')))
        <div class="alert alert-danger">
            {{ session('errors') }}
        </div>
    @endif

    <h4 class="mb-3 fw-bold">
        {{ $mode === 'edit' ? 'Edit Penjualan' : 'Tambah Penjualan' }}
    </h4>

    <div class="row g-4">

        {{-- ================== DAFTAR PRODUK ================== --}}
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
                            <button type="submit" class="btn btn-primary w-100 {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">
                                +
                            </button>
                        </div>
                    </form>
                    @endforeach

                </div>
            </div>
        </div>

        {{-- ================== KERANJANG BELANJA ================== --}}
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
                                    <th>Jenis</th>
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
                                    <td>{{ $item->produk->jenis->nama_jenis ?? '-' }}</td>
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
                                    <td colspan="6" class="text-center text-muted py-3">Keranjang kosong</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted fw-semibold">Total Pembayaran:</span>
                        <strong class="fs-5 text-dark">Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}</strong>
                    </div>

                    {{-- ================== FORM CHECKOUT ================== --}}
                    <form method="POST" action="{{ route('penjualan.update', $sale->id) }}"
                        onsubmit="return handleCheckoutSubmit(event);" id="checkoutForm"
                        data-total="{{ $sale->total_pembayaran }}">
                        @csrf
                        @method('PUT')

                        <select name="payment_method" id="paymentMethod" class="form-select mb-3 @error('payment_method') is-invalid @enderror" onchange="togglePaymentInputs()">
                            <option value="">Pilih Pembayaran</option>
                            <option value="CASH" {{ old('payment_method') === 'CASH' ? 'selected' : '' }}>Cash (Tunai)</option>
                            <option value="QRIS" {{ old('payment_method') === 'QRIS' ? 'selected' : '' }}>QRIS (Simulasi)</option>
                        </select>
                        @error('payment_method')
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        <!-- INPUT PEMBAYARAN CASH -->
                        <div id="cashInputWrapper" class="mb-3 d-none">
                            <div class="p-3 border rounded bg-light mb-3">
                                <div class="mb-3">
                                    <label for="inputUang" class="form-label small fw-semibold">Uang Dibayar</label>
                                    <input type="text" 
                                           name="uang_dibayar" 
                                           id="inputUang" 
                                           class="form-control @error('uang_dibayar') is-invalid @enderror" 
                                           placeholder="Masukkan jumlah uang tunai..."
                                           value="{{ old('uang_dibayar') }}"
                                           autocomplete="off"
                                           oninput="formatRupiahInput(this); hitungKembalian();">
                                    @error('uang_dibayar')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Kembalian</span>
                                        <div class="text-end">
                                            <div id="textKembalian" class="fw-bold">Rp.0</div>
                                            <div id="pesanUangKurang" class="text-danger small fw-semibold d-none">
                                                Uang Kurang
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="kembalian" id="kembalianInput" value="0">
                            </div>
                        </div>

                        <!-- DISPLAY QRIS DUMMY -->
                        <div id="qrisInputWrapper" class="mb-3 d-none">
                            <div class="p-3 border rounded bg-light text-center">
                                <span class="badge bg-primary mb-2">Sistem Pembayaran QRIS</span>
                                <h6 class="fw-bold text-dark mb-1">Pindai Kode QR</h6>
                                <p class="text-muted small mb-2">Simulasi QRIS Pembayaran Toko Kasir</p>

                                <div class="d-inline-block p-2 bg-white rounded shadow-sm border mb-2">
                                    <img src="https://quickchart.io/qr?text=SIMULASI_QRIS_UJIKOM_SALE_{{ $sale->id }}_TOTAL_{{ $sale->total_pembayaran }}&size=180" 
                                         alt="QRIS Simulasi" 
                                         class="img-fluid"
                                         style="max-width: 180px; height: auto;">
                                </div>

                                <div class="fw-bold text-success mb-1">
                                    Total Tagihan: Rp {{ number_format($sale->total_pembayaran, 0, ',', '.') }}
                                </div>
                                <small class="text-muted d-block">Klik tombol Checkout di bawah untuk mensimulasikan pembayaran lunas.</small>
                            </div>
                        </div>

                        <!-- TOMBOL CHECKOUT & LOADING SPINNER -->
                        <button type="submit" id="btnSubmitCheckout" class="btn btn-success w-100 py-2 fw-semibold {{ $sale->status === 'COMPLETED' ? 'disabled' : '' }}">
                            <span id="btnText">Checkout</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
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

<script>
    function formatRupiahInput(element) {
        let value = element.value.replace(/[^0-9]/g, '');
        if (value) {
            element.value = parseInt(value, 10).toLocaleString('id-ID');
        } else {
            element.value = '';
        }
    }

    function togglePaymentInputs() {
        const paymentMethod = document.getElementById('paymentMethod').value;
        const cashWrapper = document.getElementById('cashInputWrapper');
        const qrisWrapper = document.getElementById('qrisInputWrapper');
        
        cashWrapper.classList.add('d-none');
        qrisWrapper.classList.add('d-none');

        if (paymentMethod === 'CASH') {
            cashWrapper.classList.remove('d-none');
            const inputUang = document.getElementById('inputUang');
            if (inputUang.value) {
                formatRupiahInput(inputUang);
            }
            hitungKembalian();
        } else if (paymentMethod === 'QRIS') {
            qrisWrapper.classList.remove('d-none');
        }
    }

    function hitungKembalian() {
        const checkoutForm = document.getElementById('checkoutForm');
        const totalHarga = parseFloat(checkoutForm.getAttribute('data-total')) || 0;
        
        const rawValue = document.getElementById('inputUang').value.replace(/[^0-9]/g, '');
        const inputUang = parseFloat(rawValue) || 0;

        const textKembalian = document.getElementById('textKembalian');
        const pesanUangKurang = document.getElementById('pesanUangKurang');
        const kembalianInput = document.getElementById('kembalianInput');

        const selisih = inputUang - totalHarga;

        if (inputUang === 0) {
            textKembalian.innerText = 'Rp.0';
            kembalianInput.value = 0;
            pesanUangKurang.classList.add('d-none');
            return;
        }

        if (selisih >= 0) {
            textKembalian.innerText = 'Rp.' + selisih.toLocaleString('id-ID');
            kembalianInput.value = selisih;
            pesanUangKurang.classList.add('d-none');
        } else {
            const kekurangannya = Math.abs(selisih);
            textKembalian.innerText = 'Rp.0';
            kembalianInput.value = 0;
            pesanUangKurang.innerText = 'Uang Kurang Rp.' + kekurangannya.toLocaleString('id-ID');
            pesanUangKurang.classList.remove('d-none');
        }
    }

    function handleCheckoutSubmit(e) {
        const paymentMethod = document.getElementById('paymentMethod').value;
        const checkoutForm = document.getElementById('checkoutForm');
        
        if (paymentMethod === 'CASH') {
            const totalHarga = parseFloat(checkoutForm.getAttribute('data-total')) || 0;
            const rawValue = document.getElementById('inputUang').value.replace(/[^0-9]/g, '');
            const inputUang = parseFloat(rawValue) || 0;

            if (inputUang < totalHarga) {
                e.preventDefault();
                document.getElementById('pesanUangKurang').classList.remove('d-none');
                document.getElementById('inputUang').focus();
                return false;
            }
        }

        if (!confirm('Proses checkout transaksi ini?')) {
            e.preventDefault();
            return false;
        }

        if (paymentMethod === 'QRIS') {
            e.preventDefault();

            const btnSubmit = document.getElementById('btnSubmitCheckout');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            btnSubmit.classList.add('disabled');
            btnText.innerText = 'Memverifikasi Status Pembayaran QRIS...';
            btnSpinner.classList.remove('d-none');

            setTimeout(function() {
                checkoutForm.submit();
            }, 2500);

            return false;
        }

        return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        togglePaymentInputs();
    });
</script>

@endsection