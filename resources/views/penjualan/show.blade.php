<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Penjualan - POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="container" style="max-width: 700px;">

            <div class="mb-3">
                <a href="{{ route('penjualan.index') }}" class="text-decoration-none text-secondary fw-semibold small">
                    &larr; Kembali ke Daftar Penjualan
                </a>
            </div>

            <div class="card shadow-sm position-relative" style="min-height: 300px;">

                {{-- Loading overlay --}}
                <div id="loading-content"
                    class="position-absolute top-0 start-0 w-100 h-100 bg-white d-flex flex-column align-items-center justify-content-center"
                    style="z-index: 40; transition: opacity 0.3s ease-out;">
                    <div class="d-flex align-items-center gap-2 bg-light px-4 py-2 rounded-pill border shadow-sm">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <span class="text-muted fw-medium small">Memuat detail transaksi...</span>
                    </div>
                </div>

                <div class="card-header bg-light">
                    <h5 class="mb-1 fw-bold">Detail Transaksi Penjualan</h5>
                    <p class="text-muted small mb-0">Rincian transaksi dan barang yang terjual.</p>
                </div>

                <div class="card-body p-0">

                    {{-- ==== INFO TRANSAKSI ==== --}}
                    <div class="table-responsive border-bottom">
                        <table class="table table-borderless mb-0 align-middle">
                            <tbody>
                                <tr class="bg-light bg-opacity-50">
                                    <td class="fw-semibold px-4 py-3" style="width: 35%;">Tanggal Transaksi</td>
                                    <td class="px-4 py-3">{{ $penjualan->created_at->translatedFormat('d-m-Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold px-4 py-3">Kasir</td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill">
                                            {{ $penjualan->user->name }}
                                        </span>
                                    </td>
                                </tr>
                                <tr class="bg-light bg-opacity-50">
                                    <td class="fw-semibold px-4 py-3">Metode Pembayaran</td>
                                    <td class="px-4 py-3">{{ $penjualan->metode_pembayaran ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold px-4 py-3">Status</td>
                                    <td class="px-4 py-3">
                                        @if($penjualan->status === 'COMPLETED')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($penjualan->status === 'OPEN')
                                            <span class="badge bg-warning text-dark">Open</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $penjualan->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr class="bg-light bg-opacity-50">
                                    <td class="fw-semibold px-4 py-3">Total Pembayaran</td>
                                    <td class="px-4 py-3 fw-bold">
                                        Rp {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- ==== RINCIAN ITEM ==== --}}
                    <div class="p-4">
                        <h6 class="fw-semibold mb-2">Rincian Barang</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-end">Harga Satuan</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($penjualan->itemPenjualan as $item)
                                        <tr>
                                            <td>{{ $item->produk->nama }}</td>
                                            <td class="text-end">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                                            <td class="text-center">{{ $item->kuantitas }}</td>
                                            <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">Tidak ada item</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white d-flex gap-2 justify-content-end">
                    <a href="{{ route('penjualan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.addEventListener("load", function () {
            const loadingContent = document.getElementById("loading-content");

            setTimeout(() => {
                loadingContent.style.opacity = "0";

                setTimeout(() => {
                    loadingContent.classList.add("d-none");
                }, 300);
            }, 700);
        });
    </script>
</body>

</html>