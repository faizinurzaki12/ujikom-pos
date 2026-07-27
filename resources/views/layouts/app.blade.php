<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- isi tittle yang kita kirimkan dari views lain -->
    <title>@yield('title')</title>
    <!-- memanggil link boostrap -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container">
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success')}}
        </div>
        @endif
        <!-- isi konten yang kita kirimkan dari views lain -->
        @yield('content')
    </div>
</body>
</html>