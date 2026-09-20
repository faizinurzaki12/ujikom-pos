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
            <!-- card  -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-1 fw-bold">Detail Informasi Produk</h5>
                    <p class="text-muted small mb-0">Rincian data produk yang tersimpan di sistem.</p>
                </div>

                <div class="card-body p-0">

                    @if($produk->foto)
                        <div class="text-center p-4 border-bottom">
                            {{-- 
                                id="fotoProduk" dipakai JavaScript di bawah untuk "menandai" gambar ini.
                                cursor-zoom-in cuma styling supaya kursor mouse berubah pas hover (nunjukin bisa diklik).
                            --}}
                            <img
                                src="{{ asset('storage/'.$produk->foto) }}"
                                alt="{{ $produk->nama }}"
                                id="fotoProduk"
                                class="rounded"
                                style="width: 160px; height: 160px; object-fit: cover; cursor: zoom-in;"
                            >
                            <!-- <div class="text-muted small mt-2">
                                <i class="bi bi-zoom-in"></i> Klik foto untuk memperbesar
                            </div> -->
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-borderless mb-0 align-middle">
                            <tbody>
                                <tr class="bg-light bg-opacity-50">
                                    <td class="fw-semibold px-4 py-3" style="width: 35%;">Nama Produk</td>
                                    <td class="px-4 py-3">{{ $produk->nama }}</td>
                                </tr>
                                <tr class="bg-light bg-opacity-50">
                                    <td class="fw-semibold px-4 py-3" style="width: 35%;">Jenis</td>
                                    <td class="px-4 py-3">{{ $produk->jenis->nama_jenis ?? '-' }}</td>
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
            </div>
        </div>
    </div>

    {{-- 
        ===== INI OVERLAY-NYA (masih kosong/tersembunyi) =====
        Div ini awalnya nggak keliatan (display: none di CSS bawah).
        Nanti JavaScript yang akan "menampilkannya" pas foto diklik.
        Di dalamnya cuma ada 1 elemen <img> kosong (id="fotoBesar") yang
        akan kita isi src-nya pakai JavaScript juga.
    --}}
    <div id="overlayFoto">
        <img id="fotoBesar" src="" alt="Foto diperbesar">
    </div>

    <style>
        /* Tampilan overlay gelap full-screen */
        #overlayFoto {
            display: none;              /* defaultnya disembunyikan */
            position: fixed;            /* nempel di layar, bukan ikut scroll halaman */
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            z-index: 9999;              /* biar nutupin semua elemen lain */
            justify-content: center;    /* posisikan foto besar ke tengah horizontal */
            align-items: center;        /* posisikan foto besar ke tengah vertikal */
            cursor: zoom-out;           /* kursor jadi "kaca pembesar minus" di area overlay */
        }

        #fotoBesar {
            max-width: 90%;
            max-height: 90%;
            border-radius: 8px;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.5);
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // 1. Ambil elemen-elemen yang kita butuhkan dari HTML, simpan ke variabel.
        //    document.getElementById() = cara JavaScript "menunjuk" elemen HTML lewat id-nya.
        const fotoProduk  = document.getElementById('fotoProduk');   // foto kecil di card
        const overlayFoto = document.getElementById('overlayFoto');  // div gelap full-screen
        const fotoBesar   = document.getElementById('fotoBesar');    // <img> kosong di dalam overlay

        // 2. Kalau di halaman ini memang ada foto produk (fotoProduk tidak null)...
        if (fotoProduk) {

            // 3. Pasang "pendengar event" (event listener) ke foto kecil.
            //    Artinya: "kalau elemen fotoProduk ini di-klik, jalankan fungsi di bawah ini"
            fotoProduk.addEventListener('click', function () {

                // 4. Isi src foto besar dengan src foto kecil yang barusan diklik.
                //    (foto sama, cuma ditampilkan lebih besar)
                fotoBesar.src = fotoProduk.src;

                // 5. Ubah CSS overlay dari "display: none" jadi "display: flex" -> jadi keliatan.
                overlayFoto.style.display = 'flex';
            });

            // 6. Pasang juga event listener ke overlay-nya sendiri.
            //    Kalau area gelap ini diklik (di mana saja), overlay ditutup lagi.
            overlayFoto.addEventListener('click', function () {
                overlayFoto.style.display = 'none';
            });
        }
    </script>
</body>

</html>