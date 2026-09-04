<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard POS - Responsive')</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
     <link rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
</head>
<body>
    <div class="wrapper">
        <!-- sidebar -->
        <div class="sidebar" id="sidebarMenu">
            <div class="d-flex justify-content-between align-items-center px-3 mb-4">
                <h4 class="mb-0 text-center" style="color: #0d6efd;">POS Toko Handphone Danzz</h4>
                <button class="btn btn-dark d-lg-none text-white" id="sidebarClose">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                @can('viewAny', App\Models\User::class)
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ url('users') }}">Users</a>
                </li>
                @endcan
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('jenis*') ? 'active' : '' }}" href="{{ route('jenis.index') }}">Jenis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('produk*') ? 'active' : '' }}" href="{{ route('produk.index') }}">Produk</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('penjualan*') ? 'active' : '' }}" href="{{ route('penjualan.index') }}">Penjualan</a>
                </li>
                @can('viewAny', App\Models\User::class)
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('laporan*') ? 'active' : '' }}" href="{{ route('laporan.bulanan') }}">Rekap Bulanan</a>
                </li>
                @endcan
            </ul>

            <!-- Bagian Tombol Logout yang Memicu Modal -->
            <div class="logout d-flex justify-content-center">
                <button type="button" class="nav-link text-danger border-0  w-100 text-center" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    Logout
                </button>
            </div>
        </div>

        <!-- modal logout -->
        <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow rounded-4">
                    <div class="modal-body text-center p-4">
                        <div class="mb-3">
                            <i class="bi bi-exclamation-circle text-danger display-4"></i>
                        </div>
                        <h5 class="fw-bold mb-2" id="logoutModalLabel">Konfirmasi Keluar</h5>
                        <p class="text-muted mb-4">Apakah Anda yakin ingin keluar dari aplikasi POS?</p>
                        <div class="d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Batal</button>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger m-0 rounded-pill">Ya, Keluar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- main kontent -->
        <div class="main-container">
            <nav class="navbar navbar-expand-lg navbar-light bg-light px-3 px-md-4 border-bottom navbar-top">
                <div class="container-fluid px-0">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-outline-secondary me-3 d-lg-none" id="sidebarToggle" type="button">
                            <i class="bi bi-list"></i>
                        </button>
                        <a class="navbar-brand judul badge bg-primary mb-0">
                            @if(auth()->user()->role?->name == 'admin')
                                Admin - {{ auth()->user()->name }}
                            @else
                                Kasir - {{ auth()->user()->name }}
                            @endif
                        </a>
                    </div>
                </div>
            </nav>

            <div class="main-content {{ Request::routeIs('dashboard') || Request::routeIs('laporan.bulanan') ? 'no-page-scroll' : '' }}">
               @if(session('success'))
                <div class="popup-alert-overlay" id="popupAlert">
                    <div class="popup-alert popup-success">
                        <i class="bi bi-check-circle-fill popup-icon"></i>
                        <p class="popup-message">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="popup-alert-overlay" id="popupAlert">
                    <div class="popup-alert popup-error">
                        <i class="bi bi-x-circle-fill popup-icon"></i>
                        <p class="popup-message">{{ session('error') }}</p>
                    </div>
                </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebarMenu');
        const toggleBtn = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('sidebarClose');

        if(toggleBtn) {
            toggleBtn.addEventListener('click', () => { sidebar.classList.toggle('show'); });
        }
        if(closeBtn) {
            closeBtn.addEventListener('click', () => { sidebar.classList.remove('show'); });
        }

        const popupAlert = document.getElementById('popupAlert');
        if (popupAlert) {
            setTimeout(() => {
                popupAlert.classList.add('popup-hide');
                // hapus dari DOM setelah animasi fade selesai
                setTimeout(() => popupAlert.remove(), 400);
            }, 3000);
        }
    </script>
</body>
</html>