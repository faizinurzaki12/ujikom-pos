<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Produk - POS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="container" style="max-width: 600px;">

            <div class="mb-3">
                <a href="{{ route('produk.index') }}" class="text-decoration-none text-secondary fw-semibold small">
                    &larr; Kembali ke Daftar Produk
                </a>
            </div>

            <div class="card shadow-sm position-relative" style="min-height: 300px;">

                {{-- Loading overlay --}}
                <div id="loading-content"
                    class="position-absolute top-0 start-0 w-100 h-100 bg-white d-flex flex-column align-items-center justify-content-center"
                    style="z-index: 40; transition: opacity 0.3s ease-out;">
                    <div class="d-flex align-items-center gap-2 bg-light px-4 py-2 rounded-pill border shadow-sm">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <span class="text-muted fw-medium small">Memuat detail produk...</span>
                    </div>
                </div>

                <div class="card-header bg-light">
                    <h5 class="mb-1 fw-bold">Detail Informasi Produk</h5>
                    <p class="text-muted small mb-0">Rincian data produk yang tersimpan di sistem.</p>
                </div>

                <div class="card-body p-0">

                    @if($produk->foto)
                        <div class="text-center p-4 border-bottom">
                            <img src="{{ asset('storage/'.$produk->foto) }}" alt="{{ $produk->nama }}"
                                class="rounded" style="width: 160px; height: 160px; object-fit: cover;">
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-borderless mb-0 align-middle">
                            <tbody>
                                <tr class="bg-light bg-opacity-50">
                                    <td class="fw-semibold px-4 py-3" style="width: 35%;">Nama Produk</td>
                                    <td class="px-4 py-3">{{ $produk->nama }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold px-4 py-3">Ditambahkan oleh</td>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-primary-subtle text-primary-emphasis rounded-pill">
                                            {{ $produk->user->name }}
                                        </span>
                                    </td>
                                </tr>
                                <tr class="bg-light bg-opacity-50">
                                    <td class="fw-semibold px-4 py-3">Harga Beli</td>
                                    <td class="px-4 py-3">Rp {{ number_format($produk->harga_beli, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold px-4 py-3">Harga Jual</td>
                                    <td class="px-4 py-3">Rp {{ number_format($produk->harga_jual, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="bg-light bg-opacity-50">
                                    <td class="fw-semibold px-4 py-3">Stok</td>
                                    <td class="px-4 py-3">
                                        @if($produk->stok <= 0)
                                            <span class="badge bg-danger">Habis</span>
                                        @elseif($produk->stok < 5)
                                            <span class="badge bg-warning text-dark">{{ $produk->stok }} (Rendah)</span>
                                        @else
                                            <span class="badge bg-success">{{ $produk->stok }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white d-flex gap-2 justify-content-end">
                    <a href="{{ route('produk.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                    @can('update', $produk)
                        <a href="{{ route('produk.edit', $produk) }}" class="btn btn-warning btn-sm">Edit</a>
                    @endcan
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