<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - 404</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-bold text-danger">404</h1>
        <h3 class="fw-bold">Waduh, Kesasar Nih?</h3>
        <p class="text-muted">
            {{ $exception->getMessage() ?: 'Halaman yang kamu cari gak ketemu atau mungkin udah dihapus.' }}
        </p>
        <p class="text-muted">Halaman yang kamu cari gak ketemu atau mungkin udah dihapus.</p>
        <a href="{{ url('/dashboard') }}" class="btn btn-primary mt-3">Balik ke Dashboard, Yuk</a>
    </div>
</body>
</html>