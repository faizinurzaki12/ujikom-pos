<!-- memanggil file app.blade.php -->
@extends('layouts.app')

<!-- mengirimkan nilai title ke title untuk di tampilkan -->
@section('title', 'Halaman Uji Coba')

<!-- batas awal konten  -->
@section('content')
<button type="button" class="btn btn-primary">Primary</button>
<button type="button" class="btn btn-secondary">Secondary</button>
<button type="button" class="btn btn-success">Success</button>
<button type="button" class="btn btn-danger">Danger</button>
<button type="button" class="btn btn-warning">Warning</button>
<button type="button" class="btn btn-info">Info</button>
<button type="button" class="btn btn-light">Light</button>
<button type="button" class="btn btn-dark">Dark</button>

<button type="button" class="btn btn-link">Link</button>
<!-- batas akhir konten -->
@endsection