<!-- memanggil file app.blade.php -->
@extends('layouts.app')

<!-- mengirimkan nilai title ke title untuk di tampilkan -->
@section('title', 'Login')

<!-- batas awal konten  -->
@section('content')
<div class="card text-center position-absolute top-50 start-50 translate-middle" style="width: 18rem;">
  <h5 class="card-header">Login POS</h5>
  <div class="card-body">
    <form action="{{ route('auth')}}" method="POST">
      @csrf
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Masukan Email Anda...</label>
    <input type="email" name="email" placeholder="contoh@gmail.com" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
    @error('email')
    <div class="badge text-bg-danger">{{ $message }}</div>
    @enderror
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" name="password" placeholder="Masukan Password Anda min 8" class="form-control" id="exampleInputPassword1">
  </div>
  @error('password')
    <div class="badge text-bg-danger">{{ $message }}</div>
  @enderror
  <!-- <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Check me out</label>
  </div> -->
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
  </div>
</div>
<!-- batas akhir konten -->
@endsection