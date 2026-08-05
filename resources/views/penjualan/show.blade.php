<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail Penjualan - POS</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  </head>

  <body class="bg-light">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh; padding: 20px 0">
      <div class="container" style="max-width: 700px">
        <!-- Tombol Kembali -->
        <div class="mb-3">
          <a href="{{ route('penjualan.index') }}" class="text-decoration-none text-secondary fw-semibold small"> &larr; Kembali ke Daftar Penjualan </a>
        </div>

        <div class="card shadow-sm">
          <div class="card-header bg-light">
            <h5 class="mb-1 fw-bold">Detail Transaksi Penjualan</h5>
            <p class="text-muted small mb-0">Rincian transaksi dan barang yang terjual.</p>
          </div>

          <div class="card-body p-0">
            <!-- ================= BAGIAN A: SKELETON SCREEN (Dikomentari) ================= -->
            <!--
            <div id="skeleton-state" class="placeholder-glow">
              <!-- Skeleton Info Transaksi -->
              <div class="table-responsive border-bottom">
                <table class="table table-borderless mb-0 align-middle">
                  <tbody>
                    <tr class="bg-light bg-opacity-50">
                      <td class="fw-semibold px-4 py-3" style="width: 35%">
                        <span class="placeholder col-8 bg-secondary opacity-25"></span>
                      </td>
                      <td class="px-4 py-3">
                        <span class="placeholder col-6 bg-secondary opacity-25"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold px-4 py-3">
                        <span class="placeholder col-5 bg-secondary opacity-25"></span>
                      </td>
                      <td class="px-4 py-3">
                        <span class="placeholder rounded-pill col-5 bg-secondary opacity-25" style="height: 24px; display: inline-block"></span>
                      </td>
                    </tr>
                    <tr class="bg-light bg-opacity-50">
                      <td class="fw-semibold px-4 py-3">
                        <span class="placeholder col-7 bg-secondary opacity-25"></span>
                      </td>
                      <td class="px-4 py-3">
                        <span class="placeholder col-4 bg-secondary opacity-25"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="fw-semibold px-4 py-3">
                        <span class="placeholder col-4 bg-secondary opacity-25"></span>
                      </td>
                      <td class="px-4 py-3">
                        <span class="placeholder rounded col-3 bg-secondary opacity-25" style="height: 22px; display: inline-block"></span>
                      </td>
                    </tr>
                    <tr class="bg-light bg-opacity-50">
                      <td class="fw-semibold px-4 py-3">
                        <span class="placeholder col-7 bg-secondary opacity-25"></span>
                      </td>
                      <td class="px-4 py-3">
                        <span class="placeholder col-5 bg-secondary opacity-25"></span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Skeleton Rincian Barang -->
              <div class="p-4">
                <h6 class="fw-semibold mb-2"><span class="placeholder col-4 bg-secondary opacity-25"></span></h6>
                <div class="table-responsive">
                  <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light">
                      <tr>
                        <th><span class="placeholder col-6 bg-secondary opacity-25"></span></th>
                        <th class="text-end"><span class="placeholder col-8 bg-secondary opacity-25"></span></th>
                        <th class="text-center"><span class="placeholder col-4 bg-secondary opacity-25"></span></th>
                        <th class="text-end"><span class="placeholder col-6 bg-secondary opacity-25"></span></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><span class="placeholder col-8 bg-secondary opacity-25"></span></td>
                        <td class="text-end"><span class="placeholder col-6 bg-secondary opacity-25"></span></td>
                        <td class="text-center"><span class="placeholder col-4 bg-secondary opacity-25"></span></td>
                        <td class="text-end"><span class="placeholder col-6 bg-secondary opacity-25"></span></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            -->

            <!-- ================= BAGIAN B: KONTEN ASLI LARAVEL ================= -->
            <div id="content-state"> <!-- d-none dihapus agar langsung tampil -->
              <!-- ==== INFO TRANSAKSI ==== -->
              <div class="table-responsive border-bottom">
                <table class="table table-borderless mb-0 align-middle">
                  <tbody>
                    <tr class="bg-light bg-opacity-50">
                      <td class="fw-semibold px-4 py-3" style="width: 35%">Tanggal Transaksi</td>
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
                      <td class="px-4 py-3 fw-bold">Rp {{ number_format($penjualan->total_pembayaran, 0, ',', '.') }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- ==== RINCIAN BARANG ==== -->
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
          </div>

          <div class="card-footer bg-white d-flex gap-2 justify-content-end">
            <a href="{{ route('penjualan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Pengontrol State (Dikomentari) -->
    <!--
    <script>
      const skeletonState = document.getElementById("skeleton-state");
      const contentState = document.getElementById("content-state");

      window.addEventListener("load", function () {
        skeletonState.classList.add("d-none");
        contentState.classList.remove("d-none");
      });
    </script>
    -->
  </body>
</html>