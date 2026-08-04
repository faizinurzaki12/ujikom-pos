<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - 403</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
    <h1 class="display-1 fw-bold text-warning">403?</h1>
    <h3 class="fw-bold">Ups, Mau Kemana Nih?</h3>
    <p class="text-muted">
        {{ $exception->getMessage() ?: 'Sepertinya halaman ini khusus buat Admin. Kamu gak punya hak akses ke sini!' }}
    </p>
    <p class="text-muted">
        Sepertinya halaman ini khusus buat Admin. Kamu gak punya hak akses ke sini!
    </p>
    <a href="{{ url('/dashboard') }}" class="btn btn-primary mt-3">Balik ke Dashboard, Yuk</a>
</div>
</body>
</html>